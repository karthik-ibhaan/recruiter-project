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
            <?php echo session()->getFlashdata('success')?>
        </div>
        <?php endif;?>
        <?php if(session()->getFlashdata('success2') !== NULL):?>
        <div class="alert alert-success">
            <?php echo session()->getFlashdata('success2')?>
        </div>
        <?php endif;?>
        <div class="alert alert-success" style="display: none;" id="successDisplay">
        </div>
        <div class="alert alert-warning" style="display: none;" id="errorDisplay">
        </div>
        <center><h2>YOUR CANDIDATES</h2></center>

        <div class="separator"></div>

        <h3>To see all of the current candidates, <a class="btn btn-primary" href="/candidatesview">Click Here</a></h3>

        <div class="separator"></div>

        <!--<button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>-->
        <div class="row">
            <div class="column mt-2">
                <?php foreach($status as $keys=>$data):?>
                    <button type="button" name="<?php echo $keys?>-category" id="<?php echo $keys?>-category" class="btn btn-danger mb-2 statuses" data-id="<?php echo $keys?>"><?php echo $keys?></button>
                <?php endforeach;?>
                <button type="button" name="reset-button" class="btn btn-primary mb-2" id="reset-button" value="<?php echo ""?>" >Reset Filter</button>
            </div>
        </div>
        <div class="separator"></div>
        <div class="text-left">
            <table class="table table-striped table-bordered" id="candidates" name="candidates" style="width:100%">
                <tfoot>
                    <tr>
                        <th></th>
                        <?php foreach($fieldNames as $keys=>$values):?>
                            <th></th>
                        <?php endforeach;?>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <form id="addForm" method="post" enctype="multipart/form-data">
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Candidate</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= csrf_field() ?>
                <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="The client for whom the candidate is sourced.">Client</label>
                        <select name="client" id = "client" class="form-control" required>
                            <option value="">-Select-</option>
                            <?php $uniqueIDs = array_unique(array_map(function ($i) { return $i['CLIENT_ID']; }, $demandOptions));?>
                            <?php $uniqueNames = array_unique(array_map(function ($i) { return $i['CLIENT_NAME']; }, $demandOptions));?>
                            <?php foreach($uniqueIDs as $keys => $data):?>
                                <?php $id = str_replace("&", "", $keys);?>
                                <?php $id = str_replace(" ","", $id);?>
                                <option id="<?php echo $data?>" data-target="<?php echo $data?>" value="<?php echo $data?>"><?php echo $uniqueNames[$keys]?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <?php $uniqueIDs = array_unique(array_map(function ($i) { return $i['CLIENT_ID']; }, $demandOptions));?>
                    <?php $uniqueNames = array_unique(array_map(function ($i) { return $i['CLIENT_NAME']; }, $demandOptions));?>

                    <div class="form-group col">
                    <?php foreach($uniqueIDs as $keys=>$id):?>
                        <div id="client-<?php echo $id?>" style="display:none" class="col">
                            <div class="form-group">
                                <label class="required" data-bs-toggle="tooltip" data-bs-placement="left" title="The Job Title for which the candidate is sourced.">Job Title</label>
                                <select name="<?php echo $id?>" id="demand" class="demand search-filter form-control">
                                    <option value="">-Select-</option>
                                    <?php foreach($demandOptions as $keys => $data):?>
                                        <?php if($data['CLIENT_ID'] == $id):?>
                                            <option value="<?php echo $data['DEMAND_ID']?>" id="<?php echo $data['DEMAND_ID']?>" data-target="<?php echo $data['DEMAND_ID']?>"><?php echo $data['DEMAND_ID']?> - <?php echo $data['JD_ID']?> <?php echo $data['JOB_TITLE']?></option>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                    <?php endforeach;?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="The name of the Candidate.">Candidate Name</label>
                        <input type="text" class="form-control" name="candidate_name" placeholder="Candidate Name..." required title="The first character must be a letter">
                    </div>
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="The email address of the candidate">Email Address</label>
                        <input type="email" id="emailAdd" class="search-filter form-control" name="emailAdd" placeholder="email@example.com" required>
                    </div>
                    <label name="requiredElement" id="requiredElement"></label>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="The current stage of recruitment.">Process</label>
                        <select name="action" id = "action" class="form-control" required>
                            <option value="">-Select-</option>
                            <?php foreach($status as $keys => $data):?>
                                <?php $id = str_replace("&", "", $keys);?>
                                <?php $id = str_replace(" ","", $id);?>
                                <option id="<?php echo $id?>" data-target="<?php echo $id?>" value="<?php echo $keys?>"><?php echo $keys?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <?php foreach($status as $keys => $data):?>
                        <?php $id = str_replace("&", "", $keys);?>
                        <?php $id = str_replace(" ","", $id);?>
                        <div id="action-<?php echo $id?>" style="display:none" class="col">
                            <div class="form-group">
                                <label data-bs-toggle="tooltip" data-bs-placement="left" title="The status of recruitment.">Recruitment Status</label>
                                <select name="<?php echo $id?>" id="status" class="status form-control">
                                    <option value="">-Select-</option>
                                    <?php foreach($data as $keys2 => $value):?>
                                        <option value="<?php echo $value?>" id="<?php echo str_replace(" ", "", $value)?>" data-target="<?php echo str_replace(" ", "", $value)?>"><?php echo $value?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label class="required" data-bs-toggle="tooltip" data-bs-placement="right" title="Phone Number of the Candidate.">Phone Number</label>
                        <input type="number" aria-label="Phone Number 1" min="0" class="form-control" id="phno_1" name="phno_1" placeholder="1234567890" pattern = "[6789]\d{9,9}" required>
                    </div>
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="right" title="Alternate Phone Number of the Candidate. This is not mandatory.">Alternate Phone Number</label>
                        <input type="number" aria-label="Phone Number 2" min="0" class="form-control" id="phno_2" name="phno_2" placeholder="1234567890" pattern = "[6789]\d{9,9}">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label class="required">Organisation</label>
                        <input type="text" class="form-control" name="organisation" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                    <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="The Current Working Location of the Candidate.">Location</label>
                        <select name="location" id="location" class="form-control" required>
                            <option value="">-Select-</option>
                            <?php foreach($location as $keys=>$data):?>
                                <option value="<?php echo $data?>"><?php echo $data?></option?>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="Experience required by the candidates.">Experience</label>
                        <input type="number" step=0.1 min=1 class="form-control" name="experience" placeholder="5.7" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="Current CTC in Lakhs per Annum.">Current CTC (in LPA)</label>
                        <input type="number" step=0.1 min=0 class="form-control" name="cctc" placeholder="5.7" required>
                    </div>
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="Expected CTC in Lakhs per Annum.">Expected CTC (in LPA)</label>
                        <input type="text" class="form-control" name="ectc" id="ectc" placeholder="5.7" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="Notice Period (in Days) Ex: 30">Notice Period (in Days)</label>
                        <input type="text" class="form-control" name="NP" id="NP" placeholder="30" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label>Interview Date</label>
                        <input type="date" class="form-control" name="interview-date" id="interview-date">
                    </div>
                    <div class="form-group col">
                        <label>Interview Time</label>
                        <input type="time" class="form-control" name="interview-time" id="interview-time">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="CV to upload, which is stored in the system. Can be Downloaded Later. Preferred Format: .pdf, .docx">CV Upload</label>
                        <input type="file" class="form-control" name="cv" id="cv" required accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.pdf">
                    </div>
                </div>
                <div class="row" id="selection-data" style="display:none">
                    <center><label class="text-uppercase" style="text-align: center;"><b>SELECTION DATA</b></label></center>
                    <div class="row">
                        <div class="form-group col">
                            <label>Offer CTC <span style="color: red">*</span></label>
                            <input type="number" step=0.1 min=1 class="form-control" name="selectionCTC" placeholder="5.7">
                        </div>
                        <div class="form-group col">
                            <label>Selection Date <span style="color: red">*</span></label>
                            <input type="date" class="form-control" name="selectionDate" id="selectionDate">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label>Planned Date of Joining <span style="color: red">*</span></label>
                            <input type="date" class="form-control" name="plannedDOJ" id="plannedDOJ">
                        </div>
                        <div class="form-group col">
                            <label>Actual Date of Joining</label>
                            <input type="date" class="form-control" name="actualDOJ" id="actualDOJ">
                        </div>
                        <div class="form-group col">
                            <label>Exit Date</label>
                            <input type="date" class="form-control" name="exitDate" id="exitDate">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="addsubmit" id="addsubmit" class="btn btn-primary">Save</button>
            </div>
            </div>
        </div>
        </div>
    </form>

    <form id="editForm" method="post" enctype="multipart/form-data" accept-charset="multipart/form-data">
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Candidate</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?= csrf_field() ?>

                        <input type="hidden" name="candidateID2" id="candidateID2">
                        <input type="hidden" name="demand2" id="demand2" >
                        <div class="row">
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="The client for whom the candidate is sourced.">Client</label>
                                <select name="client_id2" id = "client_id2" class="form-control" required>
                                    <option value="">-Select-</option>
                                    <?php $uniqueIDs = array_unique(array_map(function ($i) { return $i['CLIENT_ID']; }, $clients));?>
                                    <?php $uniqueNames = array_unique(array_map(function ($i) { return $i['CLIENT_NAME']; }, $clients));?>
                                    <?php foreach($uniqueIDs as $keys => $data):?>
                                        <?php $id = str_replace("&", "", $keys);?>
                                        <?php $id = str_replace(" ","", $id);?>
                                        <option id="<?php echo $data?>" data-target="<?php echo $data?>" value="<?php echo $data?>"><?php echo $uniqueNames[$keys]?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" data-bs-placement="right" title="The Job Title for which the candidate is sourced.">Job Title</label>
                                <input type="text" name="j_title2" id="j_title2" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="The name of the Candidate.">Candidate Name</label>
                                <input type="text" class="form-control" name="candidate_name2" id="candidate_name2" placeholder="Candidate Name..." required>
                            </div>
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="The email address of the candidate">Email Address</label>
                                <input type="email" class="search-filter form-control" name="emailAdd2" id="emailAdd2" placeholder="email@example.com" required>
                            </div>
                            <label name="requiredElement" id="requiredElement"></label>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="The Recruitment Status of the Candidate">Recruitment Status</label>
                                <select name="status2" id="status2" class="status2 form-control">
                                    <option value="">-Select-</option>
                                    <?php foreach($status as $keys => $data):?>
                                        <?php foreach($data as $keys2 => $value):?>
                                            <option value="<?php echo $value?>" id="<?php echo str_replace(" ", "", $value)?>" data-target="<?php echo str_replace(" ", "", $value)?>"><?php echo $value?></option>
                                        <?php endforeach;?>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="Phone Number of the Candidate.">Phone Number</label>
                                <input type="number" aria-label="Phone Number 1" class="form-control" id="edit_phno_1" name="edit_phno_1" placeholder="1234567890" pattern = "[6789]\d{9,9}" required>
                            </div>
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" data-bs-placement="right" title="Alternate Phone Number of the Candidate. This is not mandatory.">Alternate Phone Number</label>
                                <input type="number" aria-label="Phone Number 2" class="form-control" id="edit_phno_2" name="edit_phno_2" placeholder="1234567890" pattern = "[6789]\d{9,9}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="The organisation that the candidate belongs to.">Organisation</label>
                                <input type="text" class="form-control" name="organisation2" id="organisation2" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                            <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="The Current Working Location of the Candidate.">Location</label>
                                <select name="location2" id="location2" class="form-control" required>
                                    <option value="">-Select-</option>
                                    <?php foreach($location as $keys=>$data):?>
                                        <option value="<?php echo $data?>"><?php echo $data?></option?>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="Experience required by the candidates.">Experience</label>
                                <input type="number" step=0.1 min=1 class="form-control" name="experience2" id="experience2" placeholder="5.7" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="Current CTC in Lakhs per Annum.">Current CTC (in LPA)</label>
                                <input type="number" step=0.1 min=0 class="form-control" name="cctc2" id="cctc2" placeholder="5.7" required>
                            </div>
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="Expected CTC in Lakhs per Annum.">Expected CTC (in LPA)</label>
                                <input type="text" class="form-control" name="ectc2" id="ectc2" placeholder="5.7" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="Notice Period (in Days) Ex: 30">Notice Period (in Days)</label>
                                <input type="text" class="form-control" name="NP2" id="NP2" placeholder="30" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label>Interview Date</label>
                                <input type="date" class="form-control" name="interview-date2" id="interview-date2">
                            </div>
                            <div class="form-group col">
                                <label>Interview Time</label>
                                <input type="time" class="form-control" name="interview-time2" id="interview-time2">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" data-bs-placement="left" title="CV to upload, which is stored in the system. Can be Downloaded Later. Preferred Format: .pdf, .docx">CV Upload</label>
                                <input type="file" class="form-control" name="cv2" id="cv2" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.pdf">
                            </div>
                        </div>
                        <span id="selection-data2" style="display:none">
                            <center><label class="text-uppercase" style="text-align: center;"><b>SELECTION DATA</b></label></center>
                            <div class="row">
                                <div class="form-group col">
                                    <label>Offer CTC <span style="color: red">*</span></label>
                                    <input type="number" step=0.1 min=1 class="form-control" name="selectionCTC2" id="selectionCTC2" placeholder="5.7">
                                </div>
                                <div class="form-group col">
                                    <label>Selection Date <span style="color: red">*</span></label>
                                    <input type="date" class="form-control" name="selectionDate2" id="selectionDate2">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label>Planned Date of Joining <span style="color: red">*</span></label>
                                    <input type="date" class="form-control" name="plannedDOJ2" id="plannedDOJ2">
                                </div>
                                <div class="form-group col" id="actualDOJForm2">
                                    <label>Actual Date of Joining</label>
                                    <input type="date" class="form-control" name="actualDOJ2" id="actualDOJ2">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label>Exit Date</label>
                                    <input type="date" class="form-control" name="exitDate2" id="exitDate2">
                                </div>
                            </div>
                        </span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="addsubmit" id="addsubmit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="deleteForm" method="post" enctype="multipart/form-data" accept-charset="multipart/form-data">
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Candidate</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <input type="hidden" name="rowID" id="rowID">
                        <div class="form-group">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Demand ID in Database">Candidate ID</label>
                            <input type="text" class="form-control" style="pointer-events:none" name="candidate_id3" id="candidate_id3" pattern="\S(.*\S)?" required>
                        </div>
                        <div class="form-group">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Job Title">Job Title</label>
                            <input type="text" class="form-control" style="pointer-events:none" name="candidate_name3" id="candidate_name3" pattern="\S(.*\S)?" required>
                        </div>
                        <center>
                            <h3>Are you sure you want to delete this profile details?</h3>
                        </center>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php include('Footers/footer.php')?>
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

        // $("#addModal").on("hidden.bs.modal", function(){
        //     console.log("Closing");
        //     document.location.reload();
        // });
        // $("#editModal").on("hidden.bs.modal", function(){
        //     console.log("Closing");
        //     document.location.reload();
        // });

        $("#reset-button").attr('disabled',true);

        $("#reset-button").click(function(){
            $("#reset-button").attr('disabled', true);
            $('.statuses').attr('disabled', false);
            $("#candidates").DataTable().columns().search('').draw();
        });
        var target;
        var today = new Date().toISOString().split('T')[0];
        /* Printing DataTable */

        var fieldNames = <?php echo json_encode($fieldNames)?>;
        var fieldNames = fieldNames;
        var columns = [];
        columns.push({ data: null, title: "CV", orderable: false, className: 'dt-control', defaultContent: '' });

        for(var field in fieldNames)
        {
            columns.push({ data: fieldNames[field] , title: fieldNames[field].replace("_"," "), className: "text-center justify-content-center col"});
        }
        columns.push({ data: null, title: "EDITS", 
            render: function(data) {
                var btn = '<div class="btn-group" role="group"><button type="button" class="btn btn-dark btn-sm editButton" data-bs-toggle="modal" data-id="'+data.CANDIDATE_ID+'" data-bs-target="#editModal" name="editButton" id="editButton"><i class="bi bi-pen"></i></button><button type="button" class="btn btn-dark btn-sm deleteButton" data-bs-toggle="modal" data-id="'+data.CANDIDATE_ID+'" data-name="'+data.CANDIDATE_NAME+'" data-bs-target="#deleteModal"><i class="bi bi-trash"></i></button></div>';
                return btn;
            },
            className: "text-center justify-content-center"
        });
        var candidates = <?php echo json_encode($candidates)?>;
        Object.entries(candidates).forEach(([key, value]) => {
            var phno = JSON.parse(value["PHONE_NO"]);
            if(phno[1])
            {
                candidates[key]["PHONE_NO"] = phno[0]+", "+phno[1];
            }
            else
            {
                candidates[key]["PHONE_NO"] = phno[0];
            }
        })

        var table = $('#candidates').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', {extend: 'csv', filename: function() { return getFileName();}}, {extend: 'excel', filename: function() { return getFileName();}}, 'print'],
            columns: columns,
            data: candidates,
            columnDefs: [ {
                defaultContent: "",
                targets: "_all"
            }],
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
            order: [[6,"desc"]],
            columnDefs: [{ width: 200, targets: 0 }],
            saveState: false,
            scrollX:        true,
            scrollCollapse: true,
            fixedColumns:   {
                right: 1,
                left: 0
            }
        });
        
        $("#candidates")[0].tBodies[0].className = "container";
        function getFileName(){
            var date = new Date();
            
            return "Candidates As of: "+" - "+date.getDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear();
        }
        /* DataTable Printing End */
        
        var $container = $(".container");
        var $scroller = $(".dataTables_scrollBody");;
        
        bindDragScroll($container, $scroller);
        window.dispatchEvent(new Event('resize'));
        function bindDragScroll($container, $scroller) {
        
            var $window = $(window);
            
            var x = 0;
            var y = 0;
            
            var x2 = 0;
            var y2 = 0;
            var t = 0;
        
            $container.on("mousedown", down);
            $container.on("click", preventDefault);
            $scroller.on("mousewheel", horizontalMouseWheel); // prevent macbook trigger prev/next page while scrolling
    
            function down(evt) {
            //alert("down");
                if (evt.button === 0) {
                
                t = Date.now();
                x = x2 = evt.pageX;
                y = y2 = evt.pageY;
                
                $container.addClass("down");
                $window.on("mousemove", move);
                $window.on("mouseup", up);
                
                evt.preventDefault();
                
                }
                
            }
        
            function move(evt) {
                // alert("move");
                if ($container.hasClass("down")) {
                
                var _x = evt.pageX;
                var _y = evt.pageY;
                var deltaX = _x - x;
                var deltaY = _y - y;
                
                $scroller[0].scrollLeft -= deltaX;
                
                x = _x;
                y = _y;
                
                }
                
            }
        
            function up(evt) {
            
                $window.off("mousemove", move);
                $window.off("mouseup", up);
                
                var deltaT = Date.now() - t;
                var deltaX = evt.pageX - x2;
                var deltaY = evt.pageY - y2;
                if (deltaT <= 300) {
                    $scroller.stop().animate({
                        scrollTop: "-=" + deltaY * 3,
                        scrollLeft: "-=" + deltaX * 3
                        }, 500, function (x, t, b, c, d) {
                            // easeOutCirc function from http://gsgd.co.uk/sandbox/jquery/easing/
                            return c * Math.sqrt(1 - (t = t / d - 1) * t) + b;
                    });
                }
                
                t = 0;
                
                $container.removeClass("down");
                
            }
        
            function preventDefault(evt) {
                if (x2 !== evt.pageX || y2 !== evt.pageY) {
                    evt.preventDefault();
                    return false;
                }
            }
            
            function horizontalMouseWheel(evt) {
                evt = evt.originalEvent;
                var x = $scroller.scrollLeft();
                var max = $scroller[0].scrollWidth - $scroller[0].offsetWidth;
                var dir = (evt.deltaX || evt.wheelDeltaX);
                var stop = dir > 0 ? x >= max : x <= 0;
                if (stop && dir) {
                    evt.preventDefault();
                }
            }
        }
        
        function disablebutton(){
            $('#addsubmit').attr("disabled", true);
        }

        function enablebutton(){
            $('#addsubmit').attr("disabled", false);
        }

        var value;
        var timeout = null;

        $(document).on('change', "select.search-filter[data-class=search-class]", function() {
            var value = $(this).val();
            var value2 = $("#emailAdd").val();
            timeout = setTimeout(() => {
                if(value != "" && value2 != "")
                {
                    var httpRequest = new XMLHttpRequest();
                    var params = "demand_id="+value+"&email="+value2;
                    var res;
                    httpRequest.open('GET','<?php echo base_url('Candidates/CheckExisting')?>'+"?"+params, true);
                    httpRequest.send();
                    httpRequest.onload = function() {
                        res = JSON.parse(httpRequest.responseText);
                        if(res[0] == "t")
                        {
                            document.getElementById("requiredElement").textContent = "REQUIRED";
                            document.getElementById("requiredElement").style.display = "block";
                            var emailAdd = document.getElementById("emailAdd");
                            emailAdd.setCustomValidity("This Candidate Has Already Been Sent for this Job Title");
                            emailAdd.style.border = "2px solid red";
                            emailAdd.reportValidity();
                        }
                        else if(res[0] == "f")
                        {
                            var emailAdd = document.getElementById("emailAdd");
                            emailAdd.setCustomValidity("");
                            emailAdd.style.border = "";
                            document.getElementById("requiredElement").style.display = "none";
                        }
                    }
                }
                else if(value == "" || value2 == "")
                {
                    var emailAdd = document.getElementById("emailAdd");
                    emailAdd.setCustomValidity("");
                    emailAdd.style.border = "";
                    document.getElementById("requiredElement").style.display = "none";
                }
            }, 50);
        });


        $("#phno_1").on('input keup keydown', function(){
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                var reg = /[6789]\d{9,9}/;
                var value = $("#phno_1").val();
                if(value != "")
                {
                    if(reg.test(value))
                    {
                        var phno1 = document.getElementById("phno_1");
                        phno1.style.border = "";
                        phno1.setCustomValidity("");                    }
                    else
                    {
                        var phno1 = document.getElementById("phno_1");
                        phno1.style.border = "2px solid red";
                        phno1.setCustomValidity("Phone Numbers Can Only be 10 Digits.");
                        phno1.reportValidity();
                    }
                    if(value.length != 10)
                    {
                        var phno1 = document.getElementById("phno_1");
                        phno1.style.border = "2px solid red";
                        phno1.setCustomValidity("Phone Numbers Can Only be 10 Digits.");
                        phno1.reportValidity();
                    }
                }
                else if(value == "")
                {
                    var phno1 = document.getElementById("phno_1");
                    phno1.style.border = "";
                    phno1.setCustomValidity("");                    
                }
            },500)
        })
        $("#phno_2").on('input keup keydown', function(){
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                var reg = /[6789]\d{9,9}/;
                var value = $("#phno_2").val();
                if(value != "")
                {
                    if(reg.test(value))
                    {
                        var phno2 = document.getElementById("phno_2");
                        phno2.style.border = "";
                        phno2.setCustomValidity("");
                    }
                    else
                    {
                        var phno2 = document.getElementById("phno_2");
                        phno2.style.border = "2px solid red";
                        phno2.setCustomValidity("Phone Numbers Can Only be 10 Digits.");
                        phno2.reportValidity();
                    }
                    if(value.length != 10)
                    {
                        var phno2 = document.getElementById("phno_2");
                        phno2.style.border = "2px solid red";
                        phno2.setCustomValidity("Phone Numbers Can Only be 10 Digits.");
                        phno2.reportValidity();
                    }
                }
                else if(value == "")
                {
                    var phno2 = document.getElementById("phno_2");
                    phno2.style.border = "";
                    phno2.setCustomValidity("");                    
                }
            },500)
        })
        
        $("#emailAdd").on('input keyup keydown', function() {
            var search = $("select.search-filter[data-class=search-class]").val();
            var value = search;
            var value2 = $("#emailAdd").val();
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                if(value != "" && value2 != "")
                {
                    var httpRequest = new XMLHttpRequest();
                    var params = "demand_id="+value+"&email="+value2;
                    var res;
                    httpRequest.open('GET','<?php echo base_url('Candidates/CheckExisting')?>'+"?"+params, true);
                    httpRequest.send();
                    httpRequest.onload = function() {
                        res = JSON.parse(httpRequest.responseText);
                        if(res[0] == "t")
                        {
                            document.getElementById("requiredElement").textContent = "REQUIRED";
                            document.getElementById("requiredElement").style.display = "block";
                            var emailAdd = document.getElementById("emailAdd");
                            emailAdd.setCustomValidity("This Candidate Has Already Been Sent for this Job Title. Please Search for Another.");
                            emailAdd.style.border = "2px solid red";
                            emailAdd.reportValidity();
                        }
                        else if(res[0] == "f")
                        {
                            var emailAdd = document.getElementById("emailAdd");
                            emailAdd.setCustomValidity("");
                            emailAdd.style.border = "";
                            document.getElementById("requiredElement").style.display = "none";
                        }
                    }
                }
                else if(value == "" || value2 == "")
                {
                    var emailAdd = document.getElementById("emailAdd");
                    emailAdd.setCustomValidity("");
                    emailAdd.style.border = "";
                    document.getElementById("requiredElement").style.display = "none";
                }
            }, 200);
        })

        $('#interview-date').attr('min',today);
        $('#client').on('change', function() {
        $('.demand').prop('required', false);
        $('.demand').removeAttr('data-class');
        $('.demand').val('');
        target=$(this).find(":selected").attr("data-target");
        var id=$(this).attr("id");
        $("div[id^='"+id+"']").hide();
        $("#"+id+"-"+target).show();
        $("[name="+target+"]").prop('required', true);
        $("[name="+target+"]").attr('data-class', 'search-class');
        });

        $('.status').on('change', function() {
            var action = $(this).val();
            const regexp = new RegExp('^(11|09|10|08)');
            const regexp2 = new RegExp('^10');
            if(action != "" && regexp.test(action))
            {
                $("#selection-data").show();
                $("#submissionDate").attr('max', today);
                $("#plannedDOJ").attr('required', true);
                $("#selectionCTC").attr('required', true);
                $("#selectionDate").attr('required', true);
                if(!regexp2.test(action))
                {
                    $("#actualDOJForm").hide();
                    $("#actualDOJ").attr('required', false);
                    $("#actualDOJ").removeClass('required');
                }
                else
                {
                    $("#actualDOJForm").show();
                    $("#actualDOJ").attr('required', true);
                    $("#actualDOJ").addClass('required');
                }
            }
            else if(action == "" || !regexp.test(action))
            {
                $("#selection-data").hide();
                $('#plannedDOJ').val('');
                $('#actualDOJ').val('');
                $('#selectionCTC').val('');
                $('#selectionDate').val('');
                $('#exitDate').val('');
                $("#plannedDOJ").attr('required', false);
                $("#selectionCTC").attr('required', false);
                $("#selectionDate").attr('required', false);
            }    
        })
        
        $('#interview-time').on('change',function() {
        });

        $('select#demand').on('change', function() {
            var demandID = $(this).val();
            var demands = <?php echo json_encode($demands)?>;
            for(var key in demands)
            {
                if(demands[key].DEMAND_ID == demandID)
                {
                    $("[name=location]").val(demands[key].LOCATION).change();
                }
            }
        })

        $("#selectionDate").on('change', function() {
            var selectDate = $(this).val();
            $("#plannedDOJ").attr(min, selectDate);
            $("#exitDate").attr(min, selectDate);
        })

        $("#plannedDOJ").on('change', function() {
            var plannedval = $(this).val();
            $("#actualDOJ").attr(min, plannedval);
        })
        
        $("#addForm").submit(function(e){
            e.preventDefault();
            var params = new FormData($("#addForm")[0]);
            $("#successDisplay").hide();
            $("#errorDisplay").hide();      
            $("#addModal").modal('hide');
            $("#addForm")[0].reset();
            $.ajax({
                type: "POST",
                url: '<?php echo base_url('Candidates/AddCandidate')?>',
                data: params,
                processData: false,
                contentType: false,
                success: function(data)
                {
                    var str = JSON.parse(data);
                    if(str[0].valueOf() === "success".valueOf())
                    {
                        $("#successDisplay").html('Candidate Details Updated Successfully.');
                        $("#successDisplay").show();
                        confirm("Candidate Details Updated Successfully.");
                        document.getElementById("successDisplay").scrollIntoView({behavior: 'smooth'});
                        var rows = str[3];
                        for(var key in rows){
                            $("#candidates").DataTable().row.add( rows[key] ).draw();
                        }
                    }
                    else 
                    {
                        $("#errorDisplay").html(str[0]);
                        $("#errorDisplay").show();
                        alert(str[0]);
                        document.getElementById("errorDisplay").scrollIntoView({behavior: 'smooth'});
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
        });
        
        $('#candidates tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = $("#candidates").DataTable().row(tr);
            var data = row.data();
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                cv_location = data["CV_LOCATION"];
                console.log(cv_location);
                row.child(loader).show();
                row.child(format(cv_location)).show();
                tr.addClass('shown');
            }
        });

        function loader() {
            return (
                '<div class="d-flex justify-content-left spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
            );
        }
        function format(d) {
            // `d` is the original data object for the row
            if(d != undefined && d!== "")
            return (
                '<div class="form-group inline-block" style="text-align: left;"><h4 class="col">Download The CV With The Button Below:</h4><button style="width: auto; display: inline-block;" type="button" class="btn btn-outline-dark btn-sm" data-id="'+d+'" name="cvView2" id="cvView2"><i class="bi bi-download"></i> Download</button></div>'
            )
            else 
            return (
                '<h4>No CV Available</h4>'
            )
        }

        $("#editModal").on("hidden.bs.modal", function () {
            $("#editForm")[0].reset();
        });

        /* Delete Form On Submit */
        $("#deleteForm").submit(function(e){
            e.preventDefault();
            var params = new FormData($("#deleteForm")[0]);
            $("#successDisplay").hide();
            $("#errorDisplay").hide();
            $("#deleteModal").modal('hide');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url('Candidates/DeleteCandidate')?>',
                data: params,
                processData: false,
                contentType: false,
                success: function(result)
                {
                    var str = JSON.parse(result);
                    if(str[0].valueOf() === "success".valueOf())
                    {
                        $("#successDisplay").html(str[1]);
                        confirm(str[1]);
                        $("#successDisplay").show();
                        document.getElementById("successDisplay").scrollIntoView({behavior: 'smooth'});
                        var deleteRow = $("#rowID").val();
                        $("#candidates").DataTable().cell(deleteRow, 0).row().remove().draw();
                        console.log(str[2]);
                    }
                    else
                    {
                        $("#errorDisplay").html(str[1]);
                        confirm(str[1]);
                        $("#errorDisplay").show();
                        document.getElementById("errorDisplay").scrollIntoView({behavior: 'smooth'});
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

        $("#editForm").submit(function(e){
            e.preventDefault();
            var params = new FormData($("#editForm")[0]);
            $("#successDisplay").hide();
            $("#errorDisplay").hide();
            $("#editModal").modal('hide');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url('Candidates/EditCandidate')?>',
                data: params,
                processData: false,
                contentType: false,
                success: function(result)
                {
                    var str = JSON.parse(result);
                    if(str[0].valueOf() === "success".valueOf())
                    {
                        $("#successDisplay").html('Candidate Details Updated Successfully.');
                        confirm("Candidate Details Updated Successfully.");
                        $("#successDisplay").show();
                        document.getElementById("successDisplay").scrollIntoView({behavior: 'smooth'});
                    }
                    else
                    {
                        $("#errorDisplay").html(str[0]);
                        confirm(str[0]);
                        $("#errorDisplay").show();
                        document.getElementById("errorDisplay").scrollIntoView({behavior: 'smooth'});
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
        });

        /* CV Download */
        $("#candidates tbody").on('click', '#cvView2', function() {
            var location = $(this).data('id');
            var httpRequest = new XMLHttpRequest();
            var params = 'cvLocation='+location;
            var res;
            httpRequest.open('GET','<?php echo base_url('Candidates/cvDownload')?>'+"?"+params, true);
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

        /* Delete Button Modal Start */
        $("#candidates tbody").on('click', '.deleteButton',function(){
            var candidateID = $(this).data('id');
            var candidateName = $(this).data('name');
            var tr = $(this).closest('tr');
            var rowID = tr.index();
            $("#candidate_id3").val(candidateID).trigger('change');
            $("#candidate_name3").val(candidateName).trigger('change');
            $("#rowID").val(rowID).trigger('change');
        })

        /* Edit Button Modal Start */
        $("#candidates tbody").on('click', '.editButton',function(){
            var candidateID = $(this).data('id');
            $("#candidateID2").val(candidateID);
            var httpRequest = new XMLHttpRequest();
            var params = "candidate_id="+candidateID;
            var res;
            httpRequest.open('GET','<?php echo base_url('Candidates/GetCandidate')?>'+"?"+params, true);
            httpRequest.send();
            httpRequest.onload = function() {
                $("#editForm")[0].reset();
                res = JSON.parse(httpRequest.responseText);
                var planned, actual;
                var candidate_name = res[0].CANDIDATE_NAME;
                var demand = res[0].DEMAND_ID;
                var jobTitle = res[1][0].JOB_TITLE;
                var status = res[0].RECRUITMENT_STATUS;
                var action = <?php echo json_encode($status);?>;
                var phno = JSON.parse(res[0].PHONE_NO);
                var email = res[0].EMAIL_ADDRESS;
                var org = res[0].ORGANISATION;
                var exp = res[0].TOTAL_EXPERIENCE;
                var cctc = res[0].CCTC_LPA;
                var ectc = res[0].ECTC_LPA;
                var dateTime = res[0].INTERVIEW_DATE;
                if(dateTime)
                {
                    var split = dateTime.split(' ');
                }
                var dateTime2 = res[0].SUBMISSION_DATE;
                if(dateTime2)
                {
                    var split2 = dateTime2.split(' ');
                }
                var notice = res[0].NOTICE_PERIOD_DAYS;
                var location = res[0].WORK_LOCATION;
                var client = res[1][0].CLIENT_ID;
                var c_name = res[1][0].CLIENT_NAME;
                if(res[0].PLANED_DOJ != "0000-00-00")
                    var planned = res[0].PLANNED_DOJ;
                if(res[0].ACTUAL_DOJ != "0000-00-00");
                    var actual = res[0].ACTUAL_DOJ;
                if(res[0].SELECTION_DATE != "0000-00-00")
                    var selectionDate = res[0].SELECTION_DATE;
                if(res[0].SELECTION_CTC != "")
                    var selectionCTC = res[0].SELECTION_CTC;
                if(res[0].EXIT_DATE != "0000-00-00")
                    var exitDate = res[0].EXIT_DATE;
                $(".form-group #client_id2").val(client).change();
                $(".form-group #client_id2").css('pointer-events','none');
                $("#candidateID2").val(candidateID);
                $("#demand2").val(demand).trigger('change');
                $(".form-group #status2").val(status).change();
                const regexp = new RegExp('^(11|09|10|08)');
                if(regexp.test(status.toLowerCase()))
                {
                    $("#selectionCTC2").val(selectionCTC);
                    $("#selectionDate2").val(selectionDate);
                    $("#plannedDOJ2").val(planned);
                    $("#actualDOJ2").val(actual);
                    $("#exitDate2").val(exitDate);
                }
                $(".form-group #j_title2").val( jobTitle );
                $(".form-group #j_title2").css('pointer-events','none');
                $(".form-group #organisation2").val( org );
                $(".form-group #edit_phno_1").val(phno[0]);
                $(".form-group #edit_phno_2").val("");
                $(".form-group #edit_phno_2").val(phno[1]);
                $(".form-group #experience2").val(exp);
                $(".form-group #candidate_name2").val(candidate_name);
                $(".form-group #c_name2").val( c_name );
                $(".form-group #cctc2").val( cctc );
                if(split)
                {
                    $(".form-group #interview-date2").val("");
                    $(".form-group #interview-time2").val("");
                    $(".form-group #interview-date2").val(split[0]);
                    $(".form-group #interview-time2").val(split[1]);
                }
                $(".form-group #ectc2").val( ectc );
                $(".form-group #NP2").val( notice );
                $(".form-group #emailAdd2").val(email);
                $(".form-group #location2").val(location).change();
            }    
        })
        $('.status2').on('change', function() {
            var action = $(this).val();
            const regexp = new RegExp('^(11|09|10|08)');
            const regexp2 = new RegExp('^10');
            if(action != "" && regexp.test(action))
            {
                $("#selection-data2").show();
                $("#submissionDate2").attr('max', today);
                $("#plannedDOJ2").attr('required', true);
                $("#selectionCTC2").attr('required', true);
                $("#selectionDate2").attr('required', true);
                if(!regexp2.test(action))
                {
                    $("#actualDOJForm2").hide();
                    $("#actualDOJ2").attr('required', false);
                    $("#actualDOJ2").removeClass('required');
                }
                else
                {
                    $("#actualDOJForm2").show();
                    $("#actualDOJ2").attr('required', true);
                    $("#actualDOJ2").addClass('required');
                }
            }
            else if(action == "" || !regexp.test(action))
            {
                $("#selection-data2").hide();
                $('#plannedDOJ2').val('');
                $('#actualDOJ2').val('');
                $('#selectionCTC2').val('');
                $('#selectionDate2').val('');
                $('#exitDate2').val('');
                $("#plannedDOJ2").attr('required', false);
                $("#selectionCTC2").attr('required', false);
                $("#selectionDate2").attr('required', false);
            }    
        })

        $("#plannedDOJ2").on('change', function() {
            var plannedval = $(this).val();
            $("#actualDOJ2").attr(min, plannedval);
        })

        $("#selectionDate2").on('change', function(){
            var selectDate = $(this).val();
            $("#plannedDOJ2").attr(min, selectDate);
            $("#exitDate2").attr(min, selectDate);
        })
        /* Edit Button Modal End */

        /* Search Field */
        $(".dropdown-item").click(debound(filter_table, 500))

        $(".statuses").on('click', function(){
            $("#reset-button").attr('disabled',false);
            var id = $(this).data('id');
            $('.statuses').attr('disabled', false);
            $(this).attr('disabled', true);
            debound(filter_table2(id), 500);
        });

        function filter_table2(e)
        {
            var id = e;
            var category = id.split(".");
            console.log(category[0]);
            var val = $.fn.dataTable.util.escapeRegex(category[0]);
            $("#candidates").DataTable().columns(7).search('^'+val, true, false).draw();
        }
        
        function filter_table(e) {
        const rows = document.querySelectorAll('tbody tr')
        rows.forEach(row => {
            e.target.value = e.target.value.toLowerCase();
            row.style.display = (row.innerText.toLowerCase().includes(e.target.value)) ? '' : 'none'
        })
        }

        function debound(func, timeout) {
            let timer
            return (...args) => {
                if (!timer) {
                func.apply(this, args);
                }
                clearTimeout(timer)
                timer = setTimeout(() => {
                func.apply(this, args)
                timer = undefined
                }, timeout)
            }
        }
        /* Search Field End */
    });

</script>