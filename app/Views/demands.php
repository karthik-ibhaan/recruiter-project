<!doctype html>
<html lang="en">
    <?php include('Headers/head.php')?>
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

        <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>

        <br>
        <div class="row">
            <div class="column">
                <input type="text" id="filter" placeholder="Enter Filter...">
                <button type="button" name="reset-button" id="reset-button" value="<?php echo ""?>">Reset Filter</button>
            </div>
        </div>
        <br>
        <center><h2>DEMANDS</h2></center>

        <div class="text-center">
            <table style="width:100%;" class="table" id="demands" name="demands">
                <thead>
                    <tr>
                        <?php foreach($fieldNames as $keys=>$values):?>
                            <?php if(strtolower(strtolower($values) == "demand_id" || strtolower($values) == "client_id")):?>
                                <?php continue;?>
                            <?php endif;?>
                        <?php $display = str_replace('','', $values);?>
                        <th class="text-center col text-uppercase"><?php echo $display?></th>
                        <?php endforeach;?>
                        <th class="text-center justify-content-center">EDITS</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($demands as $keys=>$data):?>
                    <tr>
                        <?php foreach($fieldNames as $keys=>$value):?>
                            <?php if(strtolower(strtolower($value) == "demand_id" || strtolower($value) == "client_id")):?>
                                <?php continue;?>
                            <?php endif;?>
                        <td class="text-center"><?php echo $data[$value]?></td>
                        <?php endforeach;?>
                        <td>
                            <div class="col">
                                <button 
                                    type="button" 
                                    class="btn btn-dark btn-sm editButton" 
                                    data-bs-toggle="modal" 
                                    data-id="<?php echo $data['DEMAND_ID']?>" 
                                    data-name="<?php echo $data['JOB_TITLE']?>" 
                                    data-client="<?php echo $data['CLIENT_ID']?>" 
                                    data-cus="<?php echo $data['CUS_SPOC']?>" 
                                    data-ib="<?php echo $data['IBHAAN_SPOC']?>" 
                                    data-status = "<?php echo $data['DEMAND_STATUS']?>" 
                                    data-priority="<?php echo $data['PRIORITY']?>" 
                                    data-complexity="<?php echo $data['COMPLEXITY']?>" 
                                    data-jd = "<?php echo $data['JD_ID']?>" 
                                    data-no = "<?php echo $data['NO_POSITIONS']?>" 
                                    data-minb = "<?php echo $data['MIN_BUDGET']?>"
                                    data-maxb = "<?php echo $data['MAX_BUDGET']?>"
                                    data-mine = "<?php echo $data['MIN_EXPERIENCE']?>"
                                    data-maxe = "<?php echo $data['MAX_EXPERIENCE']?>"                                     
                                    data-band="<?php echo $data['BAND']?>" 
                                    data-location="<?php echo $data['LOCATION']?>" 
                                    data-job="<?php echo $data['JOB_TITLE']?>" 
                                    data-primary="<?php echo $data['PRIMARY_SKILL']?>" 
                                    data-secondary="<?php echo $data['SECONDARY_SKILL']?>" 
                                    data-bs-target="#editModal">EDIT</button>
                                <button type="button" class="btn btn-dark btn-sm deleteButton" data-bs-toggle="modal" data-id="<?php echo $data['DEMAND_ID']?>" data-name="<?php echo $data['JOB_TITLE']?>" data-bs-target="#deleteModal">DELETE</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                    <tr>
                        <?php foreach($fieldNames as $keys=>$values):?>
                            <?php if(strtolower(strtolower($values) == "demand_id" || strtolower($values) == "client_id")):?>
                                <?php continue;?>
                            <?php endif;?>
                            <td></td>
                        <?php endforeach;?>
                        <td style="text-align:center"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <br>
        <?= form_open('Demands/AddDemand')?>
            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Demand</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Choose the Client in the options given. Ex: Alstom">Client</label>
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
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Status of the Demand. Ex: OPEN">Demand Status</label>
                            <select name="demand_status" class="form-control" required>
                                <option value="">-Select-</option>
                                <?php foreach($status as $s):?>
                                        <option value="<?php echo ucwords($s)?>"><?php echo ucwords($s)?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="Demand Priority. Ex: High">Priority</label>
                            <select name="priority" class="form-control" required>
                                <option value="">-Select-</option>
                                <?php foreach($priority as $p):?>
                                        <option value="<?php echo ucwords($p)?>"><?php echo ucwords($p)?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Demand Complexity. If Demand is Easy to Search, select Low, etc.">Complexity</label>
                            <select name="complexity" class="form-control" required>
                                <option value="">-Select-</option>
                                <?php foreach($complexity as $c):?>
                                        <option value="<?php echo ucwords($c)?>"><?php echo ucwords($c)?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="Number of positions for current demand.">No of Positions</label>
                            <input type="number" class="form-control" min="1" name="no_positions" placeholder="No of Positions..." required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="The point of contact on the customer's end.">Customer SPOC</label>
                            <input type="text" class="form-control" name="cus_spoc" placeholder="Customer SPOC" required pattern="\S(.*\S)?">
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="ID given in the Job Description. Ex: BNGCPT-3346">Ibhaan SPOC</label>
                            <input type="text" class="form-control" name="ibhaan_spoc" placeholder="Ibhaan SPOC" value="<?php echo session()->get('name')?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="The Industry that the demand belongs to.">Industry Segment</label>
                            <select name="industry" class="form-control" required>
                                <option value="">-Select-</option>
                                <?php foreach($industry as $i):?>
                                        <option class = "text-uppercase" value="<?php echo ucwords($i)?>"><?php echo ucwords($i)?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="The domain that the demand belongs to.">Domain</label>
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
                            <label>Min</label>
                            <input type="number" step=0.1 class="experience form-control" name="min_experience" id="min_experience" placeholder="5.7" required pattern="\S(.*\S)?">
                        </div>
                        <div class="form-group col">
                            <label>Max</label>
                            <input type="number" step=0.1 class="experience form-control" name="max_experience" id="max_experience" placeholder="5.7" pattern="\S(.*\S)?">
                        </div>
                    </div>
                    <div class="row">
                        <label data-bs-toggle="tooltip" data-bs-placement="right" title="Budget in LPA.">Budget</label>
                        <div class="form-group col">
                            <label>Min</label>
                            <input type="number" step=0.1 class="budget form-control" name="min_budget" id="min_budget" placeholder="5.7" required pattern="\S(.*\S)?">
                        </div>
                        <div class="form-group col">
                            <label>Max</label>
                            <input type="number" step=0.1 class="budget form-control" name="max_budget" id="max_budget" placeholder="5.7" pattern="\S(.*\S)?">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Work Location given in the JD.">Location</label>
                            <select name="location" id="location" class="form-control" required>
                                <option value="">-Select-</option>
                                <?php foreach($location as $keys=>$data):?>
                                    <option value="<?php echo $data?>"><?php echo $data?></option?>
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
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Primary Skills Mentioned in the JD.">Primary Skills</label>
                            <textarea class="form-control" name="p_skills" placeholder="Primary Skills" required pattern="\S(.*\S)?"></textarea>
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Skills that are 'good to have's in the JD.">Secondary Skills</label>
                            <textarea class="form-control" name="s_skills" placeholder="Secondary Skills" pattern="\S(.*\S)?"></textarea>
                        </div>
                        <div class="form-group col" style="display: none">
                            <label>Recruiter</label>
                            <input type="hidden" class="form-control" name="recruiter" value="<?php echo session()->get('user_id')?>">
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

