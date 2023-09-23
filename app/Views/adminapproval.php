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

            <?php if(session()->getFlashdata('success') !== NULL):?>
            <div class="alert alert-success">
                <?php echo session()->getFlashdata('success') ?>
            </div>
            <?php endif;?>

            <h2 class="text-uppercase text-center">LEAVE APPROVAL</h2>

            <center>
                <h3>APPLIED LEAVES</h3>
                <br>
                <div class="text-center">
                    <table class="table table-striped table-bordered" style="width:100%" id="processing" name="processing">
                        <thead>
                            <tr>
                                <th class="text-center">RECRUITER NAME</th>
                                <th class="text-center">FROM DATE</th>
                                <th class="text-center">TO DATE</th>
                                <th class="text-center">APPROVAL STATUS</th>
                                <th class="text-center">COMMENTS</th>
                                <th class="text-center">APPROVE APPLICATION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($leaves as $keys=>$data):?>
                                <?php if(strtolower($data['APPROVAL_STATUS']) == "processing"):?>
                                    <tr>
                                        <td class="text-center"><?php echo $data["FULL_NAME"]?></td>
                                        <td class="text-center"><?php echo date("d F Y", strtotime($data["FROM_DATE"]))?></td>
                                        <td class="text-center"><?php echo date("d F Y", strtotime($data["TO_DATE"]))?></td>
                                        <td class="text-center"><?php echo "LEAVE APPLIED"?></td>
                                        <td class="text-center"><?php echo $data["COMMENTS"]?></td>
                                        <td class="text-center">
                                        <button
                                            type="button"
                                            class="btn btn-primary btn-sm approveButton"
                                            data-bs-toggle="modal"
                                            data-id="<?php echo $data["APPROVAL_ID"]?>"
                                            data-from="<?php echo date("d F Y", strtotime($data["FROM_DATE"]))?>"
                                            data-to="<?php echo date("d F Y", strtotime($data["TO_DATE"]))?>"
                                            data-recruiter="<?php echo $data["RECRUITER_ID"]?>"
                                            data-rec_name="<?php echo $data["FULL_NAME"]?>"
                                            data-bs-target="#approveModal"
                                            name="approveButton"
                                            id="approveButton">APPROVE</button>
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
                                <th class="text-center">RECRUITER NAME</th>
                                <th class="text-center">FROM DATE</th>
                                <th class="text-center">TO DATE</th>
                                <th class="text-center">APPROVAL STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($leaves as $keys=>$data):?>
                                <?php if(strtolower($data['APPROVAL_STATUS']) == "approved"):?>
                                    <tr>
                                        <td class="text-center"><?php echo $data["FULL_NAME"]?></td>
                                        <td class="text-center"><?php echo date("d F Y", strtotime($data["FROM_DATE"]))?></td>
                                        <td class="text-center"><?php echo date("d F Y", strtotime($data["TO_DATE"]))?></td>
                                        <td class="text-center"><?php echo "LEAVE APPROVED"?></td>
                                    </tr>
                                <?php endif;?>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </center>

            <?= form_open('AdminApproval/ApproveLeave')?>
                <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Approve Leave</h5>
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
                        <button type="submit" class="btn btn-primary">Approve</button>
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
                emptyTable: 'No Leaves Applied.'
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

    $("#processing tbody").on('click', '#approveButton', function(){
        var approval_id = $(this).data('id');
        var rec_name = $(this).data('rec_name');
        var from_date = $(this).data('from');
        var to_date = $(this).data('to');
        $(".form-group #approval_id").val(approval_id);
        $(".modal-body .details").html("<center>RECRUITER: "+rec_name+"<br><br>FROM DATE: <b>"+from_date+"</b><br>"+"TO DATE: <b>"+to_date+"</b><br><br>"+"<h4 class='text-center'>Would you like to cancel the leave application for the above dates?</h4></center>");
    })
</script>