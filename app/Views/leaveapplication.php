<!doctype html>
<html lang="en">
    <?php include('Headers/head.php')?>
    <body>
        <?php include('Headers/header.php')?>

        <?php if(isset($validation)):?>
            <div class="alert alert-warning">
            <?= $validation->listErrors() ?>
            </div>
        <?php endif;?>
        <div class="page-content p-5" id="content">
            <?php if(session()->getFlashdata('error') !== NULL):?>
            <div class="alert alert-warning">
                <?php echo session()->getFlashdata('error') ?>
            </div>
            <?php endif;?>

            <?php if(session()->getFlashdata('success') !== NULL):?>
            <div class="alert alert-success">
                <?php echo session()->getFlashdata('success') ?>
            </div>
            <?php endif;?>

            <h2 class="text-uppercase text-center">LEAVE APPLICATION</h2>

            <br>
            <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#addModal">APPLY LEAVE</button>
            <br>
            <center>
                <h3>APPLIED LEAVES</h3>
                <br>
                <div class="text-center">
                    <table class="table table-striped table-bordered" style="width:100%" id="processing" name="processing">
                        <thead>
                            <tr>
                                <th class="text-center">FROM DATE</th>
                                <th class="text-center">TO DATE</th>
                                <th class="text-center">APPROVAL STATUS</th>
                                <th class="text-center">CANCEL APPLICATION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($leaves as $keys=>$data):?>
                                <?php if(strtolower($data['APPROVAL_STATUS']) == "processing"):?>
                                    <tr>
                                        <td class="text-center"><?php echo date("d F Y", strtotime($data["FROM_DATE"]))?></td>
                                        <td class="text-center"><?php echo date("d F Y", strtotime($data["TO_DATE"]))?></td>
                                        <td class="text-center"><?php echo "LEAVE APPLIED"?></td>
                                        <td class="text-center">
                                        <button
                                            type="button" 
                                            class="btn btn-danger btn-sm cancelButton" 
                                            data-bs-toggle="modal" 
                                            data-id="<?php echo $data["APPROVAL_ID"]?>"
                                            data-from="<?php echo date("d F Y", strtotime($data["FROM_DATE"]))?>"
                                            data-to="<?php echo date("d F Y", strtotime($data["TO_DATE"]))?>"
                                            data-bs-target="#cancelModal"
                                            name="cancelButton"
                                            id="cancelButton">CANCEL</button>
                                        </td>
                                    </tr>
                                <?php endif;?>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                    <br>

                    <h3>APPROVED LEAVES</h3>
                    <br>
                    <table class="table table-striped table-bordered" style="width:100%" id="approved" name="approved">
                        <thead>
                            <tr>
                                <th class="text-center">FROM DATE</th>
                                <th class="text-center">TO DATE</th>
                                <th class="text-center">APPROVAL STATUS</th>
                                <th class="text-center">CANCEL APPLICATION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($leaves as $keys=>$data):?>
                                <?php if(strtolower($data['APPROVAL_STATUS']) == "approved"):?>
                                    <tr>
                                        <td class="text-center"><?php echo date("d F Y", strtotime($data["FROM_DATE"]))?></td>
                                        <td class="text-center"><?php echo date("d F Y", strtotime($data["TO_DATE"]))?></td>
                                        <td class="text-center"><?php echo "LEAVE APPROVED"?></td>
                                        <td class="text-center">
                                        <button
                                            type="button" 
                                            class="btn btn-danger btn-sm cancelButton" 
                                            data-bs-toggle="modal" 
                                            data-id="<?php echo $data["APPROVAL_ID"]?>"
                                            data-from="<?php echo date("d F Y", strtotime($data["FROM_DATE"]))?>"
                                            data-to="<?php echo date("d F Y", strtotime($data["TO_DATE"]))?>"
                                            data-bs-target="#cancelModal"
                                            name="cancelButton"
                                            id="cancelButton">CANCEL</button>
                                        </td>
                                    </tr>
                                <?php endif;?>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </center>

            <?= form_open('LeaveApplication/ApplyLeave')?>
                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Apply Leave</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="from_date">FROM DATE</label>
                            <input type="date" onkeydown="return false" class="form-control" name="from_date" id="datepick">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="to_date">TO DATE</label>
                            <input type="date" onkeydown="return false" class="form-control" name="to_date" id="datepick2">
                        </div>
                        <br>
                        <div class="form-group">
                            <label>COMMENTS</label>
                            <input type="text" class="form-control" name="comment" id="comment" placeholder="Comment...">
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

            <?= form_open('LeaveApplication/CancelApplication')?>
                <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Cancel Leave</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <input type="hidden" name="approval_id" id="approval_id" value="">
                        </div>
                        <div class="details">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                    </div>
                </div>
                </div>
            </form>

        </div>
        <?php include("Footers/footer.php")?>
    </body>
</html>

<script>
    $(document).ready(function() {

        $.fn.dataTable.moment('DD MMMM YYYY');

        var table = $('#approved').DataTable({
            language: {
                emptyTable: "Leaves Yet To Be Approved."
            },
            scrollResize: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns:   {
                right: 1,
                left: 0
            },
            paging: false,
            searching: false,
            order: [[0,"asc"]],
        });

        var table = $('#processing').DataTable({
            language: {
                emptyTable: '<button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#addModal">APPLY LEAVE</button> to View Here.'
            },
            scrollResize: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns:   {
                right: 1,
                left: 0
            },
            paging: false,
            searching: false,
            order: [[0,"asc"]],
        });
        $("#datepick").change(function() {
            var date2 = $(this);
            if(date2 != "")
            {
                $("#datepick2").attr("min", $("#datepick").val());
            }
            else
            {
                $("#datepick2").removeAttr("min");                
            }
        })

        $("#datepick2").change(function() {
            var date = $(this);
            if (date != "")
            {
                $("#datepick").attr("max", $("#datepick2").val());
            }
            else
            {
                $("#datepick").removeAttr("max");
            }
        })
    })

    $("#processing tbody").on('click', '#cancelButton', function(){
        var approval_id = $(this).data('id');
        var from_date = $(this).data('from');
        var to_date = $(this).data('to');
        $(".form-group #approval_id").val(approval_id);
        $(".modal-body .details").html("<center>FROM DATE: <b>"+from_date+"</b><br>"+"TO DATE: <b>"+to_date+"</b><br><br>"+"<h4 class='text-center'>Would you like to cancel the leave application for the above dates?</h4></center>");
    })

    $("#approved tbody").on('click', '#cancelButton', function(){
        var approval_id = $(this).data('id');
        var from_date = $(this).data('from');
        var to_date = $(this).data('to');
        $(".form-group #approval_id").val(approval_id);
        $(".modal-body .details").html("<center>FROM DATE: <b>"+from_date+"</b><br>"+"TO DATE: <b>"+to_date+"</b><br><br>"+"<h4 class='text-center'>Would you like to cancel the leave application for the above dates?</h4></center>");
    })


</script>