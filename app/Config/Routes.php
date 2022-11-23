<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

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
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/(?i)users','Users::index',['filter' => 'pagesfilter']);
$routes->get('/(?i)registration', 'Registration::index');
$routes->get('/(?i)signin', 'SignIn::index');
$routes->get('/signin', 'SignIn::index');
$routes->get('/(?i)home','Home::index');
$routes->get('/(?i)clients', 'Clients::index',['filter' => 'pagesfilter']);
$routes->get('/(?i)demands', 'Demands::index',['filter'=> 'demandfilter']);
$routes->get('/(?i)candidates', 'Candidates::index');
$routes->match(['get','post'],'Demands/DropdownAction','Demands::DropdownAction');
$routes->match(['get','post'],'Candidates/CheckExisting','Candidates::CheckExisting');
$routes->get('/(?i)candidates_archive','CandidatesArchive::index');
$routes->get('/(?i)demandsview','DemandsView::index');
$routes->get('/(?i)password_reset','PasswordReset::index');
$routes->get('/(?i)candidatesview','CandidatesView::index');
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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}