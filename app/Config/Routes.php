<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Auth::login');

$routes->get('login', 'Auth::login');
$routes->post('loggingin', 'Auth::process');
$routes->get('logout', 'Auth::logout');
$routes->get('dashboard', 'Home::dashboard');

/* 
 * Categories
*/
$routes->get('category', 'Category::index');
$routes->get('categoryData', 'Category::getData');
$routes->post('categorySave', 'Category::process');
$routes->post('categoryEdit', 'Category::edit');
$routes->get('categoryEditBrand/(:num)', 'Category::editBrand/$1');
$routes->delete('categoryDelete/(:num)', 'Category::delete/$1');
$routes->get('categoryDetail/(:num)', 'Category::detail/$1');
// $routes->get('categoryDetails/(:num)', 'Category::detailData/$1');

/* 
* Brands
*/
$routes->get('brand', 'Brand::index');
$routes->get('brandData', 'Brand::getData');
$routes->get('brandData/(:num)', 'Brand::getData/$1');
$routes->post('brandSave', 'Brand::process');
$routes->get('brandEdit/(:num)', 'Brand::edit/$1');
$routes->delete('brandDelete/(:num)', 'Brand::delete/$1');


/* 
* Products
*/
$routes->get('product', 'Products::index');
$routes->get('productData', 'Products::getData');
$routes->post('productSave', 'Products::process');
$routes->get('productAdd', 'Products::add');
$routes->get('productEdit/(:num)', 'Products::edit/$1');
$routes->get('productChangePrice/(:num)', 'Products::editPrice/$1');
$routes->delete('productDelete/(:num)', 'Products::delete/$1');
$routes->get('productDetail/(:num)', 'Products::detail/$1');
$routes->post('productFind', 'Products::find');
$routes->post('productStock', 'Products::stock');

/* 
* Transaction
*/
$routes->get('transaction', 'Transaction::index');


/* 
* Member
*/
$routes->get('member', 'Member::index');
$routes->get('memberData', 'Member::getData');
$routes->get('memberEdit/(:num)', 'Member::edit/$1');
$routes->get('memberDetail/(:num)', 'Member::detail/$1');
$routes->post('memberSave', 'Member::process');
$routes->get('memberGet', 'Member::getAll');

/* 
* Discount
*/
$routes->get('discount', 'Discount::index');
$routes->get('discountData', 'Discount::getData');
$routes->get('discountAdd', 'Discount::add');
$routes->get('discountEdit/(:num)', 'Discount::edit/$1');
$routes->post('discountSave', 'Discount::process');

/* 
* Setting
*/
$routes->get('setting', 'Setting::index');
$routes->post('pointSave', 'Setting::processPoint');



$routes->get('insertUser', 'Auth::testInsert');
$routes->get('showAllUser', 'Auth::showAllUser');
$routes->get('deleteUser/(:num)', 'Auth::testDelete/$1');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
