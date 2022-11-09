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
                <table class="table" style="width:100%" id="users" name="users">
                    <thead>
                        <tr>
                            <?php foreach($fieldNames as $keys=>$values):?>
                            <?php $display = str_replace('_',' ', $values);?>
                            <th scope="col" class="text-uppercase text-center"><?php echo $display?></th>
                            <?php endforeach;?>
                            <th scope="col" class="text-uppercase text-center">EDITS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $keys=>$data):?>
                            <tr>
                                <?php foreach($fieldNames as $keys=>$value):?>
                                    <?php if(strtolower($value) != "level"):?>
                                        <td><?php echo $data[$value]?></td>
                                    <?php else:?>
                                    <?php foreach($levels as $keys=>$value2):?>
                                            <?php if($data[$value] == $keys):?>
                                                <td><?php echo $value2?></td>
                                            <?php endif;?>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                <?php endforeach;?>
                                <td>
                                    <div class="col">
                                        <button type="button" class="btn btn-dark btn-sm editButton" data-bs-toggle="modal" id="editBtn" data-id="<?php echo $data['user_id']?>" data-name="<?php echo $data['full_name']?>" data-email=<?php echo $data['email']?> data-level="<?php echo $data['level']?>" data-bs-target="#editModal">EDIT</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>

            <?= form_open('Users/AddUser')?>
                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add New User</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label data-bs-toggle="tooltip" data-bs-placement="left" title="Write the full name of the user.Ex: Alan G Watts">Full Name</label>
                                    <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Full Name" required>
                                </div>
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" required>
                                </div>
                                <div>
                                    <label>Level</label>
                                    <select name="user_level" id="user_level" class="form-control" required>
                                        <option value="">-Level-</option>
                                        <option value="1">Administrator</option>
                                        <option value="2">Co-ordinator</option>
                                        <option value="3">Recruiter</option>
                                    </select>
                                </div>
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

<script>
    $(document).ready(function(){
        $.noConflict();
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
        order: [[1,"desc"]],
        });    
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
