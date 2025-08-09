<?php

namespace App\Http\Controllers;

use App\Models\OnboardingSession;
use App\Models\Tenant;
use App\Jobs\ProvisionTenantJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OnboardingController extends Controller
{
    public function index()
    {
        return view('onboarding.index');
    }

    public function step1(Request $request)
    {
        $session = $this->getOrCreateSession($request);
        
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|unique:onboarding_sessions,email,' . $session->id,
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $session->update([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'current_step' => 'password'
            ]);

            return redirect()->route('onboarding.step2', ['token' => $session->token]);
        }

        return view('onboarding.step1', compact('session'));
    }

    public function step2(Request $request)
    {
        $session = $this->getSession($request);
        
        if (!$session || !$session->canProceedToStep('password')) {
            return redirect()->route('onboarding.step1');
        }

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
                'password_confirmation' => 'required|same:password',
            ], [
                'password.regex' => 'Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character.'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $session->update([
                'password_hash' => Hash::make($request->password),
                'current_step' => 'company'
            ]);

            return redirect()->route('onboarding.step3', ['token' => $session->token]);
        }

        return view('onboarding.step2', compact('session'));
    }

    public function step3(Request $request)
    {
        $session = $this->getSession($request);
        
        if (!$session || !$session->canProceedToStep('company')) {
            return redirect()->route('onboarding.step1');
        }

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string|max:255',
                'subdomain' => 'required|string|max:63|regex:/^[a-z0-9-]+$/|unique:onboarding_sessions,subdomain,' . $session->id,
            ], [
                'subdomain.regex' => 'Subdomain can only contain lowercase letters, numbers, and hyphens.',
                'subdomain.unique' => 'This subdomain is already taken.'
            ]);

            // Check for reserved keywords
            $reservedKeywords = ['admin', 'www', 'landlord', 'api', 'mail', 'ftp', 'cpanel'];
            if (in_array(strtolower($request->subdomain), $reservedKeywords)) {
                $validator->errors()->add('subdomain', 'This subdomain is reserved and cannot be used.');
                return back()->withErrors($validator)->withInput();
            }

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $session->update([
                'company_name' => $request->company_name,
                'subdomain' => strtolower(trim($request->subdomain)),
                'current_step' => 'billing'
            ]);

            return redirect()->route('onboarding.step4', ['token' => $session->token]);
        }

        return view('onboarding.step3', compact('session'));
    }

    public function step4(Request $request)
    {
        $session = $this->getSession($request);
        
        if (!$session || !$session->canProceedToStep('billing')) {
            return redirect()->route('onboarding.step1');
        }

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'billing_name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'country' => 'required|string|max:100',
                'phone' => 'required|string|max:20',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $session->update([
                'billing_info' => [
                    'billing_name' => $request->billing_name,
                    'address' => $request->address,
                    'country' => $request->country,
                    'phone' => $request->phone,
                ],
                'current_step' => 'confirmation'
            ]);

            return redirect()->route('onboarding.step5', ['token' => $session->token]);
        }

        return view('onboarding.step4', compact('session'));
    }

    public function step5(Request $request)
    {
        $session = $this->getSession($request);
        
        if (!$session || !$session->canProceedToStep('confirmation')) {
            return redirect()->route('onboarding.step1');
        }

        if ($request->isMethod('post')) {
            // Create tenant record
            $tenant = Tenant::create([
                'name' => $session->company_name,
                'subdomain' => $session->subdomain,
                'database_name' => 'tenant_' . $session->subdomain,
                'domain' => $session->subdomain . '.' . config('app.domain', 'myapp.test'),
                'status' => 'pending',
                'data' => [
                    'onboarding_session_id' => $session->id,
                    'billing_info' => $session->billing_info,
                    'company_metadata' => $session->company_metadata,
                ]
            ]);

            // Dispatch provisioning job
            ProvisionTenantJob::dispatch($tenant, $session);

            // Mark session as completed
            $session->complete();

            return redirect()->route('onboarding.success', ['token' => $session->token]);
        }

        return view('onboarding.step5', compact('session'));
    }

    public function success(Request $request)
    {
        $session = $this->getSession($request);
        
        if (!$session || !$session->is_completed) {
            return redirect()->route('onboarding.step1');
        }

        return view('onboarding.success', compact('session'));
    }

    private function getOrCreateSession(Request $request): OnboardingSession
    {
        $token = $request->get('token');
        
        if ($token) {
            $session = OnboardingSession::where('token', $token)
                ->where('expires_at', '>', now())
                ->first();
            
            if ($session) {
                return $session;
            }
        }

        return OnboardingSession::create([
            'full_name' => '',
            'email' => '',
            'password_hash' => '',
            'company_name' => '',
            'subdomain' => '',
            'current_step' => 'account',
        ]);
    }

    private function getSession(Request $request): ?OnboardingSession
    {
        $token = $request->get('token');
        
        if (!$token) {
            return null;
        }

        return OnboardingSession::where('token', $token)
            ->where('expires_at', '>', now())
            ->first();
    }
}



