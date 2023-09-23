<!doctype html>
<html lang="en">
    <?php include('Headers/head.php')?>
    <style>
        th, td { white-space: nowrap; overflow: hidden; };
    </style>
    <body>
        <?php include('Headers/header.php')?>
        <div class="page-content p-5" id="content">
            <?php if(session()->getFlashdata('updated') !== NULL):?>
                <div class="alert alert-success">
                    <?php echo session()->get('updated') ?>
                </div>
            <?php endif;?>
            <div class="alert alert-success" style="display: none;" id="successDisplay">
            </div>
            <div class="alert alert-warning" style="display: none;" id="errorDisplay">
            </div>

            <center><h2>INTERVIEW APPROVAL</h2></center>

            <button type="button" name="allInterviews" id="allInterviews" class="allInterviews btn btn-primary btn-md mb-2">All Interviews</button>
            <br>
            <br>
            <center><h4>PENDING APPROVAL</h4></center>
            <table style="width:100%;" class="table table-striped table-bordered" id="interviewapproval" name="interviewapproval">
            </table>
            <br>
            <br>
            <br>
            <br>
            <br>
            <span class="interviews2" style="display:none;">
                <center><h4>ALL INTERVIEWS</h4></center>
                <table style="width:100%;" class="table table-striped table-bordered" id="interviews2" name="interviews2">
                    <thead></thead>
                    <tbody></tbody>
                    <tfoot></tfoot>
                </table>
            </span>
            <form id="approvalForm" method="post" enctype="multipart/form-data" accept-charset="multipart/form-data">
                <div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Interview Approval Modal</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <?= csrf_field() ?>
                                <center>
                                <input type="hidden" name="interviewID" id="interviewID">
                                <div class="row">
                                    <div class="form-group col">
                                        <label>Candidate Name</label>
                                        <input type="text" name="candidateName" id="candidateName" class="form-control" disabled>
                                    </div>
                                    <div class="form-group col">
                                        <label>Interviewer Name</label>
                                        <input type="text" name="interviewerName" id="interviewerName" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col">
                                        <label>Interview Date</label>
                                        <input type="date" name="interviewDate" id="interviewDate" class="form-control">
                                    </div>
                                    <div class="form-group col">
                                        <label>Interview Time</label>
                                        <input type="time" name="interviewTime" id="interviewTime" class="form-control">
                                    </div>
                                </div>
                                </center>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="approvalsubmit" id="approvalsubmit" class="btn btn-primary">Approve</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
        <?php include('Footers/footer.php')?>
    </body>
