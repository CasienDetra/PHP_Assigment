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

// Staff
$router->get('staff/pos', 'StaffController@index');
$router->post('staff/order', 'StaffController@storeOrder');
