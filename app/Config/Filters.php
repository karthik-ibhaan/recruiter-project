<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array
     */
    public $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'auth' => Auth::class,
        'pagesfilter' => UserPageFilter::class,
        'demandfilter' => DemandFilter::class,
        'cofilter' => Coordinator::class,
        'isLoggedIn' => LoggedIn::class,
        'interviewFilter' => InterviewConsultantFilter::class

    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array
     */
    public $globals = [
        'before' => [
            // 'honeypot',
            'csrf',
            // 'invalidchars',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['csrf', 'throttle']
     *
     * @var array
     */
    public $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array
     */
    public $filters = [
        'auth' => ['before' => ['/',
        'home/*', 'home',
        'users/*', 'users',
        'candidates/*', 'candidates',
        'clients/*', 'clients',
        'demands/*', 'demands',
        'demandsview/*', 'demandsview',
        'candidates_archive','candidates_archive/*',
        'password_reset','password_reset/*',
        'candidatesview','candidatesview/*',
        'profilesourcing','profilesourcing/*',
        'interviewlist','interviewlist/*',
        'selectiondetails','selectiondetails/*',
        'overallstatus','overallstatus/*',
        'attendance','attendance/*',
        'attendanceview','attendanceview/*',
        'leaveapplication','leaveapplication/*',
        'adminapproval','adminapproval/*',
        'runratereport','runratereport/*',
        'coordinatordemands','coordinatordemands/*',
        'assigneddemands', 'assigneddemands/*',
        'ibhaaninterview', 'ibhaaninterview/*',
        'interviewapproval', 'interviewapproval',
        'iginterviews', 'iginterviews/*']],
        'interviewFilter' => ['before' => ['/',
        'home/*', 'home',
        'users/*', 'users',
        'candidates/*', 'candidates',
        'clients/*', 'clients',
        'demands/*', 'demands',
        'demandsview/*', 'demandsview',
        'candidates_archive','candidates_archive/*',
        'password_reset','password_reset/*',
        'candidatesview','candidatesview/*',
        'profilesourcing','profilesourcing/*',
        'interviewlist','interviewlist/*',
        'selectiondetails','selectiondetails/*',
        'overallstatus','overallstatus/*',
        'attendance','attendance/*',
        'attendanceview','attendanceview/*',
        'leaveapplication','leaveapplication/*',
        'adminapproval','adminapproval/*',
        'runratereport','runratereport/*',
        'coordinatordemands','coordinatordemands/*',
        'assigneddemands', 'assigneddemands/*',
        'interviewapproval', 'interviewapproval',
        'iginterviews', 'iginterviews/*']],
        'pagesfilter' => ['before' => ['users','users/*','attendance','attendance/*','attendanceview','attendanceview/*','adminapproval','adminapproval/*','runratereport','runratereport/*','interviewapproval','interviewapproval']],
        'demandfilter' => ['before' => ['clients', 'clients/*','profilesourcing','profilesourcing/*','demands','demands/*','selectiondetails','selectiondetails/*', 'coordinatordemands','coordinatordemands/*']],
        'isLoggedIn' => ['before' => ['registration','registration/*',
        'signin','signin/*']]
    ];
}
?>