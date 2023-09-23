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
        <?php if(session()->getFlashdata('error') !== NULL):?>
        <div class="alert alert-warning">
            <?php echo session()->get('error') ?>
        </div>
        <?php endif;?>
        <div class="alert alert-success" style="display: none;" id="successDisplay">
        </div>
        <div class="alert alert-warning" style="display: none;" id="errorDisplay">
        </div>

        <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>
        <br>
        <center><h2>DEMANDS</h2></center>

        <div class="text-left">
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
        </div>
        <br>
        
        
    <form id="addForm" method="post" enctype="multipart/form-data" accept-charset="multipart/form-data">
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Demand</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="Choose the Client in the options given. Ex: Alstom">Client</label>
                                <select name="client" class="form-control" required>
                                    <option value="">-Select-</option>
                                    <?php foreach($clients as $keys => $data):?>
                                    <option value="<?php echo ucwords($data['CLIENT_ID'])?>"><?php echo ucwords($data['CLIENT_NAME'])?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" data-bs-placement="right" title="Write the ID given in the Job Description. Not Mandatory. Ex: BNGCPT-3346">JD ID</label>
                                <input type="text" class="form-control" name="jd_id" placeholder="JD ID" pattern="\S(.*\S)?">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="Status of the Demand. Ex: OPEN">Demand Status</label>
                                <select name="demand_status" class="form-control" required>
                                    <option value="">-Select-</option>
                                    <?php foreach($status as $s):?>
                                            <option value="<?php echo ucwords($s)?>"><?php echo ucwords($s)?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" data-bs-placement="right" title="Demand Priority. Ex: High">Priority</label>
                                <select name="priority" class="form-control">
                                    <option value="">-Select-</option>
                                    <?php foreach($priority as $p):?>
                                            <option value="<?php echo ucwords($p)?>"><?php echo ucwords($p)?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="Demand Complexity. If Demand is Easy to Search, select Low, etc.">Complexity</label>
                                <select name="complexity" class="form-control" required>
                                    <option value="">-Select-</option>
                                    <?php foreach($complexity as $c):?>
                                            <option value="<?php echo ucwords($c)?>"><?php echo ucwords($c)?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="Number of positions for current demand.">No of Positions</label>
                                <input type="number" class="form-control" min="1" name="no_positions" placeholder="No of Positions..." required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="The point of contact on the customer's end.">Customer SPOC</label>
                                <input type="text" class="form-control" name="cus_spoc" placeholder="Customer SPOC" required pattern="\S(.*\S)?">
                            </div>
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="The point of contact on Ibhaan's end.">Ibhaan SPOC</label>
                                <select class="form-control" name="ibhaan_spoc" id="ibhaan_spoc" required>
                                    <option value="">-Select-</option>
                                    <?php foreach($userNames as $keys=>$data):?>
                                        <option value="<?php echo $data['full_name']?>"><?php echo ucwords($data['full_name'])?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="The Industry that the demand belongs to.">Industry Segment</label>
                                <select name="industry" class="form-control" required>
                                    <option value="">-Select-</option>
                                    <?php foreach($industry as $i):?>
                                            <option class = "text-uppercase" value="<?php echo ucwords($i)?>"><?php echo ucwords($i)?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="The domain that the demand belongs to.">Domain</label>
                                <select name="domain" id = "domain" class="form-control" required>
                                    <option value="">-Select-</option>
                                    <?php foreach($domain as $keys => $data):?>
                                        <?php $id = str_replace("&", "", $keys);?>
                                        <?php $id = str_replace(" ","", $id);?>
                                        <option id="<?php echo $id?>" data-target="<?php echo $id?>" value="<?php echo $keys?>"><?php echo $keys?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <?php foreach($domain as $keys => $data):?>
                                <?php $id = str_replace("&", "", $keys);?>
                                <?php $id = str_replace(" ","", $id);?>
                                <div id="domain-<?php echo $id?>" style="display:none" class="col">
                                    <div class="form-group">
                                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="The skills that the demand belongs to.">Skill</label>
                                        <select name="<?php echo $id?>" id="skill" class="form-control skill">
                                            <option value="">-Select-</option>
                                            <?php foreach($data as $keys2 => $value):?>
                                                <option value="<?php echo $value?>" id="<?php echo str_replace(" ", "", $value)?>" data-target="<?php echo str_replace(" ", "", $value)?>"><?php echo $value?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                            <?php endforeach;?>
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" data-bs-placement="right" title="">Band</label>
                                <input type="text" class="form-control" name="band" placeholder="Band" pattern="\S(.*\S)?">
                            </div>
                        </div>
                        <div class="row">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Experience required by the candidates.">Required Experience</label>
                            <div class="form-group col">
                                <label class="required">Min</label>
                                <input type="number" step=0.1 class="experience form-control" name="min_experience" id="min_experience" placeholder="5.7" required pattern="\S(.*\S)?">
                            </div>
                            <div class="form-group col">
                                <label>Max</label>
                                <input type="number" step=0.1 class="experience form-control" name="max_experience" id="max_experience" placeholder="5.7" pattern="\S(.*\S)?">
                            </div>
                        </div>
                        <div class="row">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Budget in LPA.">Budget</label>
                            <div class="form-group col">
                                <label class="required">Min</label>
                                <input type="number" step=0.1 class="budget form-control" name="min_budget" id="min_budget" placeholder="5.7" required pattern="\S(.*\S)?">
                            </div>
                            <div class="form-group col">
                                <label>Max</label>
                                <input type="number" step=0.1 class="budget form-control" name="max_budget" id="max_budget" placeholder="5.7" pattern="\S(.*\S)?">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="Work Location given in the JD.">Location</label>
                                <select name="location" id="location" class="form-control" required>
                                    <option value="">-Select-</option>
                                    <?php foreach($location as $keys=>$data):?>
                                        <option value="<?php echo $data?>"><?php echo $data?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" data-bs-placement="right" title="The Job Title given in the JD.">Job Title</label>
                                <input type="text" class="form-control" name="j_title" placeholder="Job Title" required pattern="\S(.*\S)?">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="Primary Skills Mentioned in the JD.">Primary Skills</label>
                                <textarea class="form-control" name="p_skills" placeholder="Primary Skills" required pattern="\S(.*\S)?"></textarea>
                            </div>
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" data-bs-placement="left" title="Skills that are 'good to have's in the JD.">Secondary Skills</label>
                                <textarea class="form-control" name="s_skills" placeholder="Secondary Skills" pattern="\S(.*\S)?"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label data-bs-toggle="tooltip" data-bs-placement="left" title="A text-copy of the JD.">Elaborate JD</label>
                                <textarea class="form-control" name="jd" id="jd" placeholder="Job Description..."></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="JD to upload, which is stored in the system. Can be Downloaded Later. Preferred Format: .pdf, .docx, .txt">JD Upload</label>
                                <input type="file" class="form-control" name="jd_document" id="jd_document" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.txt,.pdf">
                            </div>
                        </div>                    
                        <div class="form-group col" style="display: none">
                            <label>Recruiter</label>
                            <input type="hidden" class="form-control" name="recruiter" value="<?php echo session()->get('user_id')?>">
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

    <form id="editForm" method="post" enctype="multipart/form-data" accept-charset="multipart/form-data">
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Current Demand</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="right" title="Write the ID given in the Job Description. Not Mandatory. Ex: BNGCPT-3346">JD ID</label>
                        <input type="text" class="form-control" name="jd_id2" id="jd_id2" placeholder="JD ID" pattern="\S(.*\S)?">
                    </div>
                    </div>
                    <div class="row">
                        <div class="form-group col" style="display:none">
                            <input type="hidden" class="form-control" name="demand_id2" id="demand_id2"></input>
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="Status of the Demand. Ex: OPEN">Demand Status</label>
                            <select name="demand_status2" id="demand_status2" class="form-control" required>
                                <option value="">-Select-</option>
                                <?php foreach($status as $s):?>
                                        <option value="<?php echo ucwords($s)?>"><?php echo ucwords($s)?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="Demand Priority. Ex: High">Priority</label>
                            <select name="priority2" id="priority2" class="form-control">
                                <option value="">-Select-</option>
                                <?php foreach($priority as $p):?>
                                        <option value="<?php echo ucwords($p)?>"><?php echo ucwords($p)?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="Choose the Client in the options given. Ex: Alstom">Client</label>
                            <select name="client2" id="client2" class="form-control" required>
                                <option value="">-Select-</option>
                                <?php foreach($clients as $keys => $data):?>
                                <option value="<?php echo ucwords($data['CLIENT_ID'])?>"><?php echo ucwords($data['CLIENT_NAME'])?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="The Job Title given in the JD.">Job Title</label>
                            <input type="text" class="form-control" name="j_title2" id="j_title2" placeholder="Job Title" required pattern="\S(.*\S)?">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="Demand Complexity. If Demand is Easy, select Low, etc.">Complexity</label>
                            <select name="complexity2" id="complexity2" class="form-control" required>
                                <option value="">-Select-</option>
                                <?php foreach($complexity as $c):?>
                                        <option value="<?php echo ucwords($c)?>"><?php echo ucwords($c)?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="Number of positions for current demand.">No of Positions</label>
                            <input type="number" class="form-control" min="1" max="100" name="no_positions2" id="no_positions2" placeholder="No of Positions..." required pattern="\S(.*\S)?">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="The point of contact on the customers end.">Customer SPOC</label>
                            <input type="text" class="form-control" name="cus_spoc2" id="cus_spoc2" placeholder="Customer SPOC" required pattern="\S(.*\S)?">
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" class="required" data-bs-placement="right" title="The point of contact on Ibhaans end.">Ibhaan SPOC</label>
                            <select class="form-control" name="ibhaan_spoc2" id="ibhaan_spoc2" required>
                                <option value="">-Select-</option>
                                <?php foreach($userNames as $keys=>$data):?>
                                    <option value="<?php echo $data['full_name']?>"><?php echo ucwords($data['full_name'])?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="Experience required by the candidates.">Required Experience</label>
                        <div class="form-group col">
                            <label class="required">Min</label>
                            <input type="number" step=0.1 class="experience form-control" name="min_experience2" id="min_experience2" placeholder="5.7" required pattern="\S(.*\S)?">
                        </div>
                        <div class="form-group col">
                            <label>Max</label>
                            <input type="number" step=0.1 class="experience form-control" name="max_experience2" id="max_experience2" placeholder="5.7" pattern="\S(.*\S)?">
                        </div>
                    </div>
                    <div class="row">
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="Budget in LPA.">Budget</label>
                        <div class="form-group col">
                            <label class="required">Min</label>
                            <input type="number" step=0.1 class="budget form-control" name="min_budget2" id="min_budget2" placeholder="5.7" required pattern="\S(.*\S)?">
                        </div>
                        <div class="form-group col">
                            <label>Max</label>
                            <input type="number" step=0.1 class="budget form-control" name="max_budget2" id="max_budget2" placeholder="5.7" pattern="\S(.*\S)?">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="Work Location given in the JD.">Location</label>
                            <select name="location2" id="location2" class="form-control" required>
                                <option value="">-Select-</option>
                                <?php foreach($location as $keys=>$data):?>
                                    <option value="<?php echo $data?>"><?php echo $data?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" class="required" data-bs-placement="left" title="Primary Skills Mentioned in the JD.">Primary Skills</label>
                            <textarea class="form-control" name="p_skills2" id="p_skills2" placeholder="Primary Skills" required pattern="\S(.*\S)?"></textarea>
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Skills that are 'good to have's in the JD.">Secondary Skills</label>
                            <textarea class="form-control" name="s_skills2" id="s_skills2" placeholder="Secondary Skills"></textarea>
                        </div>
                        <div class="form-group col" style="display:none">
                            <input type="number" name="recruiter2" id="recruiter2">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="JD to upload, which is stored in the system. Can be Downloaded Later. Preferred Format: .pdf, .docx, .txt">JD Upload</label>
                            <input type="file" class="form-control" name="jd_document2" id="jd_document2" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.txt,.pdf">
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="A text-copy of the JD.">Elaborate JD</label>
                            <textarea class="form-control" name="jd2" id="jd2" placeholder="Job Description..."></textarea>
                        </div>
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

    <?=form_open('Demands/DeleteDemand')?>
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Demand</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label data-bs-toggle="tooltip" data-bs-placement="left" title="Demand ID in Database">Demand ID</label>
                                <input type="text" class="form-control" style="pointer-events:none" name="demand_id3" id="demand_id3" pattern="\S(.*\S)?" required>
                            </div>
                            <div class="form-group">
                                <label data-bs-toggle="tooltip" data-bs-placement="left" title="Job Title">Job Title</label>
                                <input type="text" class="form-control" style="pointer-events:none" name="job_title3" id="job_title3" pattern="\S(.*\S)?" required>
                            </div>
                            <center>
                                <h3>Are you sure you want to delete this demand details?</h3>
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
</style>