</html>
<script>
    var interviews = <?php echo json_encode($interviews);?>;
    var fieldNames = <?php echo json_encode($fieldNames)?>;
    console.log(fieldNames);
    var fieldNames = fieldNames;
    var columns = [];
    for(var field in fieldNames)
    {
        if(fieldNames[field] == "INTERVIEW_ID")
        {

        }
        else
        {
            columns.push({ data: fieldNames[field], title: fieldNames[field], className: "text-center justify-content-center"});
        }
    }
    columns.push({ data: null, title: "APPROVE", 
        render: function(data) {
            var btn = '<div class="btn-group" role="group"><button type="button" class="btn btn-dark btn-sm approvalButton" data-bs-toggle="modal" data-bs-target="#approvalModal" name="approvalButton" id="approvalButton"><i class="bi bi-clipboard2-check-fill"></i></button></div>';
            return btn;
        },
        className: "text-center justify-content-center"
    });

    var table = $('#interviewapproval').DataTable({
        columns: columns,
        data: interviews,
        refreshTableData: function(dt, data) {
            if (!data) {
            data = dt.data();}
            
            dt.clear();
            dt.rows.add( data ).draw();
        },
        scrollX: true,
        responsive: true,
        scrollCollapse: true,
        order: [[1,"asc"]],
        fixedColumns: {
            right: 1,
            left: 0
        }
    });

    $(document).on('click', '.approvalButton', function() {
        var tr = $(this).closest('tr');
        var rowID = tr.index();
        var data = table.row(rowID).data();
        var candidateName = data.CANDIDATE_NAME;
        var interviewerName = data.INTERVIEWER_NAME;
        var interviewDateTime = data.INTERVIEW_DATETIME;
        var datetime = interviewDateTime.split(' ');
        var interviewID = data.INTERVIEW_ID;
        $("#interviewID").val(interviewID).trigger('change');
        $("#candidateName").val(candidateName).trigger('change');
        $("#interviewerName").val(interviewerName).trigger('change');
        $("#interviewDate").val(datetime[0]).trigger('change');
        $("#interviewTime").val(datetime[1]).trigger('change');
    })

    $("#approvalForm").submit(function(e){
        e.preventDefault();
        var params = new FormData($("#approvalForm")[0]);
        $("#successDisplay").hide();
        $("#errorDisplay").hide();      
        $("#approvalModal").modal('hide');
        $("#approvalForm")[0].reset();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('InterviewApproval/Approval')?>',
            data: params,
            processData: false,
            contentType: false,
            success: function(data)
            {
                var str = JSON.parse(data);
                if(str[0].valueOf() === "success".valueOf())
                {
                    $("#successDisplay").html(str[1]);
                    $("#successDisplay").show();
                    confirm(str[1]);
                    var rows = str[2];
                    for(var key in rows){
                        $("#demands").DataTable().row.add( rows[key] ).draw();
                    }
                }
                else 
                {
                    $("#errorDisplay").html(str[1]);
                    $("#errorDisplay").show();
                    alert(str[1]);
                }
            },
            statusCode: {
                500: function() {
                    alert("Error Occurred. Please Refresh The Page and Try Again.");
                },
                403: function() {
                    alert("Error Occurred. Please Refresh The Page and Try Again.");
                },
            },
        })
    })

    $(document).on('click', '#interviews2 tbody .skillExport', function() {
        var location = $(this).data('id');
        var httpRequest = new XMLHttpRequest();
        var params = 'location='+location;
        var res;
        httpRequest.open('GET','<?php echo base_url('InterviewApproval/Download')?>'+"?"+params, true);
        httpRequest.responseType = 'blob';
        httpRequest.send();
        httpRequest.onload = function() {
            res = httpRequest.response;
            var fileName = location.split(/(\\|\/)/g).pop();
            var link = document.createElement('a');
            link.href=window.URL.createObjectURL(res);
            link.download=fileName;
            link.click();
        };
    })

    $(document).on('click', '#allInterviews', function(){
        var httpRequest = new XMLHttpRequest();
        var params = "";
        var res;
        httpRequest.open('GET', '<?php echo base_url('InterviewApproval/GetInterviews')?>'+"?"+params, true);
        httpRequest.send();
        httpRequest.onload = function() {
            res = JSON.parse(httpRequest.responseText);
            var fieldNames2 = res[1];
            var interviews2 = res[0];
            var columns2 = [];
            for(var field in fieldNames2)
            {
                if(fieldNames2[field] == "INTERVIEW_ID")
                {

                }
                else if(fieldNames2[field] == "INTERVIEW_SELECTION")
                {
                    columns2.push({ data: null, title: "SELECTION STATUS", className: "text-center justify-content-center", defaultContent: "&nbsp;",
                    render: function(data) {
                        if(data.INTERVIEW_SELECTION == "" || !data.INTERVIEW_SELECTION)
                        {
                            return "";
                        }
                        else if(data.INTERVIEW_SELECTION == "1")
                        {
                            return "SELECTED";
                        }
                        else if(data.INTERVIEW_SELECTION == "0")
                        {
                            return "REJECTED";
                        }
                    }})
                }
                else if(fieldNames2[field] == "SKILL_ANALYSIS")
                {
                    columns2.push({ data: null, title: "SKILL ANALYSIS EXPORT", className: "text-center justify-content-center", defaultContent: "&nbsp;",
                    render: function(data){
                        if(data.SKILL_ANALYSIS == "" || !data.SKILL_ANALYSIS)
                        {
                        }
                        else
                        {
                            var btn="<button type='button' class='btn btn-dark btn-sm editButton skillExport' data-id='"+data.SKILL_ANALYSIS+"'>EXPORT</button>";
                            return btn;
                        }
                    }})
                }
                else
                {
                    columns2.push({ data: fieldNames2[field], title: fieldNames2[field].replace(/_+/g, ' '), className: "text-center justify-content-center", defaultContent: "&nbsp;"});
                }
            }
            var table2 = $('#interviews2').DataTable({
                columns: columns2,
                data: interviews2,
                refreshTableData: function(dt, data) {
                    if (!data) {
                    data = dt.data();}
                    
                    dt.clear();
                    dt.rows.add( data ).draw();
                },
                // scrollX: true,
                responsive: true,
                scrollCollapse: true,
                order: [[1,"asc"]],
                initComplete: function( settings, json ){
                    $("#interviews2").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
                }
            });
            $(".interviews2").show();
        }
    })
</script>