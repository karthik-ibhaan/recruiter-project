<!DOCTYPE html>
<html>
    <?php include('Headers/head.php')?>
    <body>
        <?php include('Headers/header.php')?>
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