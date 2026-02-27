<?php

// Auth
$router->get('', 'AuthController@login'); // Home page is login
$router->get('login', 'AuthController@login');
$router->post('login', 'AuthController@authenticate');
$router->get('logout', 'AuthController@logout');

// Admin
$router->get('admin/dashboard', 'AdminController@index');
$router->get('admin/menu/create', 'AdminController@create');
$router->post('admin/menu/store', 'AdminController@store');
$router->get('admin/menu/edit', 'AdminController@edit');
$router->post('admin/menu/update', 'AdminController@update');
$router->post('admin/menu/delete', 'AdminController@delete');

// Admin User Management
$router->get('admin/manage-admins', 'AdminController@manageAdmins');
$router->get('admin/admins/create', 'AdminController@createAdmin');
$router->post('admin/admins/store', 'AdminController@storeAdmin');
$router->get('admin/admins/edit', 'AdminController@editAdmin');
$router->post('admin/admins/update', 'AdminController@updateAdmin');
$router->post('admin/admins/delete', 'AdminController@deleteAdmin');

// Admin Staff Management
$router->get('admin/manage-staff', 'AdminController@manageStaff');
$router->get('admin/staff/create', 'AdminController@createStaff');
$router->post('admin/staff/store', 'AdminController@storeStaff');
$router->get('admin/staff/edit', 'AdminController@editStaff');
$router->post('admin/staff/update', 'AdminController@updateStaff');
$router->post('admin/staff/delete', 'AdminController@deleteStaff');
$router->post('admin/staff/toggle-status', 'AdminController@toggleStaffStatus');

// Staff
$router->get('staff/pos', 'StaffController@index');
$router->post('staff/order', 'StaffController@storeOrder');
$router->get('staff/orders', 'StaffController@viewOrders');
$router->get('staff/orders/view', 'StaffController@viewOrderDetails');

