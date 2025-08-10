# SaaS Multi-Tenant Onboarding Platform

A complete Laravel-based SaaS platform with isolated database multi-tenancy, featuring a comprehensive onboarding flow for new tenants.

## 🏗️ Architecture Overview

This platform implements a three-environment architecture:

### 1. Root Environment (`http://appcrates.myapp.test/`)
- **Purpose**: Public-facing entry point for onboarding
- **Database**: Landlord database (global)
- **Features**: Multi-step onboarding flow, tenant provisioning


## 🚀 Features Implemented

### ✅ Onboarding Flow (5 Steps)
1. **Account Information**: Collect user name and email
2. **Password Setup**: Secure password creation with validation
3. **Company Details**: Company name and subdomain reservation
4. **Billing Information**: Billing address and contact details
5. **Confirmation**: Review and trigger tenant provisioning

### ✅ Multi-Tenancy Features
- **Isolated Database Model**: Each tenant has its own database
- **Subdomain Resolution**: Automatic tenant detection via subdomain
- **Dynamic Database Configuration**: Runtime database connection switching
- **Tenant Provisioning**: Automated workspace creation via queues

### ✅ Security & Validation
- **Email Uniqueness**: Global email validation across all tenants
- **Subdomain Validation**: Reserved keyword protection
- **Password Security**: Strong password requirements
- **Session Management**: Secure token-based onboarding sessions

### ✅ Queue System
- **Background Processing**: Tenant provisioning via Laravel queues
- **Retry Logic**: Failed provisioning with automatic retries
- **Status Tracking**: Real-time provisioning status updates
## 🎥 Demo Video

Watch the complete onboarding and multi-tenant setup demo here:  
[▶ Click to Watch on Loom](https://www.loom.com/share/6b042bdd29c64643950e12a4c6b78754)
## 📁 Project Structure

```
assesment/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── OnboardingController.php      # Onboarding flow
│   │   │   └── Landlord/
│   │   │       └── TenantController.php      # Admin management
│   │   └── Middleware/
│   │       └── ResolveTenant.php            # Tenant resolution
│   ├── Jobs/
│   │   └── ProvisionTenantJob.php           # Tenant provisioning
│   └── Models/
│       ├── Tenant.php                       # Tenant model
│       └── OnboardingSession.php            # Onboarding sessions
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_tenants_table.php
│   │   └── 2024_01_01_000002_create_onboarding_sessions_table.php
│   └── migrations/tenant/
│       └── 2024_01_01_000000_create_users_table.php
├── resources/views/
│   ├── onboarding/                         # Onboarding views
│   ├── landlord/                           # Admin views
│   └── tenant/                             # Tenant views
└── routes/
    └── web.php                             # Environment-specific routes
```

## 🛠️ Installation & Setup

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=landlord_db
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database
```

### 3. Database Setup
```bash
php artisan migrate
```

### 4. Virtual Host Configuration
Add to your local hosts file (`/etc/hosts` on Linux/Mac, `C:\Windows\System32\drivers\etc\hosts` on Windows):
```
127.0.0.1 appcrat.myapp.test
```

### 5. Start Queue Worker
```bash
php artisan queue:work
```

## 🎯 Usage Guide

### Root Environment (Onboarding)
1. Visit `https://myapp.test`
2. Click "Start Onboarding"
3. Complete the 5-step process
4. Tenant will be provisioned automatically


## 🔧 Key Components

### OnboardingController
Handles the complete 5-step onboarding flow with:
- Session management
- Step validation
- Data persistence
- Tenant creation

### ProvisionTenantJob
Background job that:
- Creates tenant database
- Runs migrations
- Creates initial user
- Updates tenant status

### ResolveTenant Middleware
Automatically:
- Extracts subdomain from request
- Resolves tenant from landlord database
- Configures tenant-specific database connection
- Handles tenant context switching

## 🧪 Testing the Implementation

### 1. Test Onboarding Flow
```bash
# Visit onboarding
http://appcrates.myapp.test/
## 🔒 Security Features

- **Email Validation**: Global uniqueness across all tenants
- **Subdomain Protection**: Reserved keyword blocking
- **Password Requirements**: Strong password enforcement
- **Session Security**: Token-based session management
- **Database Isolation**: Complete tenant data separation

## 🚀 Production Considerations

1. **Queue Configuration**: Use Redis for production queues
2. **Database Optimization**: Implement connection pooling
3. **Monitoring**: Add tenant provisioning monitoring
4. **Backup Strategy**: Implement tenant database backups
5. **SSL Configuration**: Secure all subdomains with SSL

## 📝 API Endpoints

### Root Environment
- `GET /onboarding` - Onboarding index
- `GET/POST /onboarding/step1` - Account information
- `GET/POST /onboarding/step2` - Password setup
- `GET/POST /onboarding/step3` - Company details
- `GET/POST /onboarding/step4` - Billing information
- `GET/POST /onboarding/step5` - Confirmation
- `GET /onboarding/success` - Success page


✅ **Multi-step onboarding flow** with persistent state  
✅ **Email uniqueness validation** across all tenants  
✅ **Subdomain reservation** with validation  
✅ **Background tenant provisioning** via queues  
✅ **Isolated database multi-tenancy**  
✅ **Environment separation** (root/landlord/tenant)  
✅ **Admin interface** for tenant management  
✅ **Secure session handling** with token-based flow  
✅ **Comprehensive validation** at each step  
✅ **Production-ready architecture** with proper error handling  

This implementation provides a complete, scalable SaaS onboarding platform ready for production deployment.





