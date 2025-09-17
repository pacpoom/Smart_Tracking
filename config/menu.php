<?php

// โครงสร้างเมนูสำหรับ Sidebar
return [
    [
        'title' => 'Dashboard',
        'icon' => 'dashboard', // ชื่อไอคอนจาก Material Symbols Rounded
        'route' => 'dashboard',
        'permission' => null, // ไม่ต้องใช้ permission
    ],
    [
        'title' => 'User Management',
        'icon' => 'group',
        'route' => 'users.index', // สมมติว่ามี Route นี้
        'permission' => 'manage users', // ต้องมี permission 'manage users' ถึงจะเห็น
    ],
    [
        'title' => 'Profile',
        'icon' => 'person',
        'route' => 'profile.edit',
        'permission' => null,
    ],
    [
        'title' => 'Role Management',
        'icon' => 'admin_panel_settings',
        'route' => 'roles.index',
        'permission' => 'view roles', // ต้องมีสิทธิ์ 'view roles' ถึงจะเห็นเมนูนี้
    ],
    [
        'title' => 'Permission Management',
        'icon' => 'policy', // ไอคอนรูปโล่
        'route' => 'permissions.index',
        'permission' => 'view permissions', // ต้องมีสิทธิ์นี้ถึงจะเห็น
    ],
];
