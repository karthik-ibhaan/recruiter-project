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
$routes->get('/(?i)signin', 'SignIn::index');
$routes->get('/signin', 'SignIn::index');
$routes->get('/(?i)home','Home::index');
$routes->get('/(?i)clients', 'Clients::index');
$routes->get('/(?i)demands', 'Demands::index',['filter'=> 'demandfilter']);
$routes->get('/(?i)candidates', 'Candidates::index');
$routes->match(['get','post'],'Demands/DropdownAction','Demands::DropdownAction');
$routes->match(['get','post'],'Candidates/CheckExisting','Candidates::CheckExisting');
$routes->get('/(?i)candidates_archive','CandidatesArchive::index');
$routes->get('/(?i)demandsview','DemandsView::index');
$routes->get('/(?i)password_reset','PasswordReset::index');
$routes->get('/(?i)candidatesview','CandidatesView::index');
$routes->get('/(?i)profilesourcing','ProfileSourcing::index');
$routes->get('/(?i)overallstatus','OverallStatus::index');
$routes->get('/(?i)interviewlist','InterviewList::index');
$routes->get('/(?i)selectiondetails','SelectionDetails::index');
$routes->get('/(?i)attendance','Attendance::index');
$routes->get('/(?i)attendanceview','AttendanceView::index');
$routes->get('/(?i)leaveapplication','LeaveApplication::index');
$routes->get('/(?i)adminapproval', 'AdminApproval::index');
$routes->get('/(?i)runratereport', 'RunRateReport::index');
$routes->get('/(?i)coordinatordemands', 'CoordinatorDemands::index');
$routes->get('/(?i)assigneddemands', 'AssignedDemands::index');
$routes->get('/(?i)ibhaaninterview', 'IbhaanInterview::index');
$routes->get('/(?i)interviewapproval', 'InterviewApproval::index');
$routes->get('/(?i)iginterviews', 'IGInterviews::index');
// $routes->get('/(?i)sendmail','SendMail::index');
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