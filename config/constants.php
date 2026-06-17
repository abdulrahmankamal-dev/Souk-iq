<?php
/**
 * SOUK.IQ App Constants
 */

// Governorates of Iraq
const GOVERNORATES = [
    'ar' => [
        'Baghdad' => 'بغداد',
        'Basra' => 'البصرة',
        'Erbil' => 'أربيل',
        'Sulaymaniyah' => 'السليمانية',
        'Duhok' => 'دهوك',
        'Kirkuk' => 'كركوك',
        'Nineveh' => 'نينوى',
        'Najaf' => 'النجف',
        'Karbala' => 'كربلاء',
        'Anbar' => 'الأنبار',
        'Saladin' => 'صلاح الدين',
        'Diyala' => 'ديالى',
        'Wasit' => 'واسط',
        'Babylon' => 'بابل',
        'Dhi Qar' => 'ذي قار',
        'Muthanna' => 'المثنى',
        'Qadisiya' => 'القادسية',
        'Maysan' => 'ميسان'
    ],
    'ku' => [
        'Baghdad' => 'بەغدا',
        'Basra' => 'بەسرە',
        'Erbil' => 'هەولێر',
        'Sulaymaniyah' => 'سلێمانی',
        'Duhok' => 'دهۆک',
        'Kirkuk' => 'کەرکوک',
        'Nineveh' => 'نەینەوا',
        'Najaf' => 'نەجەف',
        'Karbala' => 'کەربەلا',
        'Anbar' => 'ئەنبار',
        'Saladin' => 'سەلاحەدین',
        'Diyala' => 'دیالە',
        'Wasit' => 'واست',
        'Babylon' => 'بابلی',
        'Dhi Qar' => 'ذی قار',
        'Muthanna' => 'موسەنا',
        'Qadisiya' => 'قادسیە',
        'Maysan' => 'میسان'
    ],
    'en' => [
        'Baghdad' => 'Baghdad',
        'Basra' => 'Basra',
        'Erbil' => 'Erbil',
        'Sulaymaniyah' => 'Sulaymaniyah',
        'Duhok' => 'Duhok',
        'Kirkuk' => 'Kirkuk',
        'Nineveh' => 'Nineveh',
        'Najaf' => 'Najaf',
        'Karbala' => 'Karbala',
        'Anbar' => 'Anbar',
        'Saladin' => 'Saladin',
        'Diyala' => 'Diyala',
        'Wasit' => 'Wasit',
        'Babylon' => 'Babylon',
        'Dhi Qar' => 'Dhi Qar',
        'Muthanna' => 'Muthanna',
        'Qadisiya' => 'Qadisiya',
        'Maysan' => 'Maysan'
    ]
];

// Roles
const ROLE_VISITOR = 'visitor';
const ROLE_CUSTOMER = 'customer';
const ROLE_STORE_OWNER = 'store_owner';
const ROLE_ADMIN = 'admin';
const ROLE_SUPER_ADMIN = 'super_admin';

// Store Statuses
const STORE_STATUS_PENDING = 'pending';
const STORE_STATUS_ACTIVE = 'active';
const STORE_STATUS_SUSPENDED = 'suspended';
const STORE_STATUS_REJECTED = 'rejected';
const STORE_STATUS_CLOSED = 'closed';

// Product Statuses
const PRODUCT_STATUS_DRAFT = 'draft';
const PRODUCT_STATUS_PENDING = 'pending';
const PRODUCT_STATUS_ACTIVE = 'active';
const PRODUCT_STATUS_REJECTED = 'rejected';
const PRODUCT_STATUS_ARCHIVED = 'archived';

// Product Conditions
const CONDITION_NEW = 'new';
const CONDITION_USED = 'used';
const CONDITION_REFURBISHED = 'refurbished';

// Stock Statuses
const STOCK_IN_STOCK = 'in_stock';
const STOCK_OUT_OF_STOCK = 'out_of_stock';
const STOCK_LIMITED = 'limited';