<?= form_open('Demands/EditDemand')?>
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Current Demand</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                <div class="form-group col">
                    <label data-bs-toggle="tooltip" data-bs-placement="right" title="Write the ID given in the Job Description. Not Mandatory. Ex: BNGCPT-3346">JD ID</label>
                    <input type="text" class="form-control" name="jd_id2" id="jd_id2" placeholder="JD ID" pattern="\S(.*\S)?">
                </div>
                </div>
                <div class="row">
                    <div class="form-group col" style="display:none">
                        <input type="hidden" class="form-control" name="demand_id2" id="demand_id2"></input">
                    </div>
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="Status of the Demand. Ex: OPEN">Demand Status</label>
                        <select name="demand_status2" id="demand_status2" class="form-control" required>
                            <option value="">-Select-</option>
                            <?php foreach($status as $s):?>
                                    <option value="<?php echo ucwords($s)?>"><?php echo ucwords($s)?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="right" title="Demand Priority. Ex: High">Priority</label>
                        <select name="priority2" id="priority2" class="form-control" required>
                            <option value="">-Select-</option>
                            <?php foreach($priority as $p):?>
                                    <option value="<?php echo ucwords($p)?>"><?php echo ucwords($p)?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="Choose the Client in the options given. Ex: Alstom">Client</label>
                        <select name="client2" id="client2" class="form-control" required>
                            <option value="">-Select-</option>
                            <?php foreach($clients as $keys => $data):?>
                            <option value="<?php echo ucwords($data['CLIENT_ID'])?>"><?php echo ucwords($data['CLIENT_NAME'])?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="right" title="The Job Title given in the JD.">Job Title</label>
                        <input type="text" class="form-control" name="j_title2" id="j_title2" placeholder="Job Title" required pattern="\S(.*\S)?">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="Demand Complexity. If Demand is Easy, select Low, etc.">Complexity</label>
                        <select name="complexity2" id="complexity2" class="form-control" required>
                            <option value="">-Select-</option>
                            <?php foreach($complexity as $c):?>
                                    <option value="<?php echo ucwords($c)?>"><?php echo ucwords($c)?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="right" title="Number of positions for current demand.">No of Positions</label>
                        <input type="number" class="form-control" min="1" max="100" name="no_positions2" id="no_positions2" placeholder="No of Positions..." required pattern="\S(.*\S)?">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="The point of contact on the customer's end.">Customer SPOC</label>
                        <input type="text" class="form-control" name="cus_spoc2" id="cus_spoc2" placeholder="Customer SPOC" required pattern="\S(.*\S)?">
                    </div>
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="right" title="ID given in the Job Description. Ex: BNGCPT-3346">Ibhaan SPOC</label>
                        <input type="text" class="form-control" name="ibhaan_spoc2" id="ibhaan_spoc2" placeholder="Ibhaan SPOC" required pattern="\S(.*\S)?">
                    </div>
                </div>
                <div class="row">
                    <label data-bs-toggle="tooltip" data-bs-placement="left" title="Experience required by the candidates.">Required Experience</label>
                    <div class="form-group col">
                        <label>Min</label>
                        <input type="number" step=0.1 class="experience form-control" name="min_experience2" id="min_experience2" placeholder="5.7" required pattern="\S(.*\S)?">
                    </div>
                    <div class="form-group col">
                        <label>Max</label>
                        <input type="number" step=0.1 class="experience form-control" name="max_experience2" id="max_experience2" placeholder="5.7" pattern="\S(.*\S)?">
                    </div>
                </div>
                <div class="row">
                    <label data-bs-toggle="tooltip" data-bs-placement="right" title="Budget in LPA.">Budget</label>
                    <div class="form-group col">
                        <label>Min</label>
                        <input type="number" step=0.1 class="budget form-control" name="min_budget2" id="min_budget2" placeholder="5.7" required pattern="\S(.*\S)?">
                    </div>
                    <div class="form-group col">
                        <label>Max</label>
                        <input type="number" step=0.1 class="budget form-control" name="max_budget2" id="max_budget2" placeholder="5.7" pattern="\S(.*\S)?">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="Work Location given in the JD.">Location</label>
                        <select name="location2" id="location2" class="form-control">
                            <option value="">-Select-</option>
                            <?php foreach($location as $keys=>$data):?>
                                <option value="<?php echo $data?>"><?php echo $data?></option?>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="Primary Skills Mentioned in the JD.">Primary Skills</label>
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
                <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete Client</h5>
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
                                    <h3>Are you sure you want to delete this client details?</h3>
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

        <?= form_open('Demands/FileExport') ?>
            <?= csrf_field() ?>
            <center>
            <div class = "col-sm">
                <p>Click below to export the data</p>
                <input type="submit" class="btn btn-dark btn-sm" value="Export" />
            </div>
            </center>
        </form>
    </div>
    <?php include('Footers/footer.php')?>
    </body>
