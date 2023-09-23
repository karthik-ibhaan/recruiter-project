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

            <center><h2>ASSIGNED DEMANDS</h2>
            <h4>DEMANDS ASSIGNED FOR DATE: <?php echo date("d F Y")?></h4></center>
            <center class="row">
                <span class="col">
                    <h4>To Check Previous Demands, Click Here:
                    <button type="button" class="btn btn-primary mb-2" id="previousDemands" name="previousDemands">Previously Worked Demands</button></h4>
                </span>
                <span class="col">
                    <h4>To Self-Assign Demands, Click Here:
                    <button type="button" class="btn btn-primary mb-2" id="selfAssignDemands" name="selfAssignDemands">Self-Assign Demands</button></h4>
                </span>
            </center>
            <br>
            <div class="text-left" style="display: block">
                <table style="width:100%;" class="table table-striped table-bordered" id="demands" name="demands">
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
                <br><br><br>
                <div class="text-left demandsDisplay" style="display: block;">
                </div>
                <br><br><br>
            </div>

            <form id="scheduleForm" method="post" enctype="multipart/form-data">
                <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Schedule IG Interview Modal</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <?= csrf_field() ?>
                                <div class="row form-group">
                                    <div class="row">
                                        <input type="hidden" name="scheduleCandidateID" id="scheduleCandidateID">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col">
                                        <label>Candidate ID</label>
                                        <input type="text" name="candidateIDDisplay" id="candidateIDDisplay" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col">
                                        <label class="required"><b>INTERVIEW DATE</b></label>
                                        <input type="date" class="form-control" name="scheduleInterviewDate" id="scheduleInterviewDate">
                                    </div>
                                    <div class="form-group col">
                                        <label class="required"><b>INTERVIEW TIME</b></label>
                                        <input type="time" class="form-control" name="scheduleInterviewTime" id="scheduleInterviewTime">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col">
                                        <label class="required"><b>INTERVIEWER</b></label>
                                        <select name="scheduleInterviewer" id="scheduleInterviewer" class="form-control">
                                        </select>
                                    </div>
                                    <div class="form-group col">
                                        <label class="required"><b>APPROVER</b></label>
                                        <select name="scheduleApprover" id="scheduleApprover" class="form-control">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Schedule Interview</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form id="assignForm" method="post" enctype="multipart/form-data">
                <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Self-Assign Modal</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <?= csrf_field() ?>
                                <div class="row form-group">
                                    <div class="col">
                                        <label class="required"><b>CLIENT</b></label>
                                        <select name="assign_client" id="assign_client" class="form-control" required>

                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="required"><b>DEMANDS</b></label>
                                        <select name="assign_demand" id="assign_demand" class="form-control" required>

                                        </select>
                                    </div>
                                </div>
                                <br>
                                <span>WOULD YOU LIKE TO SELF-ASSIGN THIS DEMAND?</span>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Assign Demand</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
                                <input type="hidden" class="form-control" name="demand" id="demand" required>
                            </div>
                            <div class="row">
                                <div class="form-group col">                                
                                    <label data-bs-toggle="tooltip" data-bs-placement="right" title="The Demand That The Candidate Details Are Entered For.">Job Title</label>
                                    <input type="text" class="form-control" name="demand_placeholder" id="demand_placeholder" disabled>
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
                                    <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="The Recruitment Status of the Candidate">Recruitment Status</label>
                                    <select name="status" id="status" class="status form-control" required>
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
                            <span id="selection-data" style="display:none">
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
                                    <div class="form-group col" id="actualDOJForm">
                                        <label id="actualDOJlabel">Actual Date of Joining</label>
                                        <input type="date" class="form-control" name="actualDOJ" id="actualDOJ">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col">
                                        <label>Exit Date</label>
                                        <input type="date" class="form-control" name="exitDate" id="exitDate">
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
        </div>
    <?php include('Footers/footer.php')?>
    </body>
