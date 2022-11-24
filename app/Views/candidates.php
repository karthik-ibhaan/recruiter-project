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

        <center><h2>YOUR CANDIDATES</h2></center>

        <br>
        
        <h3>To see all of the current candidates, <a class="btn btn-primary" href="/candidatesview">Click Here</a></h3>

        <br>

        <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>
        <div class="row">
            <div class="column">
                <input type="text" id="filter" value="" placeholder="Enter Filter...">
                <button type="button" name="reset-button" class="btn btn-primary mb-2" id="reset-button" value="<?php echo ""?>" >Reset Filter</button>
            </div>
        </div>
        <div class="text-center">
        <table class="table" id="candidates" name="candidates">
            <thead>
                <tr>
                <?php foreach($fieldNames as $keys=>$values):?>
                    <?php $display = str_replace('_',' ', $values);?>
                    <th scope="col" class="col">
                        <?php echo $display?>
                    </th>
                <?php endforeach;?>
                <th class="align-middle">EDITS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($candidates as $keys=>$data):?>
                    <tr>
                        <?php foreach($fieldNames as $keys=>$value):?>
                        <td><?php echo $data[$value]?></td>
                        <?php endforeach;?>
                        <td>
                            <div class="col">
                                <button
                                    type="button" 
                                    class="btn btn-dark btn-sm editButton" 
                                    data-bs-toggle="modal" 
                                    data-id="<?php echo $data['CANDIDATE_ID']?>"
                                    data-bs-target="#editModal"
                                    name="editButton"
                                    id="editButton">EDIT</button>
                                <button 
                                    type="button" 
                                    class="btn btn-dark btn-sm deleteButton" data-bs-toggle="modal" 
                                    data-id="<?php echo $data['CANDIDATE_ID']?>" 
                                    data-name="<?php echo $data['CANDIDATE_NAME']?>" 
                                    data-bs-target="#deleteModal">DELETE</button>
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
                    <td></td>
                </tr>
            </tfoot>
        </table>
        </div>
        <?= form_open('Candidates/FileExport') ?>
            <?= csrf_field() ?>
            <center>
            <div class = "col-sm">
                <p>Click below to export the data</p>
                <input type="submit" class="btn btn-dark btn-sm" value="Export" />
            </div>
            </center>
        </form>
    </div>


    <?= form_open('Candidates/AddCandidate', ['id'=>'form'])?>
            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
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
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="The client for whom the candidate is sourced.">Client</label>
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
                                    <label data-bs-toggle="tooltip" data-bs-placement="left" title="The Job Title for which the candidate is sourced.">Job Title</label>
                                    <select name="<?php echo $id?>" id="demand" class="demand search-filter form-control">
                                        <option value="">-Select-</option>
                                        <?php foreach($demandOptions as $keys => $data):?>
                                            <?php if($data['CLIENT_ID'] == $id):?>
                                                <option value="<?php echo $data['DEMAND_ID']?>" id="<?php echo $data['DEMAND_ID']?>" data-target="<?php echo $data['DEMAND_ID']?>"><?php echo $data['JOB_TITLE']?></option>
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
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="The name of the Candidate.">Candidate Name</label>
                            <input type="text" class="form-control" name="candidate_name" placeholder="Candidate Name..." required pattern="\S(.*\S)?">
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="The email address of the candidate">Email Address</label>
                            <input type="email" id="emailAdd" class="search-filter form-control" name="emailAdd" placeholder="email@example.com" required pattern="\S(.*\S)?">
                        </div>
                        <label name="requiredElement" id="requiredElement"></label>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="The current stage of recruitment.">Process</label>
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
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="Phone Number of the Candidate.">Phone Number</label>
                            <input type="number" aria-label="Phone Number 1" min="0" class="form-control" id="phno_1" name="phno_1" placeholder="1234567890" pattern = "[6789]\d{9,9}">
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="Alternate Phone Number of the Candidate. This is not mandatory.">Alternate Phone Number</label>
                            <input type="number" aria-label="Phone Number 2" min="0" class="form-control" id="phno_2" name="phno_2" placeholder="1234567890" pattern = "[6789]\d{9,9}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label>Organisation</label>
                            <input type="text" class="form-control" name="organisation" pattern="\S(.*\S)?" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="The Current Working Location of the Candidate.">Location</label>
                            <select name="location" id="location" class="form-control" required>
                                <option value="">-Select-</option>
                                <?php foreach($location as $keys=>$data):?>
                                    <option value="<?php echo $data?>"><?php echo $data?></option?>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="Experience required by the candidates.">Experience</label>
                            <input type="number" step=0.1 min=1 class="form-control" name="experience" placeholder="5.7" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Current CTC in Lakhs per Annum.">Current CTC (in LPA)</label>
                            <input type="number" step=0.1 min=0 class="form-control" name="cctc" placeholder="5.7" required>
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="Budget in LPA.">Expected CTC (in LPA)</label>
                            <input type="number" step=0.1 min=0 class="form-control" name="ectc" placeholder="5.7" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Notice Period (in Days) Ex: 30">Notice Period (in Days)</label>
                            <input type="number" class="form-control" name="NP" min=1 max=60 placeholder="30" step="1" required>
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
                    <div class="row" id="selection-data" style="display:none">
                        <div class="form-group col">
                            <label>Planned Date of Joining</label>
                            <input type="date" class="form-control" name="plannedDOJ" id="plannedDOJ">
                        </div>
                        <div class="form-group col">
                            <label>Actual Date of Joining</label>
                            <input type="date" class="form-control" name="actualDOJ" id="actualDOJ">
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

        <?= form_open('Candidates/EditCandidate', ['id'=>'form'])?>
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Candidate</h5>
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
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="The client for whom the candidate is sourced.">Client</label>
                            <select name="client_id2" id = "client_id2" class="form-control" required>
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
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="The Job Title for which the candidate is sourced.">Job Title</label>
                            <input type="text" name="j_title2" id="j_title2" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="The name of the Candidate.">Candidate Name</label>
                            <input type="text" class="form-control" name="candidate_name2" id="candidate_name2" placeholder="Candidate Name..." required pattern="\S(.*\S)?">
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="The email address of the candidate">Email Address</label>
                            <input type="email" class="search-filter form-control" name="emailAdd2" id="emailAdd2" placeholder="email@example.com" required pattern="\S(.*\S)?">
                        </div>
                        <label name="requiredElement" id="requiredElement"></label>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="The current stage of recruitment.">Process</label>
                            <select name="action2" id = "action2" class="form-control" required>
                                <option value="">-Select-</option>
                                <?php foreach($status as $keys => $data):?>
                                    <?php $id = str_replace("&", "", $keys);?>
                                    <?php $id = str_replace(" ","", $id);?>
                                    <option id="<?php echo $id.'2'?>" data-target="<?php echo $id.'2'?>" value="<?php echo $keys?>"><?php echo $keys?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <?php foreach($status as $keys => $data):?>
                            <?php $id = str_replace("&", "", $keys);?>
                            <?php $id = str_replace(" ","", $id);?>
                            <div id="action2-<?php echo $id.'2'?>" style="display:none" class="col">
                                <div class="form-group">
                                    <label data-bs-toggle="tooltip" data-bs-placement="left" title="The status of recruitment.">Recruitment Status</label>
                                    <select name="<?php echo $id?>" id="status2" class="status2 form-control">
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
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="Phone Number of the Candidate.">Phone Number</label>
                            <input type="number" aria-label="Phone Number 1" class="form-control" id="edit_phno_1" name="edit_phno_1" placeholder="1234567890" pattern = "[6789]\d{9,9}">
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="Alternate Phone Number of the Candidate. This is not mandatory.">Alternate Phone Number</label>
                            <input type="number" aria-label="Phone Number 2" class="form-control" id="edit_phno_2" name="edit_phno_2" placeholder="1234567890" pattern = "[6789]\d{9,9}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="The organisation that the candidate belongs to.">Organisation</label>
                            <input type="text" class="form-control" name="organisation2" id="organisation2" pattern="\S(.*\S)?" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="The Current Working Location of the Candidate.">Location</label>
                            <select name="location2" id="location2" class="form-control" required>
                                <option value="">-Select-</option>
                                <?php foreach($location as $keys=>$data):?>
                                    <option value="<?php echo $data?>"><?php echo $data?></option?>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="Experience required by the candidates.">Experience</label>
                            <input type="number" step=0.1 min=1 class="form-control" name="experience2" id="experience2" placeholder="5.7" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Current CTC in Lakhs per Annum.">Current CTC (in LPA)</label>
                            <input type="number" step=0.1 min=0 class="form-control" name="cctc2" id="cctc2" placeholder="5.7" required>
                        </div>
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="right" title="Expected CTC in Lakhs per Annum.">Expected CTC (in LPA)</label>
                            <input type="number" step=0.1 min=0 class="form-control" name="ectc2" id="ectc2" placeholder="5.7" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Notice Period (in Days) Ex: 30">Notice Period (in Days)</label>
                            <input type="number" class="form-control" name="NP2" id="NP2" min=1 max=60 placeholder="30" step="1" required>
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
                    <div class="row" id="selection-data2" style="display:none">
                        <div class="form-group col">
                            <label>Planned Date of Joining</label>
                            <input type="date" class="form-control" name="plannedDOJ2" id="plannedDOJ2">
                        </div>
                        <div class="form-group col">
                            <label>Actual Date of Joining</label>
                            <input type="date" class="form-control" name="actualDOJ2" id="actualDOJ2">
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
    </div>
    <?php include('Footers/footer.php')?>
    </body>
