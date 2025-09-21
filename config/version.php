<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Version
    |--------------------------------------------------------------------------
    |
    | This value determines the current version of your application.
    | This value is used when the framework needs to place the application's
    | version in a notification or any other location as required by the
    | application or its packages.
    |
    */
    'version' => env('APP_VERSION', '3.0.0'),
    
    /*
    |--------------------------------------------------------------------------
    | Version Release Date
    |--------------------------------------------------------------------------
    |
    | The date when this version was released.
    |
    */
    'release_date' => env('APP_RELEASE_DATE', '2025-08-30'),
    
    /*
    |--------------------------------------------------------------------------
    | Version Codename
    |--------------------------------------------------------------------------
    |
    | A codename for the current version (optional).
    |
    */
    'codename' => env('APP_VERSION_CODENAME', 'Kurumsal'),
    
    /*
    |--------------------------------------------------------------------------
    | Version Description
    |--------------------------------------------------------------------------
    |
    | A brief description of what's new in this version.
    |
    */
    'description' => env('APP_VERSION_DESCRIPTION', 'Major Release - Kapsamlı Sistem Güncellemeleri'),
    
    /*
    |--------------------------------------------------------------------------
    | Version Features
    |--------------------------------------------------------------------------
    |
    | Key features introduced in this version.
    |
    */
    'features' => [
        'MRR (Monthly Recurring Revenue) System',
        'PaymentObserver Architecture',
        'Automated Invoice Creation',
        'Customer Balance Management',
        'Modern Quote PDF Design',
        'Reconciliation System',
        'Service Status Automation',
        'Email Integration',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Version Improvements
    |--------------------------------------------------------------------------
    |
    | UI/UX improvements in this version.
    |
    */
    'improvements' => [
        'Customer balance display accuracy',
        'Dashboard MRR metrics integration',
        'Quote form auto-fill functionality',
        'Invoice status automation',
        'Database query optimization',
        'Error handling and logging',
        'Cache management system',
        'PDF generation performance',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Version Bug Fixes
    |--------------------------------------------------------------------------
    |
    | Bug fixes in this version.
    |
    */
    'bug_fixes' => [
        'Fixed customer balance inconsistencies',
        'Resolved MRR calculation issues',
        'Fixed quote title saving problems',
        'Corrected invoice status updates',
        'Fixed payment method field errors',
        'Resolved database column missing errors',
        'Fixed PDF generation warnings',
        'Corrected ledger entry calculations',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Version Breaking Changes
    |--------------------------------------------------------------------------
    |
    | Breaking changes that require attention when upgrading.
    |
    */
    'breaking_changes' => [
        'Database schema updates (new columns added)',
        'Payment method field renamed (method → payment_method)',
        'MRR calculation logic changed (installment-only)',
        'Invoice status automation implementation',
        'Customer balance calculation method updated',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Version Upgrade Notes
    |--------------------------------------------------------------------------
    |
    | Important notes for upgrading to this version.
    |
    */
    'upgrade_notes' => [
        'Backup your database before upgrading',
        'Run database migrations for new columns',
        'Clear application cache after upgrade',
        'Update payment method references in custom code',
        'Review MRR calculations for accuracy',
        'Test invoice and payment workflows',
    ],
];
