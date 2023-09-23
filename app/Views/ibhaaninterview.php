<!doctype html>
<html lang="en">
    <?php include('head.php')?>
    <body>
        <?php include('header.php')?>
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

            <center><h2>IBHAAN INTERVIEWS</h2></center>
            <table style="width:100%;" class="table table-striped table-bordered" id="interviews" name="interviews">
            </table>
            <br>
            <br>
            <br>
            <br>
            <br>
            <table class="table table-striped table-bordered" id="interviews2" name="interviews2" style="display:none;">
            <thead></thead>
            <tbody></tbody>
            </table>

            <form id="updateForm" method="post" enctype="multipart/form-data" accept-charset="multipart/form-data">
                <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Interview Status Update Modal</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <?= csrf_field() ?>
                                <center>
                                <input type="hidden" name="interviewID" id="interviewID">
                                <input type="hidden" name="candidateName" id="candidateName">
                                <div class="row">
                                    <div class="form-group col">
                                        <label>Candidate Name</label>
                                        <input type="text" name="candidateNameDisplay" id="candidateNameDisplay" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col">
                                        <label>Interview Status Update</label>
                                        <select name="interviewResult" id="interviewResult" class="form-control">
                                            <option value="">--</option>
                                            <option value="1">Selected</option>
                                            <option value="0">Rejected</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col skillAnalysis" style="display: none;">
                                        <label data-bs-toggle="tooltip" class="required">Skill Analysis</label>
                                        <input type="file" class="form-control" name="skillAnalysis" id="skillAnalysis" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.pdf" class="form-control">
                                    </div>
                                    <div class="form-group col skillAnalysis2" style="display: none;">
                                        <label data-bs-toggle="tooltip" class="required">Skill Analysis</label>
                                        <input type="text" class="form-control" name="skillAnalysis2" id="skillAnalysis2" placeholder="Text..." class="form-control">
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
        <?php include("Footers/footer.php")?>
    </body>
</html>
<script>
    var interviews = <?php echo json_encode($interviews);?>;
    var fieldNames = <?php echo json_encode($fieldNames)?>;
    console.log(fieldNames);
    var fieldNames = fieldNames;
    var columns = [];
    columns.push({ data: null, title: "EXPORT", className: "text-center justify-content-center", defaultContent: "&nbsp;",
        render: function(data){
            var btn = '<div class="btn-group" role="group"><button type="button" class="btn btn-dark btn-sm exportButton" data-id="'+data.INTERVIEW_ID+'" data-candidate="'+data.CANDIDATE_ID+'"><i class="bi bi-download"></i> | Download</button>';
            return btn;
        }
    });
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
    columns.push({ data: null, title: "UPDATE", 
        render: function(data) {
            var btn = '<div class="btn-group" role="group"><button type="button" class="btn btn-dark btn-sm updateButton" data-bs-toggle="modal" data-id="'+data.INTERVIEW_ID+'" data-bs-target="#updateModal" name="updateButton" id="updateButton"><i class="bi bi-clipboard2-check-fill"></i></button></div>';
            return btn;
        },
        className: "text-center justify-content-center"
    });

    var table = $('#interviews').DataTable({
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

    $(document).on('click', '.updateButton', function() {
        var tr = $(this).closest('tr');
        var rowID = tr.index();
        var data = table.row(rowID).data();
        var interviewID = data.INTERVIEW_ID;
        var candidateName = data.CANDIDATE_NAME;
        $("#interviewID").val(interviewID).trigger('change');
        $("#candidateName").val(candidateName).trigger('change');
        $("#candidateNameDisplay").val(candidateName).trigger('change');
    })

    $("#updateForm").submit(function(e){
        e.preventDefault();
        var params = new FormData($("#updateForm")[0]);
        $("#successDisplay").hide();
        $("#errorDisplay").hide();      
        $("#updateModal").modal('hide');
        $("#updateForm")[0].reset();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('IbhaanInterview/StatusUpdate')?>',
            data: params,
            processData: false,
            contentType: false,
            success: function(data)
            {
                var str = JSON.parse(data);
                console.log(str);
                if(str[0].valueOf() === "success".valueOf())
                {
                    $("#successDisplay").html(str[1]);
                    $("#successDisplay").show();
                    confirm(str[1]);
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

    $(document).on('change', '#interviewResult', function(){
        var val = $(this).val();
        console.log(val);
        $(".skillAnalysis").hide();
        $(".skillAnalysis2").hide();
        if(val == "1")
        {
            $(".skillAnalysis").show();
        }
        else
        {
            $(".skillAnalysis2").show();
        }
    })
    $('#interviews tbody').on('click', '.exportButton', function () {
        var tr = $(this).closest('tr');
        var row = $("#interviews").DataTable().row(tr);
        var data = row.data();
        console.log(data.INTERVIEW_ID);

        var httpRequest = new XMLHttpRequest();
        var params = 'interview_id='+data.INTERVIEW_ID;
        var res;
        httpRequest.open('GET','<?php echo base_url('IbhaanInterview/CandidateData')?>'+"?"+params, true);
        httpRequest.responseType = 'blob';
        httpRequest.send();
        httpRequest.onload = function() {
            res = httpRequest.response;
            var candidateName = data.CANDIDATE_NAME;
            var pattern = /[., _]+/g;
            candidateName = candidateName.replace(pattern, "_");
            console.log(candidateName);
            var fileName =  candidateName + ".zip";
            var link = document.createElement('a');
            link.href=window.URL.createObjectURL(res);
            link.download=fileName;
            link.click();
        };
    });

    function loader() {
        return (
            '<div class="d-flex justify-content-left spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
        );
    }
    
    function format(d, e) {
        // `d` is the original data object for the row
        var a;
        var b;
        if(d != undefined && d!== "")
        {
            a = '<div class="form-group inline-block" style="text-align: left;"><h4 class="col">Download The CV of the Candidate With The Button Below:</h4><button style="width: auto; display: inline-block;" type="button" class="btn btn-outline-dark btn-sm" data-id="'+d+'" name="cvView" id="cvView"><i class="bi bi-download"></i> | Download</button></div>';
        }
        else if(e != undefined && e !== "")
        {
            b = '<div class="form-group inline-block" style="text-align: left;"><h4 class="col">Download The JD With The Button Below:</h4><button style="width: auto; display: inline-block;" type="button" class="btn btn-outline-dark btn-sm" data-id="'+d+'" name="jdView" id="jdView"><i class="bi bi-download"></i> | Download</button></div>';
        }
        else
        {
            a = '<h4>No JD Document Available.</h4>'
            b = ''
        }
        if(a && b)
        return ( a + '<br>' + b )
        else if(a)
        return ( a )
        else if(b)
        return ( b )
    }
</script>