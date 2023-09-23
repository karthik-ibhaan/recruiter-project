<!DOCTYPE html>
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

            <h2 class="text-uppercase text-center">Overall Status Statistics</h2>
            <br>
            <h4>Select a Date Below to Get the Overall Statistics.</h4>
            <div class="form-group col-sm-4 col-lg-2 col-md-3">
                <label>Date: </label>
                <input type="text" onkeydown="return false" class="form-control" name="datepick" id="datepick">
            </div>
            <br>
            <br>
            <table style="Width:100%" class="table table-striped table-bordered" id="overall" name="overall">
                <thead>
                    <tr>
                        <th>RECRUITER ID</th>
                        <th>RECRUITER</th>
                        <th>Total No. of Profiles Shared</th>
                        <th>No. of Profiles with Feedback Pending</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <?php include("Footers/footer.php")?>
    </body>
</html>

<style>
    table thead { text-transform: uppercase;}
</style>
<script>
    $(document).ready(function(){
        function getFileName(){
            var datepick = $("#datepick").val();
            return 'Overall Recruiter-Profile Status - '+datepick;
        }
        var mytable = $('#overall').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', {extend: 'csv', filename: function() { return getFileName();}}, {extend: 'excel', filename: function() { return getFileName();}}, {extend: 'pdf', filename: function() { return getFileName();}}, 'print'],
            columns: [
                { data: "USER_ID"},
                { data: "FULL_NAME"},
                { data: "Total"},
                { data: "Pending"}
            ],
            scrollX:        true,
            scrollCollapse: true,
            order: [[2,"desc"]],
        });
        $(function() {
            $('#datepick').datepicker({
                changeYear: true,
                changeMonth: true,
                orientation: "bottom auto",
                showButtonPanel: false,
                startView: "months",
                minViewMode: "months",
                endDate: new Date(),
                startDate: new Date("2022-12"),
                format: 'yyyy-mm',
                autoclose: true
            });
        });

        $("#datepick").change(function(){
            console.log($("#datepick").val());
            var date = $(this).val();
            if(date != "")
            {
                var xhr = new XMLHttpRequest();
                var params = "month="+date;
                var res;
                xhr.open('GET','<?php echo base_url('OverallStatus/GetDataofMonth')?>'+"?"+params, true);
                xhr.send();
                xhr.onload = function() {
                    res = JSON.parse(xhr.responseText);
                    result = {};
                    if(res != "")
                    {
                        mytable.clear();
                        mytable.rows.add( res ).draw();
                    }
                }
            }
        })
    })
</script>