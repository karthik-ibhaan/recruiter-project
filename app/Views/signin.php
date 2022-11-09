<!doctype html>
<html lang="en">
    <?php include('head.php')?>
    <body>
    <?php include('header.php')?>
    <div class="page-content p-5" id="content">
        <div class="container mt-5">
            <div class="row mx-auto col-md-6 align-items-center">
                <div class="col-">
                    <h2>Sign-In</h2>

                    <?php if(session()->getFlashdata('msg') !== NULL):?>
                    <div class="alert alert-warning">
                    <?php echo session()->getFlashdata('msg') ?>
                    </div>
                    <?php endif;?>

                    <form id="signin_form" action="<?php echo base_url(); ?>/SignIn/LoginAuth" name = "signin_form" method="post">
                    
                        <div class="form-group mb-3">
                            <input type="email" name="email" placeholder="Email" value="<?= set_value('email') ?>" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <input type="password" name="password" placeholder="Password" class="form-control" required>
                        </div>

                        <?= csrf_field() ?>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">Sign-In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>    <script src="https://kit.fontawesome.com/a1c2cc8f05.js" crossorigin="anonymous"></script>
    <script src="<?php echo base_url('js/main.js');?>"></script>
    </body>
</html>