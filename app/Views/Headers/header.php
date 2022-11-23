<!-- Vertical navbar -->
<nav class="navbar sticky-top navbar-expand-sm navbar-expand-light bg-light" id="topBar">
  <div class="container-fluid">
    <ul class="navbar-nav row">
        <li class="nav-item col" style="padding-top:10px">
            <button id="sidebarCollapse" type="button" class="btn btn-light bg-white rounded-pill shadow-sm px-4 mb-4"><i class="fa fa-bars mr-2"></i><small class="text-uppercase font-weight-bold"></small></button>
        </li>
        <li class="nav-brand col">
            <a href="/home"><img src="<?php echo base_url('images/ibhaan-logo.png')?>"></a>
        </li>
    </ul>
  </div>
</nav>

<?php if(session()->get('user_id') != NULL):?>
<div class="vertical-nav bg-white" id="sidebar">
    <div class="py-4 px-3 mb-4 bg-light">
        <div class="media d-flex align-items-center">
            <div class="rounded-circle border d-flex justify-content-center align-items-center text-muted"
                style="width:100px;height:100px"
            alt="Avatar">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                </svg>
            </div>
            <div class="media-body">
            <h4 class="m-2"><?php echo session()->get('name')?></h4>
            <?php $level = session()->get('level')?>
            <?php if($level == "1"):?>
                <p class="font-weight-normal text-muted m-2 text-uppercase">administrator</p>
                </div>
            <?php elseif($level == "2"):?>
                <p class="font-weight-normal text-muted m-2 text-uppercase">Co-Ordinator</p>
                </div>
            <?php else:?>
                <p class="font-weight-normal text-muted m-2 text-uppercase">Recruiter</p>
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
        <a href="/demands" class="nav-link text-dark">
            <svg class="mr-3 text-primary" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="16" viewBox="0 0 122.88 80.26"><defs><style>.cls-1{fill-rule:evenodd;}</style></defs><title>hiring</title><path class="cls-1" d="M93.28,30.93c2.84,6,14.31,6.32,16.87-.42,7.78,5,12.78,4.05,12.73,15.08H93.4a32.33,32.33,0,0,1-4.48,10.62l14,15.24-9.65,8.81L79.77,65.43A32.32,32.32,0,0,1,30.28,45.29H0c.87-11.23,3-9.86,12.57-15.85,2.64,6.87,15.34,7.32,18.13,0l.15.09A32.51,32.51,0,0,1,39,15.68,32.31,32.31,0,0,1,93.28,30.93ZM51.42,37.1a6.78,6.78,0,0,1-3.18-2c5-1.88,2.27-10.19,3.73-14.4,1.89-5.47,8.88-7.34,12.78-4.14,3-.32,6.14,1.25,6.8,6,.55,3.95-1.4,10.73,3.15,12.91a7.47,7.47,0,0,1-3.48,1.68,36,36,0,0,1-5.49.61v1.65l1.92,3.06-6.18,4.83L55.3,42.47l1.37-2.93V37.76a23.15,23.15,0,0,1-5.25-.66Zm19.75,7.95a15,15,0,0,0-2.86-4.44C79.42,44.91,78.44,42,80.74,51c.21.82.45,1.74.72,2.77-9,11.55-30.44,11.55-40,0,.28-1,.52-2,.73-2.82,2.26-8.88,1.24-6,12.49-10.32a14.78,14.78,0,0,0-2.86,4.44L54.72,45l6.59,5.27L68.23,45l2.94.07Zm11-26.81a28.67,28.67,0,0,0-40.57,0,29,29,0,0,0,0,40.58A28.69,28.69,0,1,0,82.16,18.24ZM15.55,28.9A4.13,4.13,0,0,1,16,27.42,6,6,0,0,1,13.6,23h-.13a1.75,1.75,0,0,1-.86-.22,2.39,2.39,0,0,1-.94-1.15c-.44-1-.78-3.64.32-4.39l-.21-.13,0-.3c0-.53-.06-1.17-.07-1.84,0-2.48-.09-5.48-2.08-6.08l-.85-.26L9.32,8a32.83,32.83,0,0,1,5-5.06A12.65,12.65,0,0,1,20.08.09,7,7,0,0,1,25.7,1.66a10.21,10.21,0,0,1,1.5,1.51A6.37,6.37,0,0,1,31.7,5.8a9.05,9.05,0,0,1,1.46,3,10.23,10.23,0,0,1,.4,3.35,8.07,8.07,0,0,1-2.34,5.43,1.65,1.65,0,0,1,.73.19,1.81,1.81,0,0,1,.64,2.22c-.22.67-.49,1.46-.75,2.12-.31.89-.77,1.05-1.66,1-.05,2.19-1.06,3.28-2.43,4.57l.31,1.08c-.86,4.19-10,4.64-12.51.23ZM95.91,30a3.85,3.85,0,0,1,.4-1.39,5.67,5.67,0,0,1-2.23-4.14H94a1.63,1.63,0,0,1-.8-.22,2.17,2.17,0,0,1-.89-1.08c-.41-.94-.73-3.41.3-4.12l-.2-.13,0-.27c0-.5,0-1.11-.06-1.74,0-2.33-.08-5.15-2-5.71l-.8-.25.53-.65a29.85,29.85,0,0,1,4.69-4.76,11.9,11.9,0,0,1,5.43-2.64,6.55,6.55,0,0,1,5.28,1.48,10,10,0,0,1,1.41,1.42,6,6,0,0,1,4.23,2.47,8.72,8.72,0,0,1,1.38,2.78,9.83,9.83,0,0,1,.37,3.15,7.62,7.62,0,0,1-2.2,5.1,1.53,1.53,0,0,1,.68.18,1.69,1.69,0,0,1,.61,2.08c-.21.64-.47,1.37-.71,2-.29.84-.72,1-1.56.91,0,2.06-1,3.08-2.28,4.29l.29,1c-.82,3.94-9.43,4.37-11.76.22Z"/></svg>
                Demands
        </a>
    </li>
    <?php if($level == 1):?>
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
                Password Reset
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

    <p class="text-gray font-weight-bold text-uppercase px-3 small py-4 mb-0">Reports</p>

    <ul class="nav flex-column bg-white mb-0">
    <li class="nav-item">
        <a href="#" class="nav-link text-dark">
            <i class="bi bi-bar-chart-fill mr-3 text-primary"></i>
                bar charts
            </a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link text-dark">
            <i class="bi bi-pie-chart-fill mr-3 text-primary"></i>
                pie charts
            </a>
    </li>
    </ul>
</div>
<?php endif; ?>
<!-- End vertical navbar -->