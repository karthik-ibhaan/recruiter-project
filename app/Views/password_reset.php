<!DOCTYPE html>
<html>
<head>
    <title>Ibhaan-Global Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <?php if(session()->get('isLoggedIn') == TRUE):?>
            <link rel="stylesheet" href="<?php echo base_url('css/style1.css');?>">
    <?php else:?>
            <link rel="stylesheet" href="<?php echo base_url('css/style.css');?>">
    <?php endif;?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.12.1/fc-4.1.0/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.1/b-2.3.3/b-html5-2.3.3/datatables.min.css"/>
    <link href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.bootstrap5.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css" integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
    <link rel="mask-icon" href="safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>
    <body>
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
                <a href="/home" class="nav-link text-dark">
                    <i class="bi bi-house-fill mr-3 text-primary"></i>
                        Home
                </a>
            </li>
            <li class="nav-item">
                <a href="/ibhaaninterview/logout" class="nav-link text-dark">
                    <i class="bi bi-box-arrow-right mr-3 text-primary"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <?php endif; ?>
        <!-- Page content holder -->
        <div class="page-content p-5" id="content">
            <div class="container mt-5">
                <?php if(session()->getFlashdata('msg') !== NULL):?>
                    <div class="alert alert-warning">
                    <?php echo session()->getFlashdata('msg') ?>
                    </div>
                <?php endif;?>
                <?php if(session()->getFlashdata('msg2') !== NULL):?>
                    <div class="alert alert-success">
                    <?php echo session()->getFlashdata('msg2') ?>
                    </div>
                <?php endif;?>
                
                <center><h2>CHANGE PASSWORD</h2></center>

                <?=form_open('PasswordReset/Resetter')?>
                    <div class="form-group row">
                        <label>Previous Password:</label>
                        <input type="password" name="password1" id="password1" placeholder="Password" class="form-control" required>
                    </div>

                    <div class="form-group row">
                        <label name="invalid" id="invalid"></label>
                    </div>
                    <br>

                    <div class="form-group row">
                        <label>New Password</label>
                        <input type="password" name="password2" id="password2" placeholder="Password" class="form-control" required>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label>Confirm Password</label>
                        <input type="password" name="password3" id="password3" placeholder="Password" class="form-control" required>
                    </div>

                    <br>
                    <?= csrf_field() ?>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-dark">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- End  content -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>    <script src="https://kit.fontawesome.com/a1c2cc8f05.js" crossorigin="anonymous"></script>
        <script src="<?php echo base_url('js/main.js');?>"></script>
    </body>
</html>

<style>
    a:hover{
        color:inherit;
    }
</style>

<script>

    var timeout = null;
    $("#password1").on('input', function() {
        var user_id = <?php echo session()->get('user_id')?>;
        var pass = $(this).val();
        var passVal = document.getElementById("password1");
        clearTimeout(timeout);
        var res;
        timeout = setTimeout(() => {
        var xhr = new XMLHttpRequest();
        var params = "user_id="+user_id+"&password="+pass;
        xhr.open('GET','<?php echo base_url('PasswordReset/PasswordCheck')?>'+"?"+params, true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            console.log(pass);
            if(res == "true")
            {
                passVal.setCustomValidity("");
                document.getElementById("invalid").textContent = "VALID";
                document.getElementById("invalid").style.color = "GREEN";
            }
            else if(res == "false")
            {
                passVal.setCustomValidity("Password is incorrect.");   
                passVal.reportValidity();                                                     
                document.getElementById("invalid").textContent = "INVALID";
                document.getElementById("invalid").style.color = "RED";
            }
        }
    },50);
    });
    $("#password2").on('input keyup keydown', function(){
        var pass1 = document.getElementById('password2');
        var pass2 = document.getElementById('password3');
        console.log(pass1.value,pass2.value);
        if(pass1.value != pass2.value && pass2.value != "")
        {
            console.log("error");
            pass2.setCustomValidity("Passwords do not match.");
            pass2.reportValidity();
        }
        else
        {
            pass2.setCustomValidity("");
        }
    })
    $("#password3").on('input keyup keydown', function(){
        var pass1 = document.getElementById('password2');
        var pass2 = document.getElementById('password3');
        console.log(pass1.value,pass2.value);
        if(pass1.value != pass2.value)
        {
            console.log("error");
            pass2.setCustomValidity("Passwords do not match.");
            pass2.reportValidity();
        }
        else
        {
            pass2.setCustomValidity("");
        }
    })
</script>