<script>
    $(document).ready(function(){
        //Dynamic Select Button Toggle
        $('#domain').on('change', function() {
        $('.skill').prop('required', false);
        var target=$(this).find(":selected").attr("data-target");
        var id=$(this).attr("id");
        $("div[id^='"+id+"']").hide();
        $("#"+id+"-"+target).show();
        });

    });

    var timeout = null;
    var max = 0;
    var min = 0;

    $("#max_experience, #min_experience").on('input keyup keydown', function(){
        clearTimeout(timeout);
        var min = document.getElementById('min_experience').value;
        var min = parseFloat(min);
        var max = document.getElementById('max_experience').value;
        var max = parseFloat(max);
        var validation = document.getElementById('max_experience');
        validation.setCustomValidity('');
        timeout = setTimeout(() => {
        if(max!= "" && min != "")
        {
            if(max<=min)
            {
                var validation = document.getElementById('max_experience');
                validation.setCustomValidity('');
                validation.setCustomValidity('Max Experience cannot be lesser than minimum experience.');
                validation.reportValidity();
            }
            else if(max > min)
            {
                var validation = document.getElementById('max_experience');
                if(validation.checkValidity())
                {
                validation.setCustomValidity('');
                }
            }
        }
        }, 1000);
    });

    $("#max_experience2, #min_experience2").on('input', function(){
        clearTimeout(timeout);
        var min = document.getElementById('min_experience2').value;
        var min = parseFloat(min);
        var max = document.getElementById('max_experience2').value;
        var max = parseFloat(max);
        var validation = document.getElementById('max_experience2');
        validation.setCustomValidity('');
        timeout = setTimeout(() => {
        if(max!= "" && min != "")
        {
            if(max<=min)
            {
                var validation = document.getElementById('max_experience2');
                validation.setCustomValidity('');
                validation.setCustomValidity('Max Experience cannot be lesser than minimum experience.');
                validation.reportValidity();
            }
            else if(max > min)
            {
                var validation = document.getElementById('max_experience2');
                if(validation.checkValidity())
                {
                validation.setCustomValidity('');
                }
            }
        }
        }, 1000);
    });

    $("#max_budget, #min_budget").on('input keyup keydown', function(){
        clearTimeout(timeout);
        var min = document.getElementById('min_budget').value;
        var min = parseFloat(min);
        var max = document.getElementById('max_budget').value;
        var max = parseFloat(max);
        var validation = document.getElementById('max_budget');
        validation.setCustomValidity('');
        timeout = setTimeout(() => {
        if(max != "" && min != "")
        {
            if(max<=min)
            {
                var validation = document.getElementById('max_budget');
                validation.setCustomValidity('');
                validation.setCustomValidity('Max Budget cannot be lesser than Minimum Budget.');
                validation.reportValidity();
            }
            else if(max > min)
            {
                var validation = document.getElementById('max_budget');
                if(validation.checkValidity())
                {
                validation.setCustomValidity('');
                }
            }
        }   
        }, 1000);
    })

    $("#max_budget2, #min_budget2").on('input', function(){
        clearTimeout(timeout);
        var min = document.getElementById('min_budget2').value;
        var min = parseFloat(min);
        var max = document.getElementById('max_budget2').value;
        var max = parseFloat(max);
        var validation = document.getElementById('max_budget2');
        validation.setCustomValidity('');
        timeout = setTimeout(() => {
        if(max != "" && min != "")
        {
            if(max<=min)
            {
                var validation = document.getElementById('max_budget2');
                validation.setCustomValidity('');
                validation.setCustomValidity('Max Budget cannot be lesser than Minimum Budget.');
                validation.reportValidity();
            }
            else if(max > min)
            {
                var validation = document.getElementById('max_budget2');
                if(validation.checkValidity())
                {
                validation.setCustomValidity('');
                }
            }
        }   
        }, 1000);
    })

    $(document).on("click", ".editButton", function () {
        var demandID = $(this).data('id');
        var httpRequest = new XMLHttpRequest();
        var params = "demand_id="+demandID;
        var res;
        httpRequest.open('GET','<?php echo base_url('Demands/GetDemand')?>'+"?"+params, true);
        httpRequest.send();
        httpRequest.onload = function() {
            $("#editForm")[0].reset();
            res = JSON.parse(httpRequest.responseText);
            var jobTitle = res.JOB_TITLE;
            var clientID = res.CLIENT_ID;
            var status = res.DEMAND_STATUS;
            var priority = res.PRIORITY;
            var complexity = res.COMPLEXITY;
            var ib = res.IBHAAN_SPOC;
            var cus = res.CUS_SPOC;
            var jd_id = res.JD_ID;
            var no = res.NO_POSITIONS;
            var minExp = res.MIN_EXPERIENCE;
            var maxExp = res.MAX_EXPERIENCE;
            var minBud = res.MIN_BUDGET;
            var maxBud = res.MAX_BUDGET;
            var band = res.BAND;
            var location = res.LOCATION;
            var primary = res.PRIMARY_SKILL;
            var secondary = res.SECONDARY_SKILL;
            var jd = res.JOB_DESCRIPTION;
            var recruiter = res.RECRUITER;

            $(".form-group #client2").val("").change();
            $(".form-group #demand_id2").val("");
            $(".form-group #recruiter2").val("");
            $(".form-group #client2").val( clientID ).change();
            $(".form-group #demand_id2").val( demandID );
            $(".form-group #jd_id2").val( jd_id );
            $(".form-group #ibhaan_spoc2").val( ib );
            $(".form-group #no_positions2").val( no );
            $(".form-group #cus_spoc2").val( cus );
            $(".form-group #priority2").val( priority ).change();
            $(".form-group #complexity2").val( complexity ).change();
            $(".form-group #demand_status2").val( status ).change();
            $(".form-group #location2").val(location).change();
            $(".form-group #min_experience2").val(minExp);
            $(".form-group #max_experience2").val(maxExp);
            $(".form-group #min_budget2").val(minBud);
            $(".form-group #max_budget2").val(maxBud);
            $(".form-group #j_title2").val( jobTitle );
            $(".form-group #p_skills2").val( primary );
            $(".form-group #s_skills2").val( secondary );
            $(".form-group #jd2").val( jd );
            $(".form-group #recruiter2").val(recruiter);
        }
    });
    
    
    $("#editModal").on("hidden.bs.modal", function () {
        $("#editForm")[0].reset();
    });
    
    $("#editForm").submit(function(e){
        e.preventDefault();
        var params = new FormData($("#editForm")[0]);
        console.log(params);
        $("#successDisplay").hide();
        $("#errorDisplay").hide();
        $("#editModal").modal('hide');
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('Demands/EditDemand')?>',
            data: params,
            processData: false,
            contentType: false,
            success: function(data)
            {
                var str = JSON.parse(data);
                if(str[0].valueOf() === "success".valueOf())
                {
                    $("#successDisplay").html('Demand Details Edited Successfully.');
                    $("#successDisplay").show();
                    confirm("Demand Details Edited Successfully.");
                    document.getElementById("successDisplay").scrollIntoView({behavior: 'smooth'});
                }
                else
                {
                    $("#errorDisplay").html("Insufficient Details Provided. Please Try Again.");
                    alert("Insufficient Details Provided. Please Try Again.");
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
    
    $("#addForm").submit(function(e){
        e.preventDefault();
        var params = new FormData($("#addForm")[0]);
        console.log(params);
        $("#successDisplay").hide();
        $("#errorDisplay").hide();
        $("#addModal").modal('hide');
        $("#addForm")[0].reset();

        $.ajax({
            type: "POST",
            url: '<?php echo base_url('Demands/AddDemand')?>',
            data: params,
            processData: false,
            contentType: false,
            success: function(data)
            {
                var str = JSON.parse(data);
                if(str[0].valueOf() === "success".valueOf())
                {
                    $("#successDisplay").html('Demand Updated Successfully.');
                    $("#successDisplay").show();
                    confirm("Demand Details Updated Successfully.");
                    document.getElementById("successDisplay").scrollIntoView({behavior: 'smooth'});
                    var rows = str[3];
                    console.log(rows);
                    for(var key in rows){
                        $("#demands").DataTable().row.add( rows[key] ).draw();
                    }
                }
                else
                {
                    $("#errorDisplay").html("Insufficient Details Provided. Please Try Again.");
                    $("#errorDisplay").show();
                    alert("Insufficient Details Provided. Please Try Again.");
                    confirm("Demand Details Updated Successfully.");
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

    $(document).on("click", ".deleteButton", function() {
        var demandID = $(this).data('id');
        var jobTitle = $(this).data('name');
        $(".form-group #demand_id3").val( demandID );
        $(".form-group #demand_display").val( demandID);
        $(".form-group #job_title3").val( jobTitle );
    });

    $(document).ready(function() {
        /* Printing DataTable */

        var fieldNames = <?php echo json_encode($fieldNames)?>;
        var fieldNames = fieldNames;
        var columns = [];
        columns.push({ data: null, title: "JD", orderable: false, className: 'dt-control', defaultContent: '' });
        for(var field in fieldNames)
        {
            if(fieldNames[field].toLowerCase() == "full_name")
            {
                columns.push({ data: fieldNames[field] , title: "RECRUITER", className: "text-center justify-content-center col"});
            }
            else 
            if(fieldNames[field].toLowerCase() != "job_description" && fieldNames[field].toLowerCase() != "primary_skill" && fieldNames[field].toLowerCase() != "secondary_skill")
            {
                columns.push({ data: fieldNames[field] , title: fieldNames[field].replace("_"," "), className: "text-center justify-content-center col"});
            }
            else
            if(fieldNames[field].toLowerCase() == "job_description" || fieldNames[field].toLowerCase() == "primary_skill" || fieldNames[field].toLowerCase() == "secondary_skill")
            {
                columns.push({ data: fieldNames[field] , title: fieldNames[field].replace("_"," "), className: "text-center justify-content-center"});
            }

        }
        columns.push({ data: null, title: "EDITS", 
            render: function(data) {
                var btn = '<div class="btn-group" role="group"><button type="button" class="btn btn-dark btn-sm editButton" data-bs-toggle="modal" data-id="'+data.DEMAND_ID+'" data-bs-target="#editModal" name="editButton" id="editButton"><i class="bi bi-pen"></i></button></div>';
                return btn;
            }
        });

        var demands = <?php echo json_encode($demands)?>;
        var table = $('#demands').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', {extend: 'csv', filename: function() { return getFileName();}}, {extend: 'excel', filename: function() { return getFileName();}}, 'print'],
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
            fixedColumns:   {
                right: 1,
                left: 0
            },
            order: [[1,"asc"]],
        });
        
        function getFileName(){
            var date = new Date();
            
            return "Demands As of: "+" - "+date.getDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear();
        }
        
        $("#demands").DataTable().columns.adjust().draw();
        $("#demands")[0].tBodies[0].className = "container";

        var $container = $(".container");
        var $scroller = $(".dataTables_scrollBody");;
        
        bindDragScroll($container, $scroller);
        window.dispatchEvent(new Event('resize'));

        $('#demands tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = $("#demands").DataTable().row(tr);
            var data = row.data();
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                jd_location = data["JD_LOCATION"];
                job_description = data["JOB_DESCRIPTION"];
                console.log(jd_location);
                row.child(loader).show();
                row.child(format(jd_location, job_description)).show();
                tr.addClass('shown');
            }
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
                a = '<div class="form-group inline-block" style="text-align: left;"><h4 class="col">Download The JD With The Button Below:</h4><button style="width: auto; display: inline-block;" type="button" class="btn btn-outline-dark btn-sm" data-id="'+d+'" name="jdView2" id="jdView2"><i class="bi bi-download"></i> | Download</button></div>';
            }
            if(e != undefined && e !== "")
            {
                b = '<div style="white-space: pre-wrap;"><b>JOB DESCRIPTION</b><br>'+e+'</div>'
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

    })

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
</script>