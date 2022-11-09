<!doctype html>
<html lang="en">
    <?php include('Headers/head.php')?>
    <body>
        <?php include('Headers/header.php')?>
        <div class="page-content p-5" id="content">
            <?=form_open('DemandConfirmation/Confirm')?>
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
                        <label data-bs-toggle="tooltip" data-bs-placement="right" title="ID given in the Job Description. Ex: BNGCPT-3346">JD ID</label>
                        <input type="text" class="form-control" name="jd_id" placeholder="JD ID" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="Status of the Demand. Ex: OPEN">Demand Status</label>
                        <select name="demand_status" class="form-control" required>
                            <option value="">-Select-</option>
                            <?php foreach($status as $s):?>
                                    <option value="<?php echo strtoupper($s)?>"><?php echo strtoupper($s)?></option>
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
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="Demand Complexity. If Demand is Easy, select Low, etc.">Complexity</label>
                        <select name="complexity" class="form-control" required>
                            <option value="">-Select-</option>
                            <?php foreach($complexity as $c):?>
                                    <option class = "text-uppercase" value="<?php echo ucwords($c)?>"><?php echo ucwords($c)?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="right" title="Number of positions for current demand.">No of Positions</label>
                        <input type="number" oninvalid ="this.setCustomValidity('Enter a number that is lesser than 100.')" onvalid="this.setCustomValidity('')" class="form-control" min="1" max="100" name="no_positions" placeholder="No of Positions..." required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="The point of contact on the customer's end.">Customer SPOC</label>
                        <input type="text" class="form-control" name="cus_spoc" placeholder="Customer SPOC" required>
                    </div>
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="right" title="ID given in the Job Description. Ex: BNGCPT-3346">Ibhaan SPOC</label>
                        <input type="text" class="form-control" name="ibhaan_spoc" placeholder="Ibhaan SPOC" required>
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
                                <select name="skill" id="<?php echo $id?>" class="form-control" required>
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
                        <input type="text" class="form-control" name="band" placeholder="Band">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="Experience required by the candidates.">Required Experience</label>
                        <input type="number" step=0.1 class="form-control" name="experience" placeholder="5.7" required>
                    </div>
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="right" title="Budget Mentioned in the JD.">Budget</label>
                        <input type="number" step=0.1 class="form-control" name="budget" placeholder="5.7">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="Work Location given in the JD.">Location</label>
                        <select name="location" id="location" class="form-control">
                            <option value="">-Select-</option>
                            <?php foreach($location as $keys=>$data):?>
                                <option value="<?php echo $data?>"><?php echo $data?></option?>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="right" title="The Job Title given in the JD.">Job Title</label>
                        <input type="text" class="form-control" name="j_title" placeholder="Job Title">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="Primary Skills Mentioned in the JD.">Primary Skills</label>
                        <textarea class="form-control" name="p_skills" placeholder="Primary Skills"></textarea>
                    </div>
                    <div class="form-group col">
                        <label data-bs-toggle="tooltip" data-bs-placement="left" title="Skills that are 'good to have's in the JD.">Secondary Skills</label>
                        <textarea class="form-control" name="s_skills" placeholder="Secondary Skills"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>    <script src="https://kit.fontawesome.com/a1c2cc8f05.js" crossorigin="anonymous"></script>
        <script src="<?php echo base_url('js/main.js');?>"></script>
    </body>
</html>