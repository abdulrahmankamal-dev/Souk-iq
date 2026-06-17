<?php
/**
 * SOUK.IQ Routing Map
 * Maps path patterns to Controller@action
 */

return [
    // Public routes
    'GET' => [
        '/' => 'HomeController@index',
        '/search' => 'SearchController@index',
        '/product/([^/]+)/([^/]+)' => 'ProductController@detail', // /product/{store-slug}/{product-slug}
        '/store/([^/]+)' => 'StoreController@profile', // /store/{slug}
        '/login' => 'AuthController@showLogin',
        '/register' => 'AuthController@showRegister',
        '/logout' => 'AuthController@logout',
        '/lang/change' => 'HomeController@changeLanguage',
        '/install' => 'HomeController@showInstall',
        
        // Customer dashboard routes
        '/dashboard' => 'CustomerController@index',
        '/dashboard/favorites' => 'CustomerController@favorites',
        '/dashboard/reviews' => 'CustomerController@reviews',
        '/dashboard/notifications' => 'CustomerController@notifications',
        '/dashboard/settings/profile' => 'CustomerController@settingsProfile',
        '/dashboard/settings/security' => 'CustomerController@settingsSecurity',
        '/dashboard/settings/privacy' => 'CustomerController@settingsPrivacy',
        '/dashboard/settings/notifications' => 'CustomerController@settingsNotifications',
        '/dashboard/settings/appearance' => 'CustomerController@settingsAppearance',
        
        // Store owner dashboard routes
        '/store-owner/dashboard' => 'StoreController@dashboard',
        '/store-owner/products' => 'StoreController@products',
        '/store-owner/products/create' => 'StoreController@createProduct',
        '/store-owner/products/edit/([0-9]+)' => 'StoreController@editProduct',
        '/store-owner/settings' => 'StoreController@settings',
        '/store-owner/reviews' => 'StoreController@reviews',
        '/store-owner/staff' => 'StoreController@staff',
        
        // Admin routes
        '/admin' => 'AdminController@index',
        '/admin/users' => 'AdminController@users',
        '/admin/stores' => 'AdminController@stores',
        '/admin/products' => 'AdminController@products',
        '/admin/categories' => 'AdminController@categories',
        '/admin/advertisements' => 'AdminController@advertisements',
        '/admin/reports' => 'AdminController@reports',
        
        // Super Admin routes
        '/super-admin' => 'SuperAdminController@index',
        '/super-admin/admins' => 'SuperAdminController@admins',
        '/super-admin/plans' => 'SuperAdminController@plans',
        '/super-admin/settings' => 'SuperAdminController@settings',
        '/super-admin/audit-logs' => 'SuperAdminController@auditLogs',

        // API routes
        '/api/v1/search' => 'ApiController@search',
        '/api/v1/products' => 'ApiController@products',
        '/api/v1/stores' => 'ApiController@stores',
        '/api/v1/notifications' => 'ApiController@notifications',
    ],
    'POST' => [
        '/login' => 'AuthController@login',
        '/register' => 'AuthController@register',
        '/install' => 'HomeController@install',
        '/lang/change' => 'HomeController@changeLanguage',
        
        // Dashboard form submits
        '/dashboard/settings/profile' => 'CustomerController@updateProfile',
        '/dashboard/settings/security' => 'CustomerController@updateSecurity',
        '/dashboard/settings/privacy' => 'CustomerController@updatePrivacy',
        '/dashboard/settings/notifications' => 'CustomerController@updateNotifications',
        '/dashboard/settings/appearance' => 'CustomerController@updateAppearance',
        '/dashboard/reviews/edit' => 'CustomerController@editReview',
        '/dashboard/reviews/delete' => 'CustomerController@deleteReview',
        
        // Actions
        '/product/favorite' => 'CustomerController@toggleFavorite',
        '/store/follow' => 'CustomerController@toggleFollow',
        '/product/review' => 'ProductController@addReview',
        '/store/review' => 'StoreController@addReview',
        '/store/contact' => 'StoreController@contactOwner',
        '/report/submit' => 'HomeController@submitReport',
        
        // Store owner dashboard submits
        '/store-owner/products/create' => 'StoreController@storeProduct',
        '/store-owner/products/edit/([0-9]+)' => 'StoreController@updateProduct',
        '/store-owner/products/delete' => 'StoreController@deleteProduct',
        '/store-owner/settings' => 'StoreController@updateStoreSettings',
        '/store-owner/reviews/reply' => 'StoreController@replyReview',
        '/store-owner/staff/invite' => 'StoreController@inviteStaff',
        '/store-owner/staff/delete' => 'StoreController@deleteStaff',
        
        // Admin operations
        '/admin/users/status' => 'AdminController@changeUserStatus',
        '/admin/stores/approve' => 'AdminController@approveStore',
        '/admin/stores/reject' => 'AdminController@rejectStore',
        '/admin/stores/suspend' => 'AdminController@suspendStore',
        '/admin/products/status' => 'AdminController@changeProductStatus',
        '/admin/categories/create' => 'AdminController@createCategory',
        '/admin/categories/edit' => 'AdminController@editCategory',
        '/admin/categories/delete' => 'AdminController@deleteCategory',
        '/admin/advertisements/create' => 'AdminController@createAd',
        '/admin/advertisements/status' => 'AdminController@changeAdStatus',
        '/admin/reports/resolve' => 'AdminController@resolveReport',
        
        // Super Admin operations
        '/super-admin/admins/create' => 'SuperAdminController@createAdmin',
        '/super-admin/plans/save' => 'SuperAdminController@savePlan',
        '/super-admin/settings/save' => 'SuperAdminController@saveSettings',
        
        // API operations
        '/api/v1/favorites/toggle' => 'ApiController@toggleFavorite',
        '/api/v1/notifications/read' => 'ApiController@markNotificationsRead',
    ]
];
