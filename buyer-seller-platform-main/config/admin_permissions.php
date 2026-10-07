<?php

return [
    'definitions' => [
        'buyers.view' => 'View buyers',
        'sellers.view' => 'View sellers',
        'sellers.approve' => 'Approve sellers',
        'sellers.reject' => 'Reject sellers',
        'products.view' => 'View products',
        'products.approve' => 'Approve products',
        'categories.manage' => 'Manage categories',
        'inquiries.view' => 'View product enquiries',
        'requirements.view' => 'View bulk requirements',
        'notifications.manage' => 'Manage notifications',
        'support.view' => 'View support tickets and reports',
        'support.reply' => 'Reply to support tickets and reports',
        'staff.manage' => 'Manage staff users',
    ],

    'groups' => [
        [
            'key' => 'buyers',
            'label' => 'Buyer Management',
            'permissions' => ['buyers.view'],
        ],
        [
            'key' => 'sellers',
            'label' => 'Seller Management',
            'permissions' => ['sellers.view', 'sellers.approve', 'sellers.reject'],
        ],
        [
            'key' => 'products',
            'label' => 'Product Management',
            'permissions' => ['products.view', 'products.approve'],
        ],
        [
            'key' => 'categories',
            'label' => 'Categories',
            'permissions' => ['categories.manage'],
        ],
        [
            'key' => 'inquiries',
            'label' => 'Product Enquiries',
            'permissions' => ['inquiries.view'],
        ],
        [
            'key' => 'requirements',
            'label' => 'Bulk Requirements',
            'permissions' => ['requirements.view'],
        ],
        [
            'key' => 'notifications',
            'label' => 'Notifications',
            'permissions' => ['notifications.manage'],
        ],
        [
            'key' => 'support',
            'label' => 'Support & Reports',
            'permissions' => ['support.view', 'support.reply'],
        ],
        [
            'key' => 'staff',
            'label' => 'Staff Management',
            'permissions' => ['staff.manage'],
        ],
    ],
];
