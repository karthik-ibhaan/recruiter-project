<!doctype html>
<html lang="en">
    <?php include('Headers/head.php')?>
    <body>
        <?php include('Headers/header.php')?>
        <div class="page-content p-5" id="content">

            <?php if(session()->getFlashdata('error') !== NULL):?>
                <div class="alert alert-warning">
                    <?php echo session()->getFlashdata('error') ?>
                </div>
            <?php endif;?>

            <?php if(session()->getFlashdata('count') !== NULL):?>
                <div class="alert alert-success">
                    Successfully inserted <?php echo session()->get('count') ?> records.
                </div>
            <?php endif;?>

            <?php if(session()->getFlashdata('success') !== NULL):?>
                <div class="alert alert-success">
                    <?php echo session()->getFlashdata('success')?>
                </div>
            <?php endif;?>

            <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>

            <div class="text-center">
                <table class="table table-striped table-bordered" style="width:100%" id="users" name="users">
                    <thead>
                        <tr>
                            <?php foreach($fieldNames as $keys=>$values):?>
                            <?php $display = str_replace('_',' ', $values);?>
                            <th scope="col" class="text-uppercase text-center"><?php echo $display?></th>
                            <?php endforeach;?>
                            <th scope="col" class="text-uppercase text-center">EDITS</th>
                            <th scope="col" class="text-uppercase text-center">RESET PASSWORD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $keys=>$data):?>
                            <tr>
                                <?php foreach($fieldNames as $keys=>$value):?>
                                    <?php if(strtolower($value) == "level"):?>
                                    <?php if($data[$value] == "-1"):?>
                                        <td><?php echo "NOT AN EMPLOYEE"?></td>
                                    <?php else:?>
                                    <?php foreach($levels as $keys=>$value2):?>
                                        <?php if($data[$value] == $keys):?>
                                            <td><?php echo $value2?></td>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                    <?php endif;?>
                                    <?php else:?>
                                        <td><?php echo $data[$value]?></td>
                                    <?php endif;?>
                                <?php endforeach;?>
                                <td>
                                    <div class="col">
                                        <button type="button" class="btn btn-dark btn-sm editButton" data-bs-toggle="modal" id="editBtn" data-id="<?php echo $data['user_id']?>" data-name="<?php echo $data['full_name']?>" data-email=<?php echo $data['email']?> data-level="<?php echo $data['level']?>" data-bs-target="#editModal"><i class="bi bi-pen"></i></button>
                                    </div>
                                </td>
                                <td>
                                    <div class="col">
                                        <button type="button" class="btn btn-dark btn-sm resetButton" data-bs-toggle="modal" id="resetBtn" data-id="<?php echo $data['user_id']?>" data-name="<?php echo $data['full_name']?>" data-email=<?php echo $data['email']?> data-bs-target="#resetModal"><i class="bi bi-arrow-clockwise"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>

            <?=form_open('Users/ResetPassword')?>
            <div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Reset Password</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label data-bs-toggle="tooltip" data-bs-placement="left" title="User ID of the user.">User ID</label>
                                    <input type="hidden" value="" name="reset_user_id" id="reset_user_id">
                                    <input type="text" class="form-control" name="reset_id" id="reset_id" placeholder="User ID" required disabled>
                                </div>
                                <div class="form-group">
                                    <label data-bs-toggle="tooltip" data-bs-placement="left" title="Write the full name of the user.Ex: Alan G Watts">Full Name</label>
                                    <input type="text" class="form-control" name="reset_name" id="reset_name" placeholder="Full Name" required disabled>
                                </div>
                                <?= csrf_field() ?>
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" class="form-control" name="reset_email" id="reset_email"  placeholder="Email Address" required>
                                </div>
                            </div>
                            <h3 class="text-center text-uppercase">Do you want to reset the password for this user?</h3>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <?= form_open('Users/AddUser')?>
                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false"  aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add New User</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label data-bs-toggle="tooltip" data-bs-placement="left" title="Write the full name of the user.Ex: Alan G Watts" class="required">Full Name</label>
                                    <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Full Name" required>
                                </div>
                                <div class="form-group">
                                    <label class="required">Email Address</label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" required>
                                </div>
                                <div>
                                    <label class="required">Level</label>
                                    <select name="user_level" id="user_level" class="form-control" required>
                                        <option value="">-Level-</option>
                                        <option value="1">Administrator</option>
                                        <option value="2">Co-ordinator</option>
                                        <option value="3">Recruiter</option>
                                        <option value="4">Interview Consultant</option>
                                    </select>
                                </div>
                                <span class="interviewerData" style="display: none;">
                                    <br>
                                    <center><h4>INTERVIEWER CONSULTANT DATA</h4></center>
                                    <br>
                                    <span class="row">
                                        <span class="form-group col">
                                            <label class="required">Interviewer Phone Number</label>
                                            <input type="text" class="form-control" name="interviewerPhone" id="interviewerPhone" placeholder="9999999999">
                                        </span>
                                        <span class="form-group col">
                                            <label class="required">Domain</label>
                                            <select name="domain" id="domain" class="form-control"></select>
                                        </span>
                                    </span>
                                    <span class="row">
                                        <span class="form-group col">
                                            <label class="required">Industry</label>
                                            <select name="industry" id="industry" class="form-control"></select>
                                        </span>
                                        <span class="form-group col">
                                            <label class="required">Skills</label>
                                            <input type="text" name="skills" id="skills" class="form-control" placeholder="ROR, NodeJS">
                                        </span>
                                    </span>
                                </span>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="addBtn">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <?=form_open('Users/EditUser')?>
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="User ID of the user.">User ID</label>
                                        <input type="hidden" value="" name="edit_user_id" id="edit_user_id">
                                        <input type="text" class="form-control" name="edit_id" id="edit_id" placeholder="User ID" required disabled>
                                    </div>
                                    <div class="form-group">
                                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="Write the full name of the user.Ex: Alan G Watts">Full Name</label>
                                        <input type="text" class="form-control" name="edit_name" id="edit_name" placeholder="Full Name" required disabled>
                                    </div>
                                    <?= csrf_field() ?>

                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" class="form-control" name="edit_email" id="edit_email"  placeholder="Email Address" required>
                                    </div>
                                    <div class="form-group">

                                        <label>Level</label>
                                        <select name="edit_user_level" id="edit_user_level" class="form-control" required>
                                            <option value="">-Level-</option>
                                            <option value="1">Administrator</option>
                                            <option value="2">Co-ordinator</option>
                                            <option value="3">Recruiter</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>

            <?= form_open('Users/FileExport') ?>
                <?= csrf_field() ?>

                <center>
                <div class = "col-sm">
                    <p>Click below to export the data</p>
                    <input type="submit" class="btn btn-dark btn-sm" id="exportBtn" value="Export" />
                </div>
                </center>
            </form>
        </div>
        <?php include("Footers/footer.php")?>
    </body>
