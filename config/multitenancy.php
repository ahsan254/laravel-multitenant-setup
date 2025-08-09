<?php

return [
    /*
     * This class is responsible for determining which tenant should be current
     * for the given request.
     *
     * This class should extend `Spatie\Multitenancy\TenantFinder\TenantFinder`
     *
     */
    'tenant_finder' => Spatie\Multitenancy\TenantFinder\DomainTenantFinder::class,

    /*
     * These fields are used by tenant:artisan command to match one or more tenant
     */
    'tenant_artisan_search_fields' => [
        'id',
        'name',
        'subdomain',
    ],

    /*
     * These tasks will be performed when switching between a tenant.
     *
     * A valid task is any class that implements Spatie\Multitenancy\Tasks\SwitchTenantTask
     */
    'switch_tenant_tasks' => [
        // Spatie\Multitenancy\Tasks\SwitchRouteCacheTask::class,
        // Spatie\Multitenancy\Tasks\SwitchConfigTask::class,
        // Spatie\Multitenancy\Tasks\SwitchDatabaseTask::class,
        // Spatie\Multitenancy\Tasks\SwitchCacheTask::class,
        // Spatie\Multitenancy\Tasks\SwitchQueueConnectionTask::class,
        // Spatie\Multitenancy\Tasks\SwitchFilesystemDisksTask::class,
        // Spatie\Multitenancy\Tasks\SwitchLogContextTask::class,
        // Spatie\Multitenancy\Tasks\SwitchTenantDatabaseTask::class,
    ],

    /*
     * This class is the model used for storing configuration on tenants.
     *
     * It must be or extend `Spatie\Multitenancy\Models\Tenant::class`
     */
    'tenant_model' => \App\Models\Tenant::class,

    /*
     * If there is a current tenant when dispatching a job, the job will be automatically
     * assigned to the current tenant. When set to true, you don't need to specify
     * the tenant when dispatching a job.
     */
    'automatically_make_jobs_tenant_aware' => true,

    /*
     * The connection name to the database where the `tenants` table is stored.
     *
     * Set to `null` to use the default connection.
     */
    'landlord_connection' => null,

    /*
     * This key will be used to bind the current tenant in the container.
     */
    'current_tenant_container_key' => 'currentTenant',

    /*
     * You can customize some of the behavior of this package by using our own custom action.
     * Your custom action should always extend the default one.
     */
    'actions' => [
        'make_tenant_current_action' => Spatie\Multitenancy\Actions\MakeTenantCurrentAction::class,
        'forget_current_tenant_action' => Spatie\Multitenancy\Actions\ForgetCurrentTenantAction::class,
        'make_queue_tenant_aware_action' => Spatie\Multitenancy\Actions\MakeQueueTenantAwareAction::class,
        'migrate_tenant' => Spatie\Multitenancy\Actions\MigrateTenant::class,
    ],

    /*
     * Models in this array will be automatically "cast" to the current tenant
     * when creating/updating them. You can create your own casts by implementing
     * the `Spatie\Multitenancy\Models\Concerns\BelongsToTenant` interface.
     */
    'tenant_aware_models' => [
        // \App\Models\TenantAwareModel::class,
    ],

    /*
     * If you want to prevent to create a tenant-aware databases, you can
     * configure this setting. If set to false, the `CreateDatabase` task will
     * not be performed.
     */
    'create_tenant_databases' => true,

    /*
     * The connection name to the database where the `tenants` table is stored.
     *
     * Set to `null` to use the default connection.
     */
    'tenant_database_connection_name' => 'tenant',

    /*
     * This key will be used to bind the current tenant in the container.
     */
    'current_tenant_database_container_key' => 'currentTenantDatabase',

    /*
     * You can customize some of the behavior of this package by using our own custom action.
     * Your custom action should always extend the default one.
     */
    'database_actions' => [
        'make_tenant_current_action' => Spatie\Multitenancy\Actions\MakeTenantCurrentAction::class,
        'forget_current_tenant_action' => Spatie\Multitenancy\Actions\ForgetCurrentTenantAction::class,
        'make_queue_tenant_aware_action' => Spatie\Multitenancy\Actions\MakeQueueTenantAwareAction::class,
        'migrate_tenant' => Spatie\Multitenancy\Actions\MigrateTenant::class,
    ],

    /*
     * Models in this array will be automatically "cast" to the current tenant
     * when creating/updating them. You can create your own casts by implementing
     * the `Spatie\Multitenancy\Models\Concerns\BelongsToTenant` interface.
     */
    'tenant_aware_models' => [
        // \App\Models\TenantAwareModel::class,
    ],

    /*
     * If you want to prevent to create a tenant-aware databases, you can
     * configure this setting. If set to false, the `CreateDatabase` task will
     * not be performed.
     */
    'create_tenant_databases' => true,
];



