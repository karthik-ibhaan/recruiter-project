<!-- Vertical navbar -->
<nav class="sticky-top bg-light" style="display: flex; padding-top: 10px;align-items: center; justify-content: left;" id="topBar">
    <ul style="list-style-type:none;text-align:center;" class="row">
        <li class="col">
            <img src="<?php echo base_url('images/ibhaan-logo.png')?>">
        </li>
        <?php if(session()->get('isLoggedIn') == FALSE):?>
            <li class="col" style="padding-top:24px;">
                <a href="/signin" class="nav-link">Sign-In</a>
            </li>
        <?php elseif(session()->get('isLoggedIn') == TRUE):?>
            <li class="col" style="padding-top:24px;">
                <a href="/ibhaaninterview/logout" class="nav-link">Logout</a>
            </li>
        <?php endif;?>
    </ul>
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
            <a href="/ibhaaninterview" class="nav-link text-dark">
                <i class="bi bi-house-fill mr-3 text-primary"></i>
                    Interviews
            </a>
        </li>
        <a href="/password_reset" class="nav-link text-dark">
            <i class="bi bi-people-fill mr-3 text-primary">
                Change Password
            </i>
        </a>
        <li class="nav-item">
            <a href="/ibhaaninterview/Logout" class="nav-link text-dark">
                <i class="bi bi-box-arrow-right mr-3 text-primary"></i>
                Logout
            </a>
        </li>
    </ul>
</div>
<?php endif; ?>

<!-- End vertical navbar -->