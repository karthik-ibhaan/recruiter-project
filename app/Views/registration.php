<!doctype html>
<html lang="en">
    <?php include('head.php')?>
    <body>
        <?php include('header.php')?>

    <div class="page-content p-5" id="registrationContent">
        <div class="container mt-5">
            <h1>Registration Page</h1>
            <h3>Welcome! Please complete the registration below to continue.</h3>
        </div>
        <div class="container mt-5">
            <div class="row mx-auto col-md-6 align-items-center">
                <div class="col-">
                    <h2>Register User</h2>

                    <?php if(isset($validation)):?>
                    <div class="alert alert-warning">
                    <?= $validation->listErrors() ?>
                    </div>
                    <?php endif;?>

                    <form id="registration_form" action="<?php echo base_url(); ?>/Registration/RegistrationStore" name = "registration_form" method="post">
                    
                        <div class="form-group mb-3">
                            <input type="text" name="full_name" placeholder="Full Name" value="<?= set_value('full_name') ?>" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <input type="email" name="email" placeholder="Email" value="<?= set_value('email') ?>" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <input type="password" name="password" placeholder="Password" class="form-control" required>
                        </div>

                        <div class="input-group mb-3">
                            <select class="form-select" id="level" name="level" required>
                                <option selected>Level...</option>
                                <option value="1">Adminstrator</option>
                                <option value="2">Co-ordinator</option>
                                <option value="3">Recruiter</option>
                            </select>
                        </div>
                        <?= csrf_field() ?>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">Register</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </body>
</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>