</html>
<style>
    .required:after 
    {
      content:" *";
      color: red;
    }

    .btn-group {
        margin-left: 0;
    }
    .btn-group {
        margin-left: 0;
    }

    /* dataTables CSS modification & positioning */
    table.dataTable thead .sorting:before,
    table.dataTable thead .sorting_asc:before,
    table.dataTable thead .sorting_desc:before,
    table.dataTable thead .sorting_asc_disabled:before,
    table.dataTable thead .sorting_desc_disabled:before {
    right: 0 !important;
    content: "" !important;
    }
    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_desc:after,
    table.dataTable thead .sorting_asc_disabled:after,
    table.dataTable thead .sorting_desc_disabled:after {
    right: 0 !important;
    content: "" !important;
    }

    table.dataTable thead th {
        position: relative;
        background-image: none !important;
    }
  
    table.dataTable thead th.sorting:after,
    table.dataTable thead th.sorting_asc:after,
    table.dataTable thead th.sorting_desc:after {
        position: absolute !important;
        top: 12px !important;
        right: 8px !important;
        display: block !important;
    }
    table.dataTable thead th.sorting:after {
        content: url('data:image/svg+xml; utf8, <svg xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" width="16" height="16" clip-rule="evenodd" viewBox="0 0 322 511.21"><path fill-rule="nonzero" d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z"/></svg>') !important;
        color: #000000 !important;
        font-size: 1em !important;
        padding-top: 0.12em !important;
        opacity: 0.2 !important;
    }
    table.dataTable thead th.sorting_asc:after {
        color: #000000 !important;
        content: url('data:image/svg+xml; utf8, <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up-fill" viewBox="0 0 16 16"><path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/></svg>') !important;
        vertical-align: text-top;
        opacity: 0.45 !important;
    }
    table.dataTable thead th.sorting_desc:after {
        color: #000000 !important;
        content: url('data:image/svg+xml; utf8, <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>') !important;
        vertical-align: text-bottom;
        opacity: 0.45 !important;
    }