</html>

<script>
    $(document).ready(function(){
        //Dynamic Select Button Toggle
        $('#domain').on('change', function() {
        $('.skill').prop('required', false);
        console.log($('.skill').attr('required'));
        var target=$(this).find(":selected").attr("data-target");
        console.log(target);
        var id=$(this).attr("id");
        $("div[id^='"+id+"']").hide();
        $("#"+id+"-"+target).show();
        $("[name="+target+"]").prop('required', true);
        console.log($("[name="+target+"]").attr('required'));
        });

    });

    $("#max_experience").on('input', function(){
        var min = document.getElementById('min_experience').value;
        var max = document.getElementById('max_experience').value;
        console.log(min, max);
        if(max<=min)
        {
            var validation = document.getElementById('max_experience');
            validation.setCustomValidity('Max Experience cannot be lesser than minimum experience.');
            validation.reportValidity();
        }
        else
        {
            $(this).setCustomValidity('');
        }
    });

    $("#max_experience2").on('input', function(){
        var min = document.getElementById('min_experience2').value;
        var max = document.getElementById('max_experience2').value;
        console.log(min, max);
        if(max<=min)
        {
            var validation = document.getElementById('max_experience2');
            validation.setCustomValidity('Max Experience cannot be lesser than minimum experience.');
            validation.reportValidity();
        }
        else
        {
            $(this).setCustomValidity('');
        }
    });

    $("#max_budget").on('input', function(){
        var min = document.getElementById('min_budget').value;
        var max = document.getElementById('max_budget').value;
        console.log(min, max);
        if(max<=min)
        {
            var validation = document.getElementById('max_budget');
            validation.setCustomValidity('Max Budget cannot be lesser than Minimum Budget.');
            validation.reportValidity();
        }
        else
        {
            $(this).setCustomValidity('');
        }
    })

    $("#max_budget2").on('input', function(){
        var min = document.getElementById('min_budget2').value;
        var max = document.getElementById('max_budget2').value;
        console.log(min, max);
        if(max<=min)
        {
            var validation = document.getElementById('max_budget2');
            validation.setCustomValidity('Max Budget cannot be lesser than Minimum Budget.');
            validation.reportValidity();
        }
        else
        {
            $(this).setCustomValidity('');
        }
    })

    $(document).on("click", ".editButton", function () {
        var demandID = $(this).data('id');
        var httpRequest = new XMLHttpRequest();
            var params = "demand_id="+demandID;
            var res;
            httpRequest.open('GET','<?php echo base_url('Demands/GetDemand')?>'+"?"+params, true);
            httpRequest.send();
            httpRequest.onload = function() {
                res = JSON.parse(httpRequest.responseText);
                var jobTitle = res.JOB_TITLE;
                console.log(res);
                console.log(jobTitle);
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
                var recruiter = res.RECRUITER;

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
                $(".form-group #recruiter2").val(recruiter);
            }
    });

    $(document).on("click", ".deleteButton", function() {
        var demandID = $(this).data('id');
        var jobTitle = $(this).data('name');
        console.log(demandID, jobTitle);
        $(".form-group #demand_id3").val( demandID );
        $(".form-group #job_title3").val( jobTitle );
    });

    $('#reset-button').click(debound(filter_table, 500))
    $(".dropdown-item").click(debound(filter_table, 500))
    document.getElementById('filter').addEventListener('input', function (e) {
        e.target.value = e.target.value.toLowerCase();
    });
    
    document.getElementById('filter').addEventListener('input', debound(filter_table, 500))


    $(document).ready(function() {
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
        scrollX: true,
        scrollCollapse: true,
        fixedColumns:   {
            right: 1,
            left: 0
        },
        order: [[1,"desc"]],
        });
    })

    addEventListener('resize', (event) => {table.columns.adjust().draw();});

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
    console.log("HELLO FROM THE OTHER")
</script>