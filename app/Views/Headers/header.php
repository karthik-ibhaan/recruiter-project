<!-- Vertical navbar -->
<nav class="navbar sticky-top navbar-expand-sm navbar-expand-light bg-light" id="topBar">
  <div class="container-fluid">
    <ul class="navbar-nav row">
        <li class="nav-item col" style="padding-top:10px">
            <button id="sidebarCollapse" type="button" class="btn btn-light bg-white rounded-pill shadow-sm px-4 mb-4"><i class="fa fa-bars mr-2"></i><small class="text-uppercase font-weight-bold"></small></button>
            <a href="/home"><img src="<?php echo base_url('images/ibhaan-logo.png')?>"></a>
        </li>
    </ul>
  </div>
</nav>

<?php if(session()->get('user_id') != NULL):?>
<div class="vertical-nav bg-white" id="sidebar">
    <div class="py-4 px-3 mb-4 bg-light">
        <div class="media d-flex align-items-center">
            <div class="media-body">
            <h4 class="m-2"><?php echo session()->get('name')?></h4>
            <?php $level = session()->get('level')?>
            <?php if($level == "1"):?>
                <p class="font-weight-normal text-muted m-2 text-uppercase">administrator</p>
                </div>
            <?php elseif($level == "2"):?>
                <p class="font-weight-normal text-muted m-2 text-uppercase">Co-Ordinator</p>
                </div>
            <?php elseif($level == "3"):?>
                <p class="font-weight-normal text-muted m-2 text-uppercase">Recruiter</p>
                </div>
            <?php elseif($level == "4"):?>
                <p class="font-weight-normal text-muted m-2 text-uppercase">Interview Consultant</p>
                </div>
            <?php endif;?>
        </div>
    </div>

    <p class="text-gray font-weight-bold text-uppercase px-3 small pb-4 mb-0">Dashboard</p>

    <ul class="nav flex-column bg-white mb-0">
    <li class="nav-item">
        <a href="/home" class="nav-link text-dark">
        <i class="bi bi-house-fill mr-3 text-primary"></i>
                Home
            </a>
    </li>
    <li class="nav-item">
        <a href="/candidates" class="nav-link text-dark">
            <i class="bi bi-person-badge-fill text-primary mr-3"></i>
            Candidates
        </a>
    </li>
    <li class="nav-item">
    </li>
    <li class="accordion accordion-flush" id="accordionPanelsStayOpenExample">
        <div class="accordion-item">
            <button class="accordion-header accordion-button text-primary nav-item" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne" style="text-align: left;">
                Demands Data
            </button>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                <div class="accordion-body">
                    <a href="/demands" class="nav-link text-dark">
                        <svg class="mr-3 text-primary" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="16" viewBox="0 0 122.88 80.26"><defs><style>.cls-1{fill-rule:evenodd;}</style></defs><title>hiring</title><path class="cls-1" d="M93.28,30.93c2.84,6,14.31,6.32,16.87-.42,7.78,5,12.78,4.05,12.73,15.08H93.4a32.33,32.33,0,0,1-4.48,10.62l14,15.24-9.65,8.81L79.77,65.43A32.32,32.32,0,0,1,30.28,45.29H0c.87-11.23,3-9.86,12.57-15.85,2.64,6.87,15.34,7.32,18.13,0l.15.09A32.51,32.51,0,0,1,39,15.68,32.31,32.31,0,0,1,93.28,30.93ZM51.42,37.1a6.78,6.78,0,0,1-3.18-2c5-1.88,2.27-10.19,3.73-14.4,1.89-5.47,8.88-7.34,12.78-4.14,3-.32,6.14,1.25,6.8,6,.55,3.95-1.4,10.73,3.15,12.91a7.47,7.47,0,0,1-3.48,1.68,36,36,0,0,1-5.49.61v1.65l1.92,3.06-6.18,4.83L55.3,42.47l1.37-2.93V37.76a23.15,23.15,0,0,1-5.25-.66Zm19.75,7.95a15,15,0,0,0-2.86-4.44C79.42,44.91,78.44,42,80.74,51c.21.82.45,1.74.72,2.77-9,11.55-30.44,11.55-40,0,.28-1,.52-2,.73-2.82,2.26-8.88,1.24-6,12.49-10.32a14.78,14.78,0,0,0-2.86,4.44L54.72,45l6.59,5.27L68.23,45l2.94.07Zm11-26.81a28.67,28.67,0,0,0-40.57,0,29,29,0,0,0,0,40.58A28.69,28.69,0,1,0,82.16,18.24ZM15.55,28.9A4.13,4.13,0,0,1,16,27.42,6,6,0,0,1,13.6,23h-.13a1.75,1.75,0,0,1-.86-.22,2.39,2.39,0,0,1-.94-1.15c-.44-1-.78-3.64.32-4.39l-.21-.13,0-.3c0-.53-.06-1.17-.07-1.84,0-2.48-.09-5.48-2.08-6.08l-.85-.26L9.32,8a32.83,32.83,0,0,1,5-5.06A12.65,12.65,0,0,1,20.08.09,7,7,0,0,1,25.7,1.66a10.21,10.21,0,0,1,1.5,1.51A6.37,6.37,0,0,1,31.7,5.8a9.05,9.05,0,0,1,1.46,3,10.23,10.23,0,0,1,.4,3.35,8.07,8.07,0,0,1-2.34,5.43,1.65,1.65,0,0,1,.73.19,1.81,1.81,0,0,1,.64,2.22c-.22.67-.49,1.46-.75,2.12-.31.89-.77,1.05-1.66,1-.05,2.19-1.06,3.28-2.43,4.57l.31,1.08c-.86,4.19-10,4.64-12.51.23ZM95.91,30a3.85,3.85,0,0,1,.4-1.39,5.67,5.67,0,0,1-2.23-4.14H94a1.63,1.63,0,0,1-.8-.22,2.17,2.17,0,0,1-.89-1.08c-.41-.94-.73-3.41.3-4.12l-.2-.13,0-.27c0-.5,0-1.11-.06-1.74,0-2.33-.08-5.15-2-5.71l-.8-.25.53-.65a29.85,29.85,0,0,1,4.69-4.76,11.9,11.9,0,0,1,5.43-2.64,6.55,6.55,0,0,1,5.28,1.48,10,10,0,0,1,1.41,1.42,6,6,0,0,1,4.23,2.47,8.72,8.72,0,0,1,1.38,2.78,9.83,9.83,0,0,1,.37,3.15,7.62,7.62,0,0,1-2.2,5.1,1.53,1.53,0,0,1,.68.18,1.69,1.69,0,0,1,.61,2.08c-.21.64-.47,1.37-.71,2-.29.84-.72,1-1.56.91,0,2.06-1,3.08-2.28,4.29l.29,1c-.82,3.94-9.43,4.37-11.76.22Z"/></svg>
                            Demands
                    </a>
                    <?php if($level>=1 && $level<3):?>
                        <a href="/coordinatordemands" class="nav-link text-dark">
                            <svg class="mr-3 text-primary" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="16" viewBox="0 0 122.88 80.26"><defs><style>.cls-1{fill-rule:evenodd;}</style></defs><title>hiring</title><path class="cls-1" d="M93.28,30.93c2.84,6,14.31,6.32,16.87-.42,7.78,5,12.78,4.05,12.73,15.08H93.4a32.33,32.33,0,0,1-4.48,10.62l14,15.24-9.65,8.81L79.77,65.43A32.32,32.32,0,0,1,30.28,45.29H0c.87-11.23,3-9.86,12.57-15.85,2.64,6.87,15.34,7.32,18.13,0l.15.09A32.51,32.51,0,0,1,39,15.68,32.31,32.31,0,0,1,93.28,30.93ZM51.42,37.1a6.78,6.78,0,0,1-3.18-2c5-1.88,2.27-10.19,3.73-14.4,1.89-5.47,8.88-7.34,12.78-4.14,3-.32,6.14,1.25,6.8,6,.55,3.95-1.4,10.73,3.15,12.91a7.47,7.47,0,0,1-3.48,1.68,36,36,0,0,1-5.49.61v1.65l1.92,3.06-6.18,4.83L55.3,42.47l1.37-2.93V37.76a23.15,23.15,0,0,1-5.25-.66Zm19.75,7.95a15,15,0,0,0-2.86-4.44C79.42,44.91,78.44,42,80.74,51c.21.82.45,1.74.72,2.77-9,11.55-30.44,11.55-40,0,.28-1,.52-2,.73-2.82,2.26-8.88,1.24-6,12.49-10.32a14.78,14.78,0,0,0-2.86,4.44L54.72,45l6.59,5.27L68.23,45l2.94.07Zm11-26.81a28.67,28.67,0,0,0-40.57,0,29,29,0,0,0,0,40.58A28.69,28.69,0,1,0,82.16,18.24ZM15.55,28.9A4.13,4.13,0,0,1,16,27.42,6,6,0,0,1,13.6,23h-.13a1.75,1.75,0,0,1-.86-.22,2.39,2.39,0,0,1-.94-1.15c-.44-1-.78-3.64.32-4.39l-.21-.13,0-.3c0-.53-.06-1.17-.07-1.84,0-2.48-.09-5.48-2.08-6.08l-.85-.26L9.32,8a32.83,32.83,0,0,1,5-5.06A12.65,12.65,0,0,1,20.08.09,7,7,0,0,1,25.7,1.66a10.21,10.21,0,0,1,1.5,1.51A6.37,6.37,0,0,1,31.7,5.8a9.05,9.05,0,0,1,1.46,3,10.23,10.23,0,0,1,.4,3.35,8.07,8.07,0,0,1-2.34,5.43,1.65,1.65,0,0,1,.73.19,1.81,1.81,0,0,1,.64,2.22c-.22.67-.49,1.46-.75,2.12-.31.89-.77,1.05-1.66,1-.05,2.19-1.06,3.28-2.43,4.57l.31,1.08c-.86,4.19-10,4.64-12.51.23ZM95.91,30a3.85,3.85,0,0,1,.4-1.39,5.67,5.67,0,0,1-2.23-4.14H94a1.63,1.63,0,0,1-.8-.22,2.17,2.17,0,0,1-.89-1.08c-.41-.94-.73-3.41.3-4.12l-.2-.13,0-.27c0-.5,0-1.11-.06-1.74,0-2.33-.08-5.15-2-5.71l-.8-.25.53-.65a29.85,29.85,0,0,1,4.69-4.76,11.9,11.9,0,0,1,5.43-2.64,6.55,6.55,0,0,1,5.28,1.48,10,10,0,0,1,1.41,1.42,6,6,0,0,1,4.23,2.47,8.72,8.72,0,0,1,1.38,2.78,9.83,9.83,0,0,1,.37,3.15,7.62,7.62,0,0,1-2.2,5.1,1.53,1.53,0,0,1,.68.18,1.69,1.69,0,0,1,.61,2.08c-.21.64-.47,1.37-.71,2-.29.84-.72,1-1.56.91,0,2.06-1,3.08-2.28,4.29l.29,1c-.82,3.94-9.43,4.37-11.76.22Z"/></svg>
                            Coordinator Demands
                        </a>
                    <?php endif;?>
                    <a href="/assigneddemands" class="nav-link text-dark">
                        <svg class="mr-3 text-primary" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="16" viewBox="0 0 122.88 80.26"><defs><style>.cls-1{fill-rule:evenodd;}</style></defs><title>hiring</title><path class="cls-1" d="M93.28,30.93c2.84,6,14.31,6.32,16.87-.42,7.78,5,12.78,4.05,12.73,15.08H93.4a32.33,32.33,0,0,1-4.48,10.62l14,15.24-9.65,8.81L79.77,65.43A32.32,32.32,0,0,1,30.28,45.29H0c.87-11.23,3-9.86,12.57-15.85,2.64,6.87,15.34,7.32,18.13,0l.15.09A32.51,32.51,0,0,1,39,15.68,32.31,32.31,0,0,1,93.28,30.93ZM51.42,37.1a6.78,6.78,0,0,1-3.18-2c5-1.88,2.27-10.19,3.73-14.4,1.89-5.47,8.88-7.34,12.78-4.14,3-.32,6.14,1.25,6.8,6,.55,3.95-1.4,10.73,3.15,12.91a7.47,7.47,0,0,1-3.48,1.68,36,36,0,0,1-5.49.61v1.65l1.92,3.06-6.18,4.83L55.3,42.47l1.37-2.93V37.76a23.15,23.15,0,0,1-5.25-.66Zm19.75,7.95a15,15,0,0,0-2.86-4.44C79.42,44.91,78.44,42,80.74,51c.21.82.45,1.74.72,2.77-9,11.55-30.44,11.55-40,0,.28-1,.52-2,.73-2.82,2.26-8.88,1.24-6,12.49-10.32a14.78,14.78,0,0,0-2.86,4.44L54.72,45l6.59,5.27L68.23,45l2.94.07Zm11-26.81a28.67,28.67,0,0,0-40.57,0,29,29,0,0,0,0,40.58A28.69,28.69,0,1,0,82.16,18.24ZM15.55,28.9A4.13,4.13,0,0,1,16,27.42,6,6,0,0,1,13.6,23h-.13a1.75,1.75,0,0,1-.86-.22,2.39,2.39,0,0,1-.94-1.15c-.44-1-.78-3.64.32-4.39l-.21-.13,0-.3c0-.53-.06-1.17-.07-1.84,0-2.48-.09-5.48-2.08-6.08l-.85-.26L9.32,8a32.83,32.83,0,0,1,5-5.06A12.65,12.65,0,0,1,20.08.09,7,7,0,0,1,25.7,1.66a10.21,10.21,0,0,1,1.5,1.51A6.37,6.37,0,0,1,31.7,5.8a9.05,9.05,0,0,1,1.46,3,10.23,10.23,0,0,1,.4,3.35,8.07,8.07,0,0,1-2.34,5.43,1.65,1.65,0,0,1,.73.19,1.81,1.81,0,0,1,.64,2.22c-.22.67-.49,1.46-.75,2.12-.31.89-.77,1.05-1.66,1-.05,2.19-1.06,3.28-2.43,4.57l.31,1.08c-.86,4.19-10,4.64-12.51.23ZM95.91,30a3.85,3.85,0,0,1,.4-1.39,5.67,5.67,0,0,1-2.23-4.14H94a1.63,1.63,0,0,1-.8-.22,2.17,2.17,0,0,1-.89-1.08c-.41-.94-.73-3.41.3-4.12l-.2-.13,0-.27c0-.5,0-1.11-.06-1.74,0-2.33-.08-5.15-2-5.71l-.8-.25.53-.65a29.85,29.85,0,0,1,4.69-4.76,11.9,11.9,0,0,1,5.43-2.64,6.55,6.55,0,0,1,5.28,1.48,10,10,0,0,1,1.41,1.42,6,6,0,0,1,4.23,2.47,8.72,8.72,0,0,1,1.38,2.78,9.83,9.83,0,0,1,.37,3.15,7.62,7.62,0,0,1-2.2,5.1,1.53,1.53,0,0,1,.68.18,1.69,1.69,0,0,1,.61,2.08c-.21.64-.47,1.37-.71,2-.29.84-.72,1-1.56.91,0,2.06-1,3.08-2.28,4.29l.29,1c-.82,3.94-9.43,4.37-11.76.22Z"/></svg>
                        Assigned Demands
                    </a>
                </div>
            </div>
        </div>
    </li>
    <?php if($level == 1 || session()->get('user_id') == 10):?>
        <li class="nav-item">
            <a href="/clients" class="nav-link text-dark">
            <i class="bi bi-building text-primary mr-3"></i>
                    Clients
                </a>
        </li>
        <li class="nav-item">
            <a href="/users" class="nav-link text-dark">
            <i class="bi bi-people-fill mr-3 text-primary"></i>
                    Users
                </a>
        </li>
    <?php endif;?>
    <li class="nav-item">
        <a href="/password_reset" class="nav-link text-dark">
            <i class="bi bi-people-fill mr-3 text-primary">
                Change Password
            </i>
        </a>
    </li>
    <li class="nav-item">
        <a href="Home/Logout" class="nav-link text-dark">
            <i class="bi bi-box-arrow-right mr-3 text-primary"></i>
            Logout
        </a>
    </li>
    </ul>


    <?php if($level == "1"):?>
        <p class="text-gray font-weight-bold text-uppercase px-3 small py-4 mb-0">IG Interview</p>

        <ul class="nav flex-column bg-white mb-0">
            <li class="nav-item">
                <a href="/interviewapproval" class="nav-link text-dark">
                    <i class="bi bi-file-earmark-check-fill mr-3 text-primary"></i>
                        Interview Approval
                </a>
            </li>
            <li class="nav-item">
                <a href="/iginterviews" class="nav-link text-dark">
                    <svg fill="currentColor" class="text-primary" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                        width="16" height="16" viewBox="0 0 943.118 943.118"
                        xml:space="preserve">
                    <g>
                        <g>
                            <g>
                                <path d="M54.182,670.915v189.128c0,11.047,8.955,20,20,20h362.347c-3.137-6.688-4.899-14.143-4.899-22.006V670.915H54.182z"/>
                                <path d="M30,639.904h24.182h377.446V622.67v-24.418c0-0.229,0.007-0.456,0.009-0.685c0.107-15.218,3.8-29.6,10.277-42.337
                                    c2.796-5.496,6.107-10.688,9.873-15.506c4.478-5.729,9.597-10.934,15.245-15.507c16.361-13.248,37.182-21.197,59.827-21.197
                                    h22.555v-43.313c0-32.846-26.627-59.473-59.473-59.473h-53.809c-10.504,0-19.628,7.229-22.029,17.455l-25.013,106.529
                                    l-3.642,15.507l-2.578,10.977c-0.36,1.538-0.785,3.049-1.271,4.528h-16.584c-0.183-5.188-0.711-10.367-1.577-15.506
                                    c-0.148-0.892-0.306-1.779-0.476-2.666l-3.326-12.841l-19.571-75.542l15.62-34.473c2.965-6.545-1.82-13.968-9.006-13.968h-33.525
                                    c-7.186,0-11.972,7.423-9.006,13.968l15.62,34.473l-20.313,75.542l-3.086,11.478c-0.268,1.339-0.506,2.683-0.728,4.029
                                    c-0.848,5.14-1.36,10.317-1.527,15.506h-15.88c-0.484-1.48-0.909-2.99-1.271-4.528l-2.578-10.977l-3.641-15.508l-25.013-106.525
                                    c-2.401-10.227-11.524-17.455-22.029-17.455h-53.808c-32.846,0-59.473,26.627-59.473,59.473v64.513v15.506v15.506H30
                                    c-16.568,0-30,13.431-30,30v24.674C0,626.474,13.432,639.904,30,639.904z"/>
                                <path d="M329.919,368.094c73.717,0,133.477-59.76,133.477-133.477V92.744c0-18.391-16.561-32.347-34.686-29.233
                                    c-39.276,6.747-128.839,24.62-184.565,35.864c-27.752,5.599-47.704,29.986-47.704,58.297v76.946
                                    C196.442,308.335,256.202,368.094,329.919,368.094z"/>
                                <path d="M526.859,533.021c-10.345,0-20.121,2.418-28.812,6.703c-7.723,3.809-14.576,9.102-20.201,15.506
                                    c-9.95,11.325-16.036,26.118-16.204,42.337c-0.002,0.229-0.017,0.455-0.017,0.685v24.418v17.234v15.505v15.506v187.122
                                    c0,12.154,9.853,22.006,22.005,22.006h334.086h103.396c12.153,0,22.006-9.852,22.006-22.006V598.252
                                    c0-31.565-22.422-57.893-52.209-63.928c-4.207-0.852-8.562-1.303-13.021-1.303H549.414H526.859L526.859,533.021z"/>
                                <path d="M702.375,497.769c80.854,0,146.4-65.546,146.4-146.4v-84.396c0-31.052-21.886-57.8-52.322-63.941
                                    c-61.123-12.332-159.355-31.935-202.434-39.336c-1.879-0.323-3.743-0.478-5.577-0.478c-17.574,0-32.468,14.276-32.468,32.542
                                    v155.609C555.975,432.223,621.52,497.769,702.375,497.769z"/>
                            </g>
                        </g>
                    </g>
                    </svg>
                    Ibhaan Interview
                </a>
            </li>
        </ul>
    <?php endif;?>
    <p class="text-gray font-weight-bold text-uppercase px-3 small py-4 mb-0">Reports</p>

    <ul class="nav flex-column bg-white mb-0">

    <?php if($level == 1 || $level == 2):?>
        <li class="nav-item">
            <a href="/selectiondetails" class="nav-link text-dark">
                <i class="bi bi-file-earmark-check-fill mr-3 text-primary"></i>
                    Selection Details
            </a>
        </li>
        <li class="nav-item">
            <a href="/profilesourcing" class="nav-link text-dark">
                <i class="bi bi-person-check-fill mr-3 text-primary"></i>
                    Sourced Profiles
            </a>
        </li>
    <?php endif;?>
    <li class="nav-item">
        <a href="/interviewlist" class="nav-link text-dark">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-vcard-fill text-primary mr-3" viewBox="0 0 16 16">
          <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5ZM9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8Zm1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5Zm-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96c.026-.163.04-.33.04-.5ZM7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0Z"/>
        </svg>
            Scheduled Interviews
        </a>
    </li>

    <li class="nav-item">
        <a href="/overallstatus" class="nav-link text-dark">
            <i class="bi bi-collection-fill mr-3 text-primary"></i>
            Overall Status
        </a>
    </li>
    <li class="nav-item">
        <a href="/attendanceview" class="nav-link text-dark">
            <i class="bi bi-calendar3 mr-3 text-primary"></i>
            Attendance Dashboard
        </a>
    </li>
    <?php if($level==1):?>
    <li class="nav-item">
        <a href="/attendance" class="nav-link text-dark">
            <i class="bi bi-calendar3 mr-3 text-primary"></i>
            Admin Attendance Dashboard
        </a>
        <a href="/runratereport" class="nav-link text-dark">
            <i class="bi bi-percent mr-3 text-primary"></i>
            Run Rate Statistics
        </a>
    </li>
    <?php endif;?>
    <li class="nav-item">
        <div class="separator"></div>
    </li>
    </ul>
</div>
<?php endif; ?>
<!-- End vertical navbar -->