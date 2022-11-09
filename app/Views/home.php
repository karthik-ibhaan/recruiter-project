<!DOCTYPE html>
<html>
    <?php include('Headers/head.php')?>
    <body>
        <?php include('Headers/header.php')?>
        <!-- Page content holder -->
        <div class="page-content p-5" id="content">
            <!--  content -->
            <div id="mainContent">
                <center><h2 class="display-4 font-weight-bold text-uppercase">home page</h2></center>
                <div class="separator"></div>
                <p class="lead mb-0 text-center text-uppercase">candidate overview</p>
                <div class="separator"></div>
                <center>
                <div class="row">
                    <div class="col square">
                        <p class="boxContent">Number of Candidates Scheduled for Interview: <?php echo $interviewDate?></p>
                    </div>
                    <div class="col square">
                        <p class="boxContent">Recently Added Candidate: <b><?php if($candidatesRecent):?><?php echo $candidatesRecent[0]['candidate_name'];?><?php endif;?></b></p>
                    </div>
                    <div class="col square">
                        <p class="boxContent">Total Number of Candidates Added by <br><b><?php echo session()->get('name')?></b>: <a class="underline" id="candidateID" href="/candidates"><?php print_r($candidatesTotal)?></a></p>
                    </div>
                </div>
                </center>
                <div class="row">
                    <?php foreach($recruitmentStatus as $key2=>$data):?>
                        <?php $count=0;?>

                        <?php foreach($candidateQueries as $key=>$value):?>
                                    <?php foreach($data as $key3=>$value2):?>
                                        <?php if($key == $value2):?>
                                            <?php $count = $count+$value?>
                                        <?php endif;?>
                                    <?php endforeach;?>
                        <?php endforeach;?>
                        <div class="col square">
                            <input type="hidden" name="status" value="<?php echo $key2?>"></input>
                            <p class="boxContent"><?php echo $key2?><br>
                            <a href="/candidates" class="underline">
                                    <?php echo $count?>
                            </a>
                            </p>
                        </div>
                    <?php endforeach;?>
                </div>
                <div class="separator"></div>
                <p class="lead mb-0 text-center text-uppercase">Demand Overview</p>
                <div class="separator"></div> 
                <?php if($demandQueries):?>
                <div class="row">
                    <?php $count=0?>
                    <?php foreach($demandQueries as $keys=>$data):?>
                        <div class="col square">
                            <?php if($count <= 2):?>
                            <a href="/demands" class="underline">
                                <p class="boxContent text-center">
                                    CLIENT NAME: <b><?php echo strtoupper($data['client_name'])?></b><br>
                                    JOB TITLE: <?php echo $data['job_title']?><br>
                                    COMPLEXITY: <?php echo $data['complexity']?>
                                </p>
                            </a>
                            <?php endif;?>
                            <?php $count++;?>
                        </div>
                    <?php endforeach;?>
                </div>
                <?php else:?>
                <div class="row">
                    <div class="col square">
                        <p class="boxContent">
                            No Demands Added, <a href="/demand" class="underline">Click Here to Add</a>
                        </p>
                    </div>
                </div>
                <?php endif;?>
                <div class="separator"></div>
                <p class="lead mb-0 text-center text-uppercase">Reports</p>
                <div class="separator"></div>
                    <div class="row">
                        <div class="col square">
                        <p class="boxContent">Space for Graph</p>
                        </div>
                        <div class="col square">
                        <p class="boxContent">Space for Graph</p>
                        </div>
                        <div class="col square">
                        <p class="boxContent">Space for Graph</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End  content -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>    <script src="https://kit.fontawesome.com/a1c2cc8f05.js" crossorigin="anonymous"></script>
        <script src="<?php echo base_url('js/main.js');?>"></script>
    </body>
</html>

<style>
    a:hover{
        color:inherit;
    }
</style>