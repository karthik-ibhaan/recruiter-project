<!doctype html>
<html lang="en">
    <?php include('Headers/head.php')?>
    <body>
    <?php include('Headers/header.php')?>
    <div class="page-content p-5" id="content">
        <form id="candidate_form" action="<?php echo base_url(); ?>/CandidateForm/AddCandidate" name = "candidate_form" method="post">
            <?= csrf_field() ?>
            <div class="row mb-3">
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group">
                        <span class="input-group-text">Candidate ID</span>
                        <input type="text" readonly class="form-control" id="candidateID" value="1">
                    </div>
                </div>
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group">
                        <span class="input-group-text">Customer</span>
                        <select class="form-select" aria-label="Default select example" id="customerName">
                            <option selected class="text-muted">Customer</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group">
                        <span class="input-group-text">Client</span>
                        <select class="form-select" aria-label="Default select example" id="clientName">
                            <option selected class="text-muted">Client</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group">
                        <span class="input-group-text">Job Title</span>
                        <select class="form-select" aria-label="Default select example" id="JTitle">
                            <option selected class="text-muted">Job Title</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group">
                        <span class="input-group-text">Recruitment Status</span>
                        <select class="form-select" aria-label="Default select example" id="rec_status">
                            <option selected class="text-muted">Recruitment Status</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group date" id="datepicker">
                        <span class="input-group-text">Submission Date</span>
                        <input type="date" class="form-control" id="submissionDate" max="">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
            <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group date" id="datepicker">
                        <span class="input-group-text">Interview Date</span>
                        <input type="date" class="form-control" id="interviewDate">
                    </div>
                </div>
            </div>
            <div class="separator"></div>
            <div class="row mb-3">
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group">
                        <span class="input-group-text">Candidate Name</span>
                        <input type="text" aria-label="Candidate Name" class="form-control" id="candidateName" placeholder="Candidate Name">
                    </div>
                </div>
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group">
                        <span class="input-group-text">Email Address</span>
                        <input type="text" id="emailAdd" aria-label="Email Address" class="form-control" placeholder="email@example.com">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group">
                        <span class="input-group-text">Phone Number</span>
                        <input type="text" aria-label="Phone Number 1" class="form-control" placeholder="1234567890">
                        <input type="text" aria-label="Phone Number 2" class="form-control" placeholder="1234567890">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group">
                        <span class="input-group-text">Work Location</span>
                        <select class="form-select" aria-label="Default select example" id="workLocation">
                            <option selected class="text-muted">Work Location</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group">
                        <span class="input-group-text">Total Experience</span>
                        <input type="number" step="0.01" aria-label="Total Experience" class="form-control" placeholder="Total Experience..." id="totExp">
                    </div>
                </div>
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group">
                        <span class="input-group-text">Current CTC (in LPA)</span>
                        <input type="number" step="0.01" aria-label="Current CTC" class="form-control" placeholder="5.2" id="CCTC">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group">
                        <span class="input-group-text">Expected CTC (in LPA)</span>
                        <input type="number" step="0.01" aria-label="Expected CTC" class="form-control" placeholder="5.2" id="ECTC">
                    </div>
                </div>
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group">
                        <span class="input-group-text">Notice Period (in Days)</span>
                        <input type="text" aria-label="Notice Period" name="NP" class="form-control" placeholder="30" id="NP">
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group date">
                        <span class="input-group-text">Planned Date of Joining</span>
                        <input type="date" class="form-control" id="plannedDOJ">
                    </div>
                </div>    
                <div class="mb-3 col-sm-6 col-md-6 col-lg-6 col">
                    <div class="input-group date">
                        <span class="input-group-text">Actual Date of Joining</span>
                        <input type="date" class="form-control" id="actualDOJ">
                    </div>
                </div>
            </div>
            <div class = "d-flex justify-content-center col-sm">
                <input type="submit" class="btn btn-dark btn-sm" value="Submit" />
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>    <script src="https://kit.fontawesome.com/a1c2cc8f05.js" crossorigin="anonymous"></script>
    <script src="<?php echo base_url('js/main.js');?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    </body>
</html>