</html>
<style>
    .required:after 
    {
      content:" *";
      color: red;
    }
</style>
<script>
    $(document).ready(function(){
        
        var table = $('.table').DataTable({
        // "rowCallback": function( row, data, index ) {
        //     var allData = this.api().column(8).data().toArray();
        //     console.log(allData);
        //     console.log(row);

        //     if (allData.indexOf(data[8]) != allData.lastIndexOf(data[8])) {
        //         math_random = '#'+(0x1000000+Math.random()*0xffffff).toString(16).substr(1,6);
        //         $('td:eq(8)', row).css('background-color', 'red');
        //         $('td:eq(8)', row).css('color', 'white');
        //     }
        // },
        initComplete: function () {
            this.api().columns('.col')
            .every(function () {
                var column = this;
                var select = $('<select><option value="">-Select-</option></select>')
                    .appendTo($(column.footer()).empty())
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());

                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });

                column
                    .data()
                    .unique()
                    .sort()
                    .each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>');
                    });
            });
        },  
        scrollResize: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns:   {
            right: 1,
            left: 0
        },
        order: [[0,"desc"]],
        });    
    });

    $(document).on('change', '#user_level', function(){
        var level = $(this).val();
        if(level == "4")
        {
            industrySegment = ['Aero Engine','Aerostructure','Automotive','Avionics','Consumer Durables','Defence','Industrial Automation','Information Technology','Locomotive','Marine','Medical Devices','Renewable Energy','Survelience','Telecom'];
            domain = ['E&E Development','E&E Manufacturing', 'Information Technology','Mechanical'];
            $("#domain").append('<option value="">-Select-</option>');
            $("#industry").append('<option value="">-Select-</option>');
            for(var i=0;i<domain.length;i++)
            {
                $("#domain").append('<option value="'+domain[i]+'">'+domain[i]+'</option>');
            }
            for(var j=0;j<industrySegment.length;j++)
            {
                $("#industry").append('<option value="'+industrySegment[j]+'">'+industrySegment[j]+'</option>');
            }
            $(".interviewerData").show();
        }
        else
        {
            $("#domain").html('<option value="">-Select-</option>');
            $("#industry").html('<option value="">-Select-</option>');
            $(".interviewerData").hide();
        }
    });

    $(document).on("click", "#editBtn", function () {
        var userID = $(this).data('id');
        var fullName = $(this).data('name');
        var email = $(this).data('email');
        var level = $(this).data('level');
        console.log(level);
        $(".form-group #edit_id").val( userID );
        $(".form-group #edit_user_id").val( userID );
        $(".form-group #edit_name").val( fullName );
        $(".form-group #edit_email").val( email );
        $(".form-group #edit_user_level").val( level ).change();
    });

    $(document).on("click", "#resetBtn", function () {
        var userID = $(this).data('id');
        var fullName = $(this).data('name');
        var email = $(this).data('email');
        $("#resetModal .form-group #reset_id").val( userID );
        $("#resetModal .form-group #reset_user_id").val( userID );
        $("#resetModal .form-group #reset_name").val( fullName );
        $("#resetModal .form-group #reset_email").val( email );
    });

    $(document).on("click", ".deleteButton", function() {
        var userID = $(this).data('id');
        var fullName = $(this).data('name');
        $(".form-group #user_id").val( userID );
    });

    // $(document).on("click", "#exportBtn", function() {
    //     var xhr = new XMLHttpRequest();
    //     xhr.open('GET', '');
    //     xhr.send();
    //     xhr.onload = function() {
    //         res = xhr.responseText;
    //         if(res == "200")
    //         {
    //             window.location.replace('');
    //         }
    //     }
    // })

    // $(document).on("click", "#addBtn", function() {
    //     var xhr = new XMLHttpRequest();
        // xhr.open('GET', '');
    //     xhr.send();
    //     xhr.onload = function() {
    //         res = JSON.parse(xhr.responseText);
    //         console.log(res);
    //         if(res == "200")
    //         {
    //             $('#addModal').modal('hide');
    //             window.location.reload();
    //         }
    //     }
    // })
</script>