</style>
<script>
    $(document).ready(function() {
        /* Printing DataTable */

        var fieldNames = <?php echo json_encode($fieldNames)?>;
        var fieldNames = fieldNames;
        var columns = [];
        columns.push({ data: null, title: "DATA", orderable: false, className: 'dt-control' , defaultContent: "&nbsp;"});
        for(var field in fieldNames)
        {
            if(fieldNames[field].toLowerCase() == "primary_skill")
            {
                columns.push({ data: fieldNames[field] , title: "PRIMARY SKILL", className: "text-center justify-content-center"});
            }
            else if(fieldNames[field].toLowerCase() == "secondary_skill")
            {
                columns.push({ data: fieldNames[field] , title: "SECONDARY SKILL", className: "text-center justify-content-center"});
            }
            else if(fieldNames[field].toLowerCase() == "full_name")
            {
                columns.push({ data: fieldNames[field] , title: "RECRUITER", className: "text-center justify-content-center col"});
            }
            else
            {
                columns.push({ data: fieldNames[field] , title: fieldNames[field].replace("_"," "), className: "text-center justify-content-center col"});
            }
        }
        columns.push({ data: null, title: "ADD",
            render: function(data) {
                var btn = '<button type="button" class="btn btn-success mb-2 addButton" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-file-plus-fill"></i></button>';
                return btn;
            },
            className: "text-center justify-content-center"
        });

        var demands = <?php echo json_encode($demands)?>;
        var table = $('#demands').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', {extend: 'csv', filename: function() { return getFileName();}}, {extend: 'excel', filename: function() { return getFileName();}}, 'print', {text: 'Collapse All',
                    className: 'disable-button close-child-rows',
                    action: function ( e, dt, node, config ) {
                    lastPage = $('#demands').DataTable().page();
                    var table = $('#demands').DataTable();

                    table.rows().every(function () {
                        var row = this;
                        if (row.child.isShown()) {
                        row.child.hide();
                        $(this.node()).removeClass('shown');
                        // disable close child rows button
                        $(".close-child-rows").addClass("disable-button");
                        }
                    })
                }
            }
        ],
            columns: columns,
            data: demands,
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
        $(window).resize();
        
        function getFileName(){
            var date = new Date();
            
            return "Demands As of: "+" - "+date.getDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear();
        }
        
        $("#demands").DataTable().columns.adjust().draw();
        $("#demands")[0].tBodies[0].className = "container";


        $('#demands tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = $("#demands").DataTable().row(tr);
            var data = row.data();
            var index = row.index();
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                if($("#child_details"+index).DataTable().destroy())
                {
                    console.log("DESTROYED")
                }
                $(window).resize();
                tr.removeClass('shown');
            } else {
                // Open this row
                var httpRequest = new XMLHttpRequest();
                if(data)
                {
                    var date = new Date();
                    var sub_date = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+(date.getDate());
                    var params = "demand_id="+data["DEMAND_ID"]+"&submission_date="+sub_date;
                    var res;
                    httpRequest.open('GET','<?php echo base_url('AssignedDemands/CandidateDetails')?>'+"?"+params, true);
                    httpRequest.send();
                    httpRequest.onload = function() {
                        res = JSON.parse(httpRequest.responseText);
                        if(res[0] && res[1])
                        {
                            row.child(
                            '<div style="width: 15%;">'+ 
                            '<table class="child_table table table-bordered table-striped display nowrap" id = "child_details' + index + '">'+
                            '<thead></thead><tbody>' +
                            '</tbody></table>' + '<button type="button" class="btn btn-sm btn-dark exportButton" id = "exportBtn-'+ index + '" name = "exportBtn-'+ index + '" data-id = "'+ index + '" data-client="' + data['CLIENT_NAME'] +'" data-demand = "' + data['DEMAND_ID'] + '" id="submit-'+ index + '">Export to Zip</button>'+'<button type="button" class="btn btn-sm btn-dark exportButton2" id = "exportAllBtn" name = "exportAllBtn" data-id = "'+ index + '" data-client="' + data['CLIENT_NAME'] +'" data-demand = "' + data['DEMAND_ID'] + '">Export All to Zip</button>'+'</div>').show();
                            var childData = res[0];
                            var childFieldNames = res[1];
                            var btn = "exportBtn-" + index;
                            $("#demands tbody #"+btn).attr('disabled', true);
                            var childColumns = [];
                            childColumns.push({ data: null, title: "EXPORT", 
                                render: function(data) {
                                    var btn = '<input type="checkbox" class="mt-0 form-check-input" id="export-'+ index + '" name="export-'+ index + '[]" value="' + data.CANDIDATE_ID + '">';
                                    return btn;
                                },
                                className: "text-center justify-content-center"
                            });
                            for(var field in childFieldNames)
                            {
                                childColumns.push({ data: childFieldNames[field] , title: childFieldNames[field].replace("_"," ")});
                            }
                            childColumns.push({ data: null, title: "EDITS", 
                                render: function(data) {
                                    var btn = '<div class="btn-group" role="group"><button type="button" class="btn btn-dark btn-sm editButton" data-bs-toggle="modal" data-id="'+data.CANDIDATE_ID+'" data-bs-target="#editModal" name="editButton" id="editButton"><i class="bi bi-pen"></i></button><button type="button" class="btn btn-dark btn-sm deleteButton" data-bs-toggle="modal" data-id="'+data.CANDIDATE_ID+'" data-name="'+data.CANDIDATE_NAME+'" data-bs-target="#deleteModal"><i class="bi bi-trash"></i></button></div>';
                                    return btn;
                                },
                            });
                            childColumns.push({ data: null, title: "IG INTERVIEW",
                                render: function(data) {
                                    const regexp = new RegExp('^(11|10|09|08|10|06|02|03|05)');
                                    if(!regexp.test(data.RECRUITMENT_STATUS))
                                    {
                                        var btn = '<button type="button" class="btn btn-dark btn-sm scheduleButton" data-id="'+data.CANDIDATE_ID+'">SCHEDULE</button>';
                                        return btn;
                                    }
                                    else
                                    {
                                        var btn = '';
                                        return btn;
                                    }
                                },
                            });
                            var childTable = $('#child_details' + index).DataTable({
                                columns: childColumns,
                                data: childData,
                                destroy: true,
                                scrollX: true,
                                scrollCollapse: true,
                                fixedColumns:   {
                                    right: 2,
                                    left: 0
                                }
                            });
                            $(window).resize();
                            tr.addClass('shown');
                        }
                        // if(res[0] == "t")
                        // {
                        //     document.getElementById("requiredElement").textContent = "REQUIRED";
                        //     document.getElementById("requiredElement").style.display = "block";
                        //     var emailAdd = document.getElementById("emailAdd");
                        //     emailAdd.setCustomValidity("This Candidate Has Already Been Sent for this Job Title");
                        //     emailAdd.style.border = "2px solid red";
                        //     emailAdd.reportValidity();
                        // }
                        // else if(res[0] == "f")
                        // {
                        //     var emailAdd = document.getElementById("emailAdd");
                        //     emailAdd.setCustomValidity("");
                        //     emailAdd.style.border = "";
                        //     document.getElementById("requiredElement").style.display = "none";
                        // }
                    }
                }
            }
        });

        function loader() {
            return (
                '<div class="d-flex justify-content-left spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
            );
        }
        
        $(document).on('click', '#demands tbody .form-check-input', function() {
            formInputCheck($(this));
        })

        $(document).on('click', '#demands2 tbody .form-check-input', function() {
            var id = $(this).attr('id');
            var index = id.split('-');
            var btn = "exportBtn2-" + index[1];
            if($("div #export2-" + index[1] + ":checked").length >= 1)
            {
                $("#demands2 tbody #"+btn).attr('disabled', false);
            }
            else
            {
                $("#demands2 tbody #"+btn).attr('disabled', true);
            }
        })

        function formInputCheck(val) {
            var id = val.attr('id');
            var index = id.split('-');
            var btn = "exportBtn-" + index[1];
            if($("div #export-" + index[1] + ":checked").length >= 1)
            {
                $("#demands tbody #"+btn).attr('disabled', false);
            }
            else
            {
                $("#demands tbody #"+btn).attr('disabled', true);
            }
        }
        
        $("#demands tbody").on('click', '.exportButton', function() {
            var $export2 = new String("export");
            exportZip($(this), $export2);
        })

        $(document).on('click', '#demands2 tbody .exportButton', function() {
            var $export2 = new String("export2");
            exportZip($(this), $export2);
        })

        function exportZip(val, origin)
        {
            var $export2 = origin;
            var demand = val.data('demand');
            var client = val.data('client');
            var index = val.data('id');
            var checkboxValues = [];
            var params = "";
            $("div #"+ $export2 + '-'+ index+":checked").map(function() {
                params += "candidate_ids[]=" + $(this).val() + "&";
            })
            params = params.substring(0, params.length -1);
            var httpRequest = new XMLHttpRequest();
            var res;
            httpRequest.open('GET','<?php echo base_url('AssignedDemands/CVDownloads')?>'+"?"+params, true);
            httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            httpRequest.responseType = 'blob';
            httpRequest.send();
            httpRequest.onload = function() {
                res = httpRequest.response;
                var fileName = client + " - " + demand + ".zip";
                var link = document.createElement('a');
                link.href=window.URL.createObjectURL(res);
                link.download=fileName;
                link.click();
            };
        }
        function format(d) {
            // `d` is the original data object for the row
            var res = 0;
            for(var i=0; i<d.length;i++)
            {
                res = res + "<p>" + d[i] + "</p>";
            }
            return d;
        }
        
        /* JD Download */
        $("#demands tbody").on('click', '#jdView2', function() {
            var location = $(this).data('id');
            var httpRequest = new XMLHttpRequest();
            var params = 'jdLocation='+location;
            var res;
            httpRequest.open('GET','<?php echo base_url('Demands/JDDownload')?>'+"?"+params, true);
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

        $(document).on('click','#assignButton', function(){
            assign($(this));
        })
        function assign(val)
        {
            $("#assignMultipleSelect").empty();
            $("#recruiterMultipleSelect").empty();
            var xhr = new XMLHttpRequest();
            var res;
            xhr.open('GET','<?php echo base_url('YourDemands/DemandsData')?>', true);
            xhr.send();
            xhr.onload = function() {
                var res = JSON.parse(xhr.responseText);
                var demands = res[0];
                var users = res[1];
                for(var i = 0; i<demands.length;i++)
                {
                    var str = demands[i].DEMAND_ID + "-" + demands[i].JOB_TITLE;
                    $("#assignMultipleSelect").append("<option value='"+demands[i].DEMAND_ID+"'>"+ str +"</option>");
                }
                for(var j = 0; j<users.length;j++)
                {
                    var str2 = users[j].FULL_NAME;
                    $("#recruiterMultipleSelect").append("<option value='"+users[j].USER_ID+"'>"+ str2 +"</option>");
                }
            }
        }
    })

    function disablebutton(){
        $('#addsubmit').attr("disabled", true);
    }

    function enablebutton(){
        $('#addsubmit').attr("disabled", false);
    }

    var value;
    var timeout = null;
    var today = new Date().toISOString().split('T')[0];


    $(document).on('click', '#selfAssignDemands', function(){
        var httpRequest = new XMLHttpRequest();
        var params = "";
        var res;
        httpRequest.open('GET', '<?php echo base_url('AssignedDemands/GetClients')?>'+"?"+params, true);
        httpRequest.send();
        httpRequest.onload = function() {
            res = JSON.parse(httpRequest.responseText);
            if(res !== "")
            {
                $("#assign_client").html("");
                $('<option value=""> - SELECT - </option>').appendTo("#assign_client");
                for(var i=0;i<res.length;i++)
                {
                    $('<option value="'+ res[i].CLIENT_ID+'">'+res[i].CLIENT_NAME+'</option>').appendTo("#assign_client");
                }

                $("#assignModal").modal('show');
            }
        }
    })

    $(document).on('change', '#assign_client', function() {
        const val = $(this).val();
        if(val != "")
        {
            var httpRequest = new XMLHttpRequest();
            var params = "client_id="+val;
            var res;
            httpRequest.open('GET', '<?php echo base_url('AssignedDemands/GetDemands')?>'+"?"+params, true);
            httpRequest.send();
            httpRequest.onload = function() {
                res = JSON.parse(httpRequest.responseText);
                $("#assign_demand").html("");
                $('<option value=""> - SELECT - </option>').appendTo("#assign_demand");
                for(var i=0;i<res.length;i++)
                {
                    $('<option value="'+ res[i].DEMAND_ID+'">'+res[i].DEMAND_ID+'-'+res[i].JD_ID+'-'+res[i].JOB_TITLE+'</option>').appendTo("#assign_demand");
                }

                $("#assignModal").modal('show');
            }            
        }
        else
        if(val === "")
        {
            $("#assign_demand").html("");
        }
    })

    $("#assignForm").submit(function(e){
        e.preventDefault();
        var params = new FormData($("#assignForm")[0]);
        $("#successDisplay").hide();
        $("#errorDisplay").hide();      
        $("#assignModal").modal('hide');
        $("#assignForm")[0].reset();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('AssignedDemands/SelfAssignment')?>',
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
    $(document).on('click', '#previousDemands', function() {
        var httpRequest = new XMLHttpRequest();
        var params = "";
        var res;
        httpRequest.open('GET','<?php echo base_url('AssignedDemands/PreviousDemands')?>'+"?"+params, true);
        httpRequest.send();
        httpRequest.onload = function() {
            $("#previousDemands").prop('disabled', true);
            var res = JSON.parse(httpRequest.responseText);
            var tbl = '<center><h4>PREVIOUSLY WORKED DEMANDS</h4></center><br><table class="table table-bordered table-striped display nowrap" id = "demands2" name="demands2">'+'<thead></thead><tbody>'+'</tbody><tfoot></tfoot></table>';
            var table = $(".demandsDisplay").append(tbl);
            var fieldNames = res[1];
            var fieldNames = fieldNames;
            var columns2 = [];
            columns2.push({ data: null, title: "DATA", orderable: false, className: 'dt-control' , defaultContent: "&nbsp;"});
            for(var field in fieldNames)
            {
                if(fieldNames[field].toLowerCase() == "primary_skill")
                {
                    columns2.push({ data: fieldNames[field] , title: "PRIMARY SKILL", className: "text-center justify-content-center"});
                }
                else if(fieldNames[field].toLowerCase() == "secondary_skill")
                {
                    columns2.push({ data: fieldNames[field] , title: "SECONDARY SKILL", className: "text-center justify-content-center"});
                }
                else if(fieldNames[field].toLowerCase() == "full_name")
                {
                    columns2.push({ data: fieldNames[field] , title: "RECRUITER", className: "text-center justify-content-center col"});
                }
                else
                {
                    columns2.push({ data: fieldNames[field] , title: fieldNames[field].replace("_"," "), className: "text-center justify-content-center col"});
                }
            }

            var demands2 = res[0];
            var table = $('#demands2').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', {extend: 'csv', filename: function() { return getFileName2();}}, {extend: 'excel', filename: function() { return getFileName2();}}, 'print', {text: 'Collapse All',
                        className: 'disable-button close-child-rows',
                        action: function ( e, dt, node, config ) {
                        lastPage = $('#demands2').DataTable().page();
                        var table = $('#demands2').DataTable();
    
                        table.rows().every(function () {
                            var row = this;
                            if (row.child.isShown()) {
                            row.child.hide();
                            $(this.node()).removeClass('shown');
                            // disable close child rows button
                            $(".close-child-rows").addClass("disable-button");
                            }
                        })
                    }
                }],
                columns: columns2,
                data: demands2,
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
            });
            document.getElementById("demands2").scrollIntoView({behavior: 'smooth'});
            $(window).resize();
            
            function getFileName2(){
                var date = new Date();                
                return "Previously Assigned Demands As On: "+" - "+date.getDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear();
            }
        }
    })

    $("#edit_phno_1").on('input keyup keydown', function(){
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

    $("#edit_phno_2").on('input keyup keydown', function(){
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            var reg = /[6789]\d{9,9}/;
            var value = $("#edit_phno_2").val();
            if(value != "")
            {
                if(reg.test(value))
                {
                    var phno1 = document.getElementById("edit_phno_2");
                    phno1.style.border = "";
                    phno1.setCustomValidity("");                    }
                else
                {
                    var phno1 = document.getElementById("edit_phno_2");
                    phno1.style.border = "2px solid red";
                    phno1.setCustomValidity("Phone Numbers Can Only be 10 Digits.");
                    phno1.reportValidity();
                }
                if(value.length != 10)
                {
                    var phno1 = document.getElementById("edit_phno_2");
                    phno1.style.border = "2px solid red";
                    phno1.setCustomValidity("Phone Numbers Can Only be 10 Digits.");
                    phno1.reportValidity();
                }
            }
            else if(value == "")
            {
                var phno1 = document.getElementById("edit_phno_2");
                phno1.style.border = "";
                phno1.setCustomValidity("");                    
            }
        },500)
    })

    $("#phno_1").on('input keyup keydown', function(){
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
    $("#phno_2").on('input keyup keydown', function(){
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

    $(document).on('click', '#demands tbody .addButton', function(e) {
        e.preventDefault();
        var tr = $(this).closest('tr');
        var row = $("#demands").DataTable().row(tr);
        var data = row.data();
        $("#addModal #demand").val(data.DEMAND_ID).trigger('change');
        $("#addModal [name='location']").val(data.LOCATION);
        $("#addModal #demand_placeholder").val(data.CLIENT_NAME + " - " + data.JOB_TITLE);
        $("#addModal").modal('hide');
        $("#addModal").modal('show');
    })
    
    $("#addModal").on('hidden.bs.modal', function() {

    })
    
    $("#emailAdd").on('input keyup keydown', function() {
        var search = $("#addModal #demand").val();
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

    $("#emailAdd2").on('input keyup keydown', function() {
        var search = $("#editModal #demand2").val();
        var value = search;
        var value2 = $("#editModal #emailAdd2").val();
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            if(value != "" && value2 != "")
            {
                var httpRequest = new XMLHttpRequest();
                var params = "demand_id="+value+"&email="+value2+"&candidate_id="+$("#editModal #candidateID2").val();
                var res;
                httpRequest.open('GET','<?php echo base_url('Candidates/CheckExisting2')?>'+"?"+params, true);
                httpRequest.send();
                httpRequest.onload = function() {
                    res = JSON.parse(httpRequest.responseText);
                    if(res[0] == "t")
                    {
                        var emailAdd = document.getElementById("emailAdd2");
                        emailAdd.setCustomValidity("This Candidate Has Already Been Sent for this Job Title. Please Search for Another.");
                        emailAdd.style.border = "2px solid red";
                        emailAdd.reportValidity();
                    }
                    else if(res[0] == "f")
                    {
                        var emailAdd = document.getElementById("emailAdd2");
                        emailAdd.setCustomValidity("");
                        emailAdd.style.border = "";
                    }
                }
            }
            else if(value == "" || value2 == "")
            {
                var emailAdd = document.getElementById("emailAdd2");
                emailAdd.setCustomValidity("");
                emailAdd.style.border = "";
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
            url: '<?php echo base_url('Candidates/AddCandidate2')?>',
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
                    var rows = str[3];
                    for(var key in rows){
                        $(".child_table").DataTable().row.add( rows[key] ).draw();
                    }
                }
                else 
                {
                    $("#errorDisplay").html(str[0]);
                    $("#errorDisplay").show();
                    alert(str[0]);
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

    $("#scheduleModal").on("hidden.bs.modal", function () {
        $("#scheduleInterviewer").html("");
        $("#scheduleApprover").html("");
        $("#scheduleForm")[0].reset();
    });

    $("#scheduleForm").submit(function(e){
        e.preventDefault();
        var params = new FormData($("#scheduleForm")[0]);
        $("#successDisplay").hide();
        $("#errorDisplay").hide();
        $("#scheduleModal").modal('hide');
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('AssignedDemands/ScheduleInterview')?>',
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
                }
                else
                {
                    $("#errorDisplay").html(str[1]);
                    alert(str[1]);
                    $("#errorDisplay").show();
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
        });
    });

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
                    var deleteRow = $("#rowID").val();
                    $(".child_table").DataTable().cell(deleteRow, 0).row().remove().draw();
                }
                else
                {
                    $("#errorDisplay").html(str[1]);
                    confirm(str[1]);
                    $("#errorDisplay").show();
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
                }
                else
                {
                    $("#errorDisplay").html(str[0]);
                    confirm(str[0]);
                    $("#errorDisplay").show();
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
    $(".child_table tbody").on('click', '#cvView2', function() {
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
    $(document).on('click', '.child_table tbody .deleteButton',function(){
        deleteButton($(this));
    })

    $(document).on('click', '.child_table2 tbody .deleteButton', function() {
        deleteButton($(this));
    })

    function deleteButton(del){
        var candidateID = del.data('id');
        var candidateName = del.data('name');
        var tr = del.closest('tr');
        var rowID = tr.index();
        $("#candidate_id3").val(candidateID).trigger('change');
        $("#candidate_name3").val(candidateName).trigger('change');
        $("#rowID").val(rowID).trigger('change');
    }

    /* Schedule Interview Modal Start */
    $(document).on('click', '.child_table tbody .scheduleButton', function(){
        scheduleButton($(this));
    })
    
    $(document).on('click', '.child_table2 tbody .scheduleButton', function(){
        scheduleButton($(this));
    })

    function scheduleButton(temp)
    {
        var candidateID = temp.data('id');
        $("#scheduleCandidateID").val(candidateID);
        $("#candidateIDDisplay").val(candidateID).trigger('change');
        var params = "candidate_id="+candidateID;
        var res;
        var users;
        var interviewers;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '<?php echo base_url('AssignedDemands/GetInterviewerData')?>'+"?"+params, true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            console.log(res);
            users = res[0];
            interviewers = res[1];
            $("#scheduleInterviewer").append('<option value="">-Select-</option>');
            $("#scheduleApprover").append('<option value="">-Select-</option>');
            for(var i=0;i<interviewers.length;i++)
            {
                $("#scheduleInterviewer").append('<option value="'+interviewers[i].INTERVIEWER_ID+'">'+interviewers[i].INTERVIEWER_NAME+'</option>');
            }
            for(var j=0;j<users.length;j++)
            {
                $("#scheduleApprover").append('<option value="'+users[j].USER_ID+'">'+users[j].FULL_NAME+'</option>');
            }
            $("#scheduleModal").modal('show');
        }
    }
    /* Edit Button Modal Start */
    $(document).on('click', '.child_table tbody .editButton',function(){
        editButton($(this));
    })
    $(document).on('click', '.child_table2 tbody .editButton',function(){
        editButton($(this));
    })
    function editButton(temp)
    {
        var candidateID = temp.data('id');
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
    }
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

    $(document).on('click', '#demands tbody .exportButton2', function() {
        var demand = $(this).data('demand');
        var client = $(this).data('client');
        var index = $(this).data('id');
        var checkboxValues = [];
        var params = "";
        params = "demand_id="+demand;
        var httpRequest = new XMLHttpRequest();
        var res;
        httpRequest.open('GET','<?php echo base_url('AssignedDemands/CVDownloads2')?>'+"?"+params, true);
        httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        httpRequest.responseType = 'blob';
        httpRequest.send();
        httpRequest.onload = function() {
            res = httpRequest.response;
            var fileName = client + " - " + demand + ".zip";
            var link = document.createElement('a');
            link.href=window.URL.createObjectURL(res);
            link.download=fileName;
            link.click();
        };
    })

    $(document).on('click', '#demands2 tbody .exportButton2', function() {
        var demand = $(this).data('demand');
        var client = $(this).data('client');
        var index = $(this).data('id');
        var checkboxValues = [];
        var params = "";
        params = "demand_id="+demand;
        var httpRequest = new XMLHttpRequest();
        var res;
        httpRequest.open('GET','<?php echo base_url('AssignedDemands/CVDownloads2')?>'+"?"+params, true);
        httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        httpRequest.responseType = 'blob';
        httpRequest.send();
        httpRequest.onload = function() {
            res = httpRequest.response;
            var fileName = client + " - " + demand + ".zip";
            var link = document.createElement('a');
            link.href=window.URL.createObjectURL(res);
            link.download=fileName;
            link.click();
        };
    })

    $(document).on('click', '#demands2 tbody td.dt-control', function () {
        var tr = $(this).closest('tr');
        var row = $("#demands2").DataTable().row(tr);
        var data = row.data();
        var index = row.index();
        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            if($("#child_details2"+index).DataTable().destroy())
            {
                console.log("DESTROYED")
            }
            $(window).resize();
            tr.removeClass('shown');
        } else {
            // Open this row
            var httpRequest = new XMLHttpRequest();
            if(data)
            {
                var date = new Date();
                var sub_date = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+(date.getDate());
                var params = "demand_id="+data["DEMAND_ID"]+"&submission_date="+sub_date;
                var res;
                httpRequest.open('GET','<?php echo base_url('AssignedDemands/CandidateDetails2')?>'+"?"+params, true);
                httpRequest.send();
                httpRequest.onload = function() {
                    res = JSON.parse(httpRequest.responseText);
                    if(res[0] && res[1])
                    {
                        row.child(
                        '<div style="width: 15%;">'+
                        '<table class="child_table2 table table-bordered table-striped display nowrap" id = "child_details2' + index + '">'+
                        '<thead></thead><tbody>' +
                        '</tbody></table>' + '<button type="button" class="btn btn-sm btn-dark exportButton" id = "exportBtn2-'+ index + '" name = "exportBtn2-'+ index + '" data-id = "'+ index + '" data-client="' + data['CLIENT_NAME'] +'" data-demand = "' + data['DEMAND_ID'] + '" id="submit-'+ index + '">Export to Zip</button>'+'<button type="button" class="btn btn-sm btn-dark exportButton2" id = "exportAllBtn" name = "exportAllBtn" data-id = "'+ index + '" data-client="' + data['CLIENT_NAME'] +'" data-demand = "' + data['DEMAND_ID'] + '">Export All to Zip</button>'+'</div>').show();
                        var childData = res[0];
                        var childFieldNames = res[1];
                        var btn = "exportBtn2-" + index;
                        $("#demands2 tbody #"+btn).attr('disabled', true);
                        var childColumns = [];
                        childColumns.push({ data: null, title: "EXPORT", 
                            render: function(data) {
                                var btn = '<input type="checkbox" class="mt-0 form-check-input" id="export2-'+ index + '" name="export2-'+ index + '[]" value="' + data.CANDIDATE_ID + '">';
                                return btn;
                            },
                            className: "text-center justify-content-center"
                        });
                        for(var field in childFieldNames)
                        {
                            childColumns.push({ data: childFieldNames[field] , title: childFieldNames[field].replace("_"," ")});
                        }
                        childColumns.push({ data: null, title: "EDITS",
                            render: function(data) {
                                var btn = '<div class="btn-group" role="group"><button type="button" class="btn btn-dark btn-sm editButton" data-bs-toggle="modal" data-id="'+data.CANDIDATE_ID+'" data-bs-target="#editModal" name="editButton2" id="editButton2"><i class="bi bi-pen"></i></button><button type="button" class="btn btn-dark btn-sm deleteButton" data-bs-toggle="modal" data-id="'+data.CANDIDATE_ID+'" data-name="'+data.CANDIDATE_NAME+'" data-bs-target="#deleteModal"><i class="bi bi-trash"></i></button></div>';
                                return btn;
                            },
                        });
                        childColumns.push({ data: null, title: "IG INTERVIEW",
                            render: function(data) {
                                const regexp = new RegExp('^(11|10|09|08|10|06|02|03|05)');
                                if(!regexp.test(data.RECRUITMENT_STATUS))
                                {
                                    var btn = '<button type="button" class="btn btn-dark btn-sm scheduleButton" data-id="'+data.CANDIDATE_ID+'">SCHEDULE</button>';
                                    return btn;
                                }
                                else
                                {
                                    var btn = '';
                                    return btn;
                                }
                            },
                        });
                        var childTable = $('#child_details2' + index).DataTable({
                            columns: childColumns,
                            data: childData,
                            destroy: true,
                            fixedColumns:   {
                                right: 2,
                                left: 0
                            },
                            scrollX: true,
                            scrollCollapse: true,
                        });
                        $(window).resize();
                        tr.addClass('shown');
                    }
                    // if(res[0] == "t")
                    // {
                    //     document.getElementById("requiredElement").textContent = "REQUIRED";
                    //     document.getElementById("requiredElement").style.display = "block";
                    //     var emailAdd = document.getElementById("emailAdd");
                    //     emailAdd.setCustomValidity("This Candidate Has Already Been Sent for this Job Title");
                    //     emailAdd.style.border = "2px solid red";
                    //     emailAdd.reportValidity();
                    // }
                    // else if(res[0] == "f")
                    // {
                    //     var emailAdd = document.getElementById("emailAdd");
                    //     emailAdd.setCustomValidity("");
                    //     emailAdd.style.border = "";
                    //     document.getElementById("requiredElement").style.display = "none";
                    // }
                }
            }
        }
    });
</script>