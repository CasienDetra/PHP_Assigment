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
$router->post('admin/menu/delete', 'AdminController@delete');

// Staff
$router->get('staff/pos', 'StaffController@index');
$router->post('staff/order', 'StaffController@storeOrder');