</html>

<script>
    $(document).ready(function(){

        $("#addModal").on("hidden.bs.modal", function(){
            console.log("Closing");
            document.location.reload();
        });
        $("#editModal").on("hidden.bs.modal", function(){
            console.log("Closing");
            document.location.reload();
        });

        $("#reset-button").click(function(){
            $("#filter").val('');
        });
        var target;
        var today = new Date().toISOString().split('T')[0];
        $.noConflict();
        var table = $('#candidates').DataTable({
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
            order: [[1,"desc"]],            
            saveState: false,
            scrollX:        true,
            scrollCollapse: true,
            fixedColumns:   {
                right: 1,
                left: 0
            }
        });
        table.columns.adjust().draw();

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
            // console.log(value2);
            // console.log(value);
            // console.log(table.columns(1).search(value ? '^' + value + '$' : '', true, false).columns(5).search(value2 ? '^' + value2 + '$' : '', true, false).draw().row().count());
            // rows = table.rows({page:'current'}).data().length;
            // console.log( "Rows = ",rows)
            // if(value == "" || value2 == "")
            // {

            // }
            // else if(rows >=1)
            // {
                // document.getElementById("requiredElement").textContent = "REQUIRED";
                // var emailAdd = document.getElementById("emailAdd");
                // emailAdd.setCustomValidity("This Candidate Has Already Been Sent for this Job Title");
                // emailAdd.style.border = "2px solid red";
                // emailAdd.reportValidity();
                // alert("This Candidate Has Already Been Added");
            // }
            // else
            // {
            //     var emailAdd = document.getElementById("emailAdd");
            //     emailAdd.setCustomValidity("");
            //     document.getElementById("requiredElement").textContent = "";
            //     emailAdd.style.border = "";
            //     document.getElementById("requiredElement").hide();
            // }
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
                        console.log(res[0]);
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
            // console.log(value2);
            // console.log(value);
            // console.log(table.columns(1).search(value ? '^' + value + '$' : '', true, false).columns(5).search(value2 ? '^' + value2 + '$' : '', true, false).draw());
            // rows = table.rows({page:'current'}).data().length;
            // console.log( "Rows = ",rows)
            // if(value == "" || value2 == "")
            // {

            // }
            // else if(value != "" && value2 != "" && rows>=1)
            // {
            //     document.getElementById("requiredElement").textContent = "REQUIRED";
            //     var emailAdd = document.getElementById("emailAdd");
            //     emailAdd.setCustomValidity("This Candidate Has Already Been Sent for this Job Title");
            //     emailAdd.style.border = "2px solid red";
            //     emailAdd.reportValidity();
            //     setTimeout(2000);
            //     alert("This Candidate Has Already Been Added");
            // }
            // else
            // {
            //     var emailAdd = document.getElementById("emailAdd");
            //     emailAdd.setCustomValidity("");
            //     document.getElementById("requiredElement").textContent = "";
            //     document.getElementById("requiredElement").style.display = "none";
            // }
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
            }, 200);
        })
        $(".editButton").click(function(){
            var candidateID = $(this).data('id');
            $("#candidateID2").val(candidateID);
            var httpRequest = new XMLHttpRequest();
            var params = "candidate_id="+candidateID;
            var res;
            httpRequest.open('GET','<?php echo base_url('Candidates/GetCandidate')?>'+"?"+params, true);
            httpRequest.send();
            httpRequest.onload = function() {
                res = JSON.parse(httpRequest.responseText);
                var planned, actual;
                var candidate_name = res[0].CANDIDATE_NAME;
                var demand = res[0].DEMAND_ID;
                var jobTitle = res[1][0].JOB_TITLE;
                var status = res[0].RECRUITMENT_STATUS;
                var phno = JSON.parse(res[0].PHONE_NO);
                var email = res[0].EMAIL_ADDRESS;
                var org = res[0].ORGANISATION;
                var exp = res[0].TOTAL_EXPERIENCE;
                var cctc = res[0].CCTC_LPA;
                var ectc = res[0].ECTC_LPA;
                var dateTime = res[0].INTERVIEW_DATE;
                var split = dateTime.split(' ');
                var notice = res[0].NOTICE_PERIOD_DAYS;
                var location = res[0].WORK_LOCATION;
                var client = res[1][0].CLIENT_ID;
                var c_name = res[1][0].CLIENT_NAME;
                if(res[0].PLANED_DOJ != "0000-00-00")
                    var planned = res[0].PLANNED_DOJ;
                if(res[0].ACTUAL_DOJ != "0000-00-00");
                    var actual = res[0].ACTUAL_DOJ;
                // $(".form-group #edit_phno_1").val( phno_1 )
                // $(".form-group #phno_2").val( phno_2 )
                $(".form-group #client_id2").val( client).change();
                $(".form-group #client_id2").css('pointer-events','none');
                $("#candidateID2").val(candidateID);
                $("#demand2").val(demand);
                $(".form-group #j_title2").val( jobTitle );
                $(".form-group #j_title2").css('pointer-events','none');
                $(".form-group #organisation2").val( org ); 
                $(".form-group #edit_phno_1").val(phno[0]);
                $(".form-group #edit_phno_2").val(phno[1]);
                $(".form-group #experience2").val(exp);
                $(".form-group #candidate_name2").val(candidate_name);
                $(".form-group #c_name2").val( c_name );
                $(".form-group #cctc2").val( cctc );
                $(".form-group #interview-date2").val(split[0]);
                $(".form-group #interview-time2").val(split[1]);
                $(".form-group #ectc2").val( ectc );
                $(".form-group #NP2").val( notice );
                $(".form-group #emailAdd2").val(email);
                $(".form-group #location2").val(location).change();
            }    
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

        $('#action').on('change', function() {
        $('.status').prop('required', false);
        $('.status').val('');
        var target2=$(this).find(":selected").attr("data-target");
        var id=$(this).attr("id");
        $("div[id^='"+id+"']").hide();
        $("#"+id+"-"+target2).show();
        $("[name="+target2+"]").prop('required', true);
        if($(this).val() != "" && $(this).val() == "Selected")
        {
            $("#selection-data").show();
            $("#submissionDate").attr('max', today);
            $('#plannedDOJ, #actualDOJ').attr('min', today);
        }
        });
    

        $('#action2').on('change', function() {
            $('.status2').prop('required', false);
            var action = $(this).val();
            $('.status2').val('');
            var target4=$(this).find(":selected").attr("data-target");
            var id4=$(this).attr("id");
            $("div[id^='"+id4+"']").hide();
            $("#"+id4+"-"+target4).show();
            $("[name="+target4+"]").prop('required', true);
            if(action != "" && action == "Selected")
            {
                $("#selection-data2").show();
                $("#submissionDate2").attr('max', today);
                $('#plannedDOJ2, #actualDOJ2').attr('min', today);
            }
            else if(action == "" || action != "Selected")
            {
                $("#selection-data2").hide();
                $('#plannedDOJ2').val('');
                $('#actualDOJ2').val('');
            }
        });

        $('#interview-time').on('change',function() {
            console.log($('#interview-time').val());
        });

        // $(".search-filter").on('input', function() {
        //     console.log(table.columns(5).search(this.value).draw());
        //     console.log("Email Row = " + table.columns(5).search(this.value).draw().row().length)
        // })

        $(".dropdown-item").click(debound(filter_table, 500))
        document.getElementById('filter').addEventListener('input', function (e) {
            e.target.value = e.target.value.toLowerCase();
        });
        
        document.getElementById('filter').addEventListener('input', debound(filter_table, 500))


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

    });

</script>