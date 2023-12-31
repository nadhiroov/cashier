<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('index', 'Home::index');
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
$routes->get('brandDetail/(:num)', 'Brand::detail/$1');
$routes->post('brandSave', 'Brand::process');
$routes->get('brandEdit/(:num)', 'Brand::edit/$1');
$routes->delete('brandDelete/(:num)', 'Brand::delete/$1');


/*
* Products
*/
$routes->get('product', 'Products::index');
$routes->get('productData', 'Products::getData');
$routes->get('productData/(:num)', 'Products::getData/$1');
$routes->post('productSave', 'Products::process');
$routes->post('productSavePrice', 'Products::processEditPrice');
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
$routes->post('transactionSave', 'Transaction::process');

/*
* Transaction History
*/
$routes->get('transHistory', 'Transaction::historyIndex');
$routes->get('transHistoryData', 'Transaction::getDataHistory');
$routes->get('transDetail/(:num)', 'Transaction::detailTrans/$1');
$routes->get('transDetailData/(:num)', 'Transaction::detailTransData/$1');
$routes->get('transPrint/(:num)', 'Transaction::print/$1');

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
$routes->delete('discountDelete/(:num)', 'Discount::delete/$1');

/*
* Setting
*/
$routes->get('setting', 'Setting::index');
$routes->post('pointSave', 'Setting::processPoint');
$routes->get('downloadSKU', 'Setting::download');
$routes->delete('resetPoint', 'Setting::resetPoint');


/*
* User
*/
$routes->get('user', 'User::index');
$routes->get('userData', 'User::getData');
$routes->get('userAdd', 'User::add');
$routes->get('userEdit/(:num)', 'User::edit/$1');
$routes->post('userSave', 'User::process');
$routes->delete('userDelete/(:num)', 'User::delete/$1');

/*
* Report
*/
$routes->get('rbyProduct', 'Report::ByProduct');
$routes->get('rbyProductData', 'Report::getDataByProduct');
$routes->get('detailByProduct/(:num)', 'Report::detailByProduct/$1');
$routes->post('detailByProductDataDaily', 'Report::detailByProductDataDaily');
$routes->post('detailByProductDataMontly', 'Report::detailByProductDataMontly');
$routes->post('detailByProductDataPrice', 'Report::detailByProductDataPrice');
$routes->post('detailByProductDataIncoming', 'Report::detailByProductDataIncoming');
$routes->get('rbyTransaction', 'Report::byTransaction');
$routes->post('byTransactionDataDaily', 'Report::transactionDaily');
$routes->post('byTransactionMonthly', 'Report::transactionMonthly');

$routes->get('rbyTransactionDiagram', 'Report::rbyTransactionDiagram');
$routes->post('byTransactionDiagramDataMonthly', 'Report::transactionDiagramMonthly');
$routes->post('byTransactionDiagramDataAnnual', 'Report::transactionDiagramAnnual');



$routes->get('insertUser', 'Auth::testInsert');
$routes->get('showAllUser', 'Auth::showAllUser');
$routes->get('deleteUser/(:num)', 'Auth::testDelete/$1');
$routes->get('print', 'Transaction::testPrint');