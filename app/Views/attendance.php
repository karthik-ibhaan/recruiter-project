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

            <h2 class="text-uppercase text-center">ATTENDANCE DASHBOARD</h2>
            <br>
            <!-- <span class="row navs">
                <div class="input-group mb-3 col-2" style="width: 20%">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelect01">Month</label>
                    </div>
                    <input type="text" onkeydown="return false" class="form-control" name="datepick" id="datepick">
                </div>    
                <button class="input-group-btn btn btn-dark col-1 btn-1" style="height: 5%" name="fetch" id="fetch">Fetch</button>
                <br>
                <span id="required"></span>
            </span> -->
            <center>
            <div class="col-6">
                <button type="button" name="btn-loading" class="btn btn-loading btn-primary w-100" id="btn-loading">Loading...
                <span class="spinner spinner-border spinner-border-sm mr-3" id="spinner" role="status" aria-hidden="true">
                </span></button>
            </div>
            </center>
            <h3 class="text-center">To Check Attendance, Toggle The Options Below.</h3>            
            <ul style="list-style-type:none;">
                <center><li><h3>USER: </h3></li>
                <li>
                    <select class="form-select" name="users" id="users" onchange="jump()" style="width: auto;">
                        <option value="">- Select -</option>
                        <?php foreach($users as $keys=>$data):?>
                            <option value="<?php echo $data['user_id']?>"><?php echo $data['full_name']?></option>
                        <?php endforeach;?>
                    </select>
                </li>
                <li>
                    <span id="">
                        To Approval Multiple Deviations, Click Here:
                        <button class="btn btn-danger btn-md" data-bs-toggle="modal" id="multiApprove" data-bs-target="#approvalMultipleModal">Multiple Approval</button>
                    </span>
                </li>
                </center>
            </ul>
            <div class="wrapper" style="width:100%">
                <span class="data"></span>
                <center>
                    <ul style="list-style-type: none;" class="row">
                        <li class="col">
                            <ul style="list-style-type: none;"  id="totalDetails">
                                <li style="margin-right: 10px;"><div id="total"></div></li>
                                <li><div id="holidays"></div></li>
                            </ul>
                        </li>
                        <br>
                        <li class="col">
                            <ul style="list-style-type: none;" id="workedDetails">
                                <li style="margin-right: 10px;"><div id="worked"></div></li>
                                <li><div id="absent"></div></li>
                            </ul>
                        </li>
                    </ul>
                </center>
                <div class="container-calendar">
                    <h3 id="monthAndYear"></h3>
                    
                    <div class="button-container-calendar">
                        <button id="previous" onclick="previous()">&#8249;</button>
                        <button id="next" onclick="next()">&#8250;</button>
                    </div>
                    
                    <table class="table-calendar" id="calendar" data-lang="en" style="width: 100%">
                        <thead id="thead-month"></thead>
                        <tbody id="calendar-body"></tbody>
                    </table>
                    
                    <div class="footer-container-calendar">
                        <label for="month">Jump To: </label>
                        <select id="month" onchange="jump()">
                            <option value=0>Jan</option>
                            <option value=1>Feb</option>
                            <option value=2>Mar</option>
                            <option value=3>Apr</option>
                            <option value=4>May</option>
                            <option value=5>Jun</option>
                            <option value=6>Jul</option>
                            <option value=7>Aug</option>
                            <option value=8>Sep</option>
                            <option value=9>Oct</option>
                            <option value=10>Nov</option>
                            <option value=11>Dec</option>
                        </select>
                        <select id="year" onchange="jump()"></select>       
                    </div>
                </div>
            </div>
            <br>
            <?= form_open('Attendance/ApproveDeviation', ['id'=>'form'])?>
                <div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Deviation Approval</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="approvalDate" id="approvalDate">
                            <input type="hidden" name="approvalRecruiter" id="approvalRecruiter">
                            <span id="showData"></span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Approve Deviation</button>
                        </div>
                    </div>
                </div>
                </div>
            </form>
            <?= form_open('Attendance/ApproveMultipleDeviations', ['id'=>'form'])?>
                <div class="modal fade" id="approvalMultipleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Approve Multiple Modal</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?= csrf_field() ?>
                            <div class="row form-group">
                                <div class="col">
                                    <label><b>APPROVAL DROPDOWN</b></label>
                                    <select class="form-control" name="approvalMultipleSelect[]" id="approvalMultipleSelect" size="5" style="width: auto;" multiple>
                                    </select>
                                </div>
                                <span id="showData2" class="col"><b>SELECTED DATES:</b><br></span>
                            </div>
                            <input type="hidden" name="approvalRecruiter2" id="approvalRecruiter2">
                            <span>WOULD YOU LIKE TO APPROVE THESE DEVIATION DATES?</span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Approve Deviations</button>
                        </div>
                    </div>
                </div>
                </div>
            </form>
            <ul style="list-style-type:none;">
                <li style="margin-left:10px">
                <?= form_open('Attendance/FileExport') ?>
                    <?= csrf_field() ?>
                    <center>
                        <div>
                            <h2 class="text-center">EXPORT DATA</h2>
                            <ul style="list-style-type: none;" class="text-center form-col">
                                    <li><label>EXPORT MONTH</label></li>
                                <li>
                                    <input type="text" onkeydown="return false" class="form-control form-control-sm text-center" name="monthYear" id="datepick2" style="width: auto;" required>
                                </li>
                                <li>
                                    <input type="submit" class="btn btn-dark btn-sm" style="width:auto;" id="Export" value="Export" />
                                </li>
                            </ul>
                        </div>
                    </center>
                </form>
                </li>
                <li style="margin-left:10px">
                    <h2 class="text-center">LEAVE APPROVAL</h2>
                    <span id="Leave Approval">
                        <h3 class="text-center">
                            To Approve Leaves, Click Here:             
                            <a class="btn btn-primary btn-md" href="adminapproval">Leave Approval</a>
                        </h3>
                    </span>
                </li>
            </ul>
        </div>
        <?php include('Footers/footer.php')?>
    </body>
</html>

<style>
    #btn-loading { display:none;}
    #spinner { display:none; } 
    body.busy .spinner { display: inline-block !important; }
    body.busy .btn-loading { display: inline-block !important; }
    body.busy .navs { display: none !important}

    body {
        margin: 0;
        font-size: 1em;
    }

    .wrapper {
        margin: 15px auto;
    }
    
    li {
        display: inline-block;
    }

    #workedDetails li{
        display: block;
    }
    
    #totalDetails li {
        display: block;
    }
    .container-calendar {
        background: #ffffff;
        padding: 15px;
        margin: 0 auto;
        overflow: auto;
    }

    .button-container-calendar button {
        cursor: pointer;
        display: inline-block;
        zoom: 1;
        background: #00a2b7;
        color: #fff;
        border: 1px solid #0aa2b5;
        border-radius: 4px;
        padding: 5px 10px;
    }

    .table-calendar {
        border-collapse: collapse;
        width: 100%;
    }

    .table-calendar td, .table-calendar th {
        padding: 5px;
        border: 1px solid #e2e2e2;
        text-align: center;
        vertical-align: top;
    }

    .date-picker.selected {
        font-weight: bold;
        outline: 2px solid #1342AC;
    }

    .date-picker.selected span {
        border-bottom: 2px solid currentColor;
    }

    /* sunday */
    .date-picker:nth-child(1) {
    color: #000000;
    }

    #monthAndYear {
        text-align: center;
        margin-top: 0;
    }

    .button-container-calendar {
        position: relative;
        margin-bottom: 1em;
        overflow: hidden;
        clear: both;
    }

    #previous {
        float: left;
    }

    #next {
        float: right;
    }

    .footer-container-calendar {
        margin-top: 1em;
        border-top: 1px solid #dadada;
        padding: 10px 0;
    }

    .footer-container-calendar select {
        cursor: pointer;
        display: inline-block;
        zoom: 1;
        background: #ffffff;
        color: #585858;
        border: 1px solid #bfc5c5;
        border-radius: 3px;
        padding: 5px 1em;
    }
</style>

<script>
    $(document).ready(function(){
        var fetchMonth = new Date();
        selectYear.value = fetchMonth.getFullYear();
        selectMonth.value = fetchMonth.getMonth();
        $(".wrapper").hide();
        document.getElementById("multiApprove").disabled=true;

        $("#multiApprove").click(function(){
            $("#approvalRecruiter2").val($("#users").val());
        })

        window.onmousedown = function (e) {
            var el = e.target;
            if (el.tagName.toLowerCase() == 'option' && el.parentNode.hasAttribute('multiple')) {
                e.preventDefault();

                // toggle selection
                if (el.hasAttribute('selected')) { el.removeAttribute('selected'); $("#"+el.value).remove(); }
                else {
                    el.setAttribute('selected', ''); 
                    $("#showData2").append("<b id='"+el.value+"'>"+el.value+"<br></b>"); 
                }

                // hack to correct buggy behavior
                var select = el.parentNode.cloneNode(true);
                el.parentNode.parentNode.replaceChild(select, el.parentNode);
            }
        }
    })

    $(function() {
        $('#datepick2').datepicker({
            changeYear: true,
            changeMonth: true,
            orientation: "bottom auto",
            showButtonPanel: false,
            startView: "months",
            minViewMode: "months",
            endDate: new Date(),
            startDate: new Date("2023-01"),
            format: 'yyyy-mm',
            autoclose: true
        });
    });

    function generate_year_range(start, end) {
    var years = "";
    for (var year = start; year <= end; year++) {
        years += "<option value='" + year + "'>" + year + "</option>";
    }
    return years;
}

    today = new Date();
    currentMonth = today.getMonth();
    currentYear = today.getFullYear();
    selectYear = document.getElementById("year");
    selectMonth = document.getElementById("month");
    user = document.getElementById("users");

    createYear = generate_year_range(1970, 2050);
    /** or
     * createYear = generate_year_range( 1970, currentYear );
     */

    document.getElementById("year").innerHTML = createYear;

    var calendar = document.getElementById("calendar");
    var lang = calendar.getAttribute('data-lang');

    var months = "";
    var days = "";

    var monthDefault = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    var dayDefault = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    if (lang == "en") {
        months = monthDefault;
        days = dayDefault;
    } else if (lang == "id") {
        months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        days = ["Ming", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
    } else if (lang == "fr") {
        months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
        days = ["dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"];
    } else {
        months = monthDefault;
        days = dayDefault;
    }


    var $dataHead = "<tr>";
    for (dhead in days) {
        $dataHead += "<th data-days='" + days[dhead] + "'>" + days[dhead] + "</th>";
    }
    $dataHead += "</tr>";

    //alert($dataHead);
    document.getElementById("thead-month").innerHTML = $dataHead;


    monthAndYear = document.getElementById("monthAndYear");
    showCalendar(currentMonth, currentYear);



    function next() {
        if($("#users").val())
        {
            $("#approvalMultipleSelect").empty();document.getElementById("multiApprove").disabled=false;
            document.getElementById("next").disabled=true;
            setTimeout('document.getElementById("next").disabled=false;',1000);
            $('body').addClass('busy');
            $('.wrapper').hide();
            var todaysDate = new Date(); console.log(todaysDate);
            currentYear = (currentMonth === 11) ? currentYear + 1 : currentYear;
            currentMonth = (currentMonth + 1) % 12;
            var todaysDate = new Date(); console.log(todaysDate);
            if(currentMonth == todaysDate.getMonth() && currentYear == todaysDate.getFullYear())
            {
                $(".data").append("<center><h2>TODAY: "+todaysDate.getDate()+"-"+(todaysDate.getMonth()+1)+"-"+todaysDate.getFullYear()+"</h2><br></center>");
            }
            else
            { 
                $(".data").empty();
            }
            var date = new Date(currentYear, currentMonth);
            var month = date.getFullYear()+"-"+(date.getMonth()+1);
            $("#required").html("");
            var params = "month="+month+"&user="+user.value;
            var res;
            var xhr = new XMLHttpRequest();
            xhr.open('GET','<?php echo base_url('Attendance/FetchAttendanceOfUser')?>'+"?"+params, true);
            xhr.send();
            xhr.onload = function() {
                res = JSON.parse(xhr.responseText); 
                if(res != "")
                {                        
                    showCalendar(currentMonth, currentYear);
                    var satCount = 0; var abs = 0; var pre = 0; var hol = 0; var tot = 0;
                    Object.keys(res).forEach(function(key) {
                        var attDate = new Date(res[key].ATTENDANCE_DATE);
                        var todaysDate = new Date(); console.log(todaysDate);
                        var present = res[key].PRESENT;                    
                        var holiday = res[key].HOLIDAY;
                        var leaveApplied = res[key].LEAVE_APPLIED;
                        var leaveApproved = res[key].LEAVE_APPROVED;
                        var devApproved = res[key].DEV_APPROVED;
                        var sourced = res[key].SOURCED;
                        $("#calendar tr").each(function() {
                            var row = $(this).index();
                            $(this).find('td').each(function() {
                                if(attDate.getDate() == $(this).data('date'))
                                {

                                    if(holiday && holiday == "1")
                                    {
                                        $(this).css('background-color', 'silver');
                                        $(this).append('<br>Holiday');
                                    }
                                    else if($(this).data('date') >= todaysDate.getDate() && $(this).data('month') >= (todaysDate.getMonth()+1) && $(this).data('year') >= todaysDate.getFullYear())
                                    {
                                        
                                    }
                                    else if($(this).closest('table').find('th').eq($(this).index()).text().toLowerCase() != "sun" && $(this).closest('table').find('th').eq($(this).index()).text().toLowerCase() != "sat")
                                    {
                                        if(leaveApproved && leaveApproved == "1")
                                        {
                                            $(this).css('color', 'black');
                                            $(this).css('background-color', '#00B0F0');
                                            $(this).append('<br><small>Leave Approved</small>');
                                        }
                                        else if(leaveApplied && leaveApplied == "1")
                                        {
                                            $(this).css('color', 'black');
                                            $(this).css('background-color','#9BC2E6');
                                            $(this).append('<br><small>Leave Applied</small>');
                                        }
                                        else if(present == 0)
                                        {
                                            if(sourced > 0 && sourced < 4)
                                            {
                                                if(devApproved == 0)
                                                {
                                                    $(this).css('color', 'black');
                                                    $(this).css('background-color', '#fff9ae');
                                                    $(this).append('<br>Deviation');
                                                    $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                                    var devDate = new Date($(this).data("year")+'-'+$(this).data('month')+'-'+$(this).data('date'));
                                                    $("#approvalMultipleSelect").append("<option value='"+devDate.toJSON().slice(0,10).replace(/-/g,'-')+"'>"+devDate.toJSON().slice(0,10).replace(/-/g,'-')+"</option>");
                                                }
                                                else if(devApproved == 1)
                                                {
                                                    $(this).css('color', 'black');
                                                    $(this).css('background-color', '#ffff00');
                                                    $(this).append('<br>Deviation Approved');
                                                    $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                                }
                                            }
                                            else if(sourced >= 4)
                                            {
                                                $(this).css('color', 'black');
                                                $(this).css('background-color', '#98fb98');
                                                $(this).append('<br>Attendance Not Recorded, Present');
                                                $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                            }
                                            else if(sourced == 0)
                                            {
                                                $(this).css('color', 'black');
                                                $(this).css('background-color', '#FFCCCB');
                                                $(this).append('<br>Absent');
                                                $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                            }
                                        }
                                        else if(present == 1)
                                        {
                                            $(this).css('color', 'black');
                                            $(this).css('background-color', '#98fb98');
                                            $(this).append('<br><small>Profiles Sourced: '+sourced+'</small>');
                                        }
                                    }
                                    else if($(this).closest('table').find('th').eq($(this).index()).text().toLowerCase() == "sat")
                                    {
                                        if(present == 0)
                                        {
                                            satCount++;   
                                            
                                        }
                                        if(satCount <= 2)
                                        {
                                            if(present == 1)
                                            {
                                                $(this).css('color', 'black');
                                                $(this).css('background-color', '#98fb98');
                                                $(this).append('<br><small>Profiles Sourced: '+sourced+'</small>');
                                            }
                                            else if(present == 0)
                                            {
                                                $(this).append("<br><small>Week Off.</small>");
                                            }
                                        }
                                        if((satCount > 2 && row < 4) || (row == 4))
                                        {
                                            if(leaveApproved && leaveApproved == "1")
                                            {
                                                $(this).css('color', 'black');
                                                $(this).css('background-color', '#00B0F0');
                                                $(this).append('<br><small>Leave Approved</small>');
                                            }
                                            else if(leaveApplied && leaveApplied == "1")
                                            {
                                                $(this).css('color', 'black');
                                                $(this).css('background-color','#9BC2E6');
                                                $(this).append('<br><small>Leave Applied</small>');
                                            }
                                            else if(present == 0)
                                            {
                                                if(sourced > 0 && sourced < 4)
                                                {
                                                    if(devApproved == 0)
                                                    {
                                                        $(this).css('color', 'black');
                                                        $(this).css('background-color', '#fff9ae');
                                                        $(this).append('<br>Deviation');
                                                        $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                                        var devDate = new Date($(this).data("year")+'-'+$(this).data('month')+'-'+$(this).data('date'));
                                                        $("#approvalMultipleSelect").append("<option value='"+devDate.toJSON().slice(0,10).replace(/-/g,'-')+"'>"+devDate.toJSON().slice(0,10).replace(/-/g,'-')+"</option>");
                                                    }
                                                    else if(devApproved == 1)
                                                    {
                                                        $(this).css('color', 'black');
                                                        $(this).css('background-color', '#ffff00');
                                                        $(this).append('<br>Deviation Approved');
                                                        $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                                    }
                                                }
                                                else if(sourced >= 4)
                                                {
                                                    $(this).css('color', 'black');
                                                    $(this).css('background-color', '#98fb98');
                                                    $(this).append('<br>Attendance Not Recorded, Present');
                                                    $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                                }
                                                else if(sourced == 0)
                                                {
                                                    $(this).css('color', 'black');
                                                    $(this).css('background-color', '#FFCCCB');
                                                    $(this).append('<br>Absent');
                                                    $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                                }
                                            }
                                            else if(present == 1)
                                            {
                                                $(this).css('color', 'black');
                                                $(this).css('background-color', '#98fb98');
                                                $(this).append('<br><small>Profiles Sourced: '+sourced+'</small>');
                                            }
                                        }
                                    }
                                    else if($(this).closest('table').find('th').eq($(this).index()).text().toLowerCase() == "sun")
                                    {
                                        $(this).append('<br><small>Week Off.</small>');
                                    }
                                    if($(this).html().toLowerCase().includes("holiday") || $(this).html().toLowerCase().includes("week off"))
                                    {
                                        hol = hol + 1;
                                    }
                                    else if($(this).html().toLowerCase().includes("absent"))
                                    {
                                        abs = abs + 1;
                                    }
                                    else if($(this).html().toLowerCase().includes("deviation"))
                                    {
                                        pre = pre + 1;
                                    }
                                    else if($(this).html().toLowerCase().includes("profiles sourced"))
                                    {
                                        pre = pre + 1;
                                    }
                                    tot = tot + 1;
                                    $(this).attr('data-present', present);
                                    $(this).attr('data-sourced', sourced);
                                }
                            })
                        })
                    });
                    window.dispatchEvent(new Event('resize'));
                    $("#total").html("<h4>Total Working Days: "+tot+"</h4>");
                    $("#worked").html("<h4>Worked Days: "+pre+"</h4>");
                    $("#holidays").html("<h4>Non-Working Days: "+hol+"</h4>");
                    $("#absent").html("<h4>Absent: "+abs+"</h4>");
                    $("body").removeClass('busy');
                    $('.wrapper').show();
                }
                if(res == "")
                {
                    showCalendar(month2, year);
                    $('body').removeClass('busy');
                    $('.wrapper').show();
                }                    
            }
        }
        else
        {
            $(".wrapper").hide();
            document.getElementById("multiApprove").disabled=true;
        }
    }

    function previous() {
        if($("#users").val())
        {
        $("#approvalMultipleSelect").empty();document.getElementById("multiApprove").disabled=false;

        document.getElementById("previous").disabled=true;
        setTimeout('document.getElementById("previous").disabled=false;',1000);
        $('body').addClass('busy');
        $('.wrapper').hide();
        currentYear = (currentMonth === 0) ? currentYear - 1 : currentYear;
        currentMonth = (currentMonth === 0) ? 11 : currentMonth - 1;
        var todaysDate = new Date(); console.log(todaysDate);
        if(currentMonth == todaysDate.getMonth() && currentYear == todaysDate.getFullYear())
        {
            $(".data").append("<center><h2>TODAY: "+todaysDate.getDate()+"-"+(todaysDate.getMonth()+1)+"-"+todaysDate.getFullYear()+"</h2><br></center>");
        }
        else
        { 
            $(".data").empty();
        }
        var date = new Date(currentYear, currentMonth);
        var month = date.getFullYear()+"-"+(date.getMonth()+1);
        $("#required").html("");
        var params = "month="+month+"&user="+user.value;
        var res;
        var xhr = new XMLHttpRequest();
        xhr.open('GET','<?php echo base_url('Attendance/FetchAttendanceOfUser')?>'+"?"+params, true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText); 
            if(res != "")
            {                        
                showCalendar(currentMonth, currentYear);
                var satCount = 0; var abs = 0; var pre = 0; var hol = 0; var tot = 0;
                Object.keys(res).forEach(function(key) {
                    var attDate = new Date(res[key].ATTENDANCE_DATE);
                    var todaysDate = new Date(); console.log(todaysDate);
                    var present = res[key].PRESENT;                    
                    var holiday = res[key].HOLIDAY;
                    var leaveApplied = res[key].LEAVE_APPLIED;
                    var leaveApproved = res[key].LEAVE_APPROVED;
                    var devApproved = res[key].DEV_APPROVED;
                    var sourced = res[key].SOURCED;
                    $("#calendar tr").each(function() {
                        var row = $(this).index();
                        $(this).find('td').each(function() {
                            $(this).attr('data-present', present);
                            $(this).attr('data-sourced', sourced);
                            if(attDate.getDate() == $(this).data('date'))
                            {
                                if(holiday && holiday == "1")
                                {
                                    $(this).css('background-color', 'silver');
                                    $(this).append('<br>Holiday');
                                }
                                else if($(this).data('date') >= todaysDate.getDate() && $(this).data('month') >= (todaysDate.getMonth()+1) && $(this).data('year') >= todaysDate.getFullYear())
                                {
                                    
                                }
                                else if($(this).closest('table').find('th').eq($(this).index()).text().toLowerCase() != "sun" && $(this).closest('table').find('th').eq($(this).index()).text().toLowerCase() != "sat")
                                {
                                    if(leaveApproved && leaveApproved == "1")
                                    {
                                        $(this).css('color', 'black');
                                        $(this).css('background-color', '#00B0F0');
                                        $(this).append('<br><small>Leave Approved</small>');
                                    }
                                    else if(leaveApplied && leaveApplied == "1")
                                    {
                                        $(this).css('color', 'black');
                                        $(this).css('background-color','#9BC2E6');
                                        $(this).append('<br><small>Leave Applied</small>');
                                    }
                                    else if(present == 0)
                                    {
                                        if(sourced > 0 && sourced < 4)
                                        {
                                            if(devApproved == 0)
                                            {
                                                $(this).css('color', 'black');
                                                $(this).css('background-color', '#fff9ae');
                                                $(this).append('<br>Deviation');
                                                $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                                var devDate = new Date($(this).data("year")+'-'+$(this).data('month')+'-'+$(this).data('date'));
                                                $("#approvalMultipleSelect").append("<option value='"+devDate.toJSON().slice(0,10).replace(/-/g,'-')+"'>"+devDate.toJSON().slice(0,10).replace(/-/g,'-')+"</option>");
                                            }
                                            else if(devApproved == 1)
                                            {
                                                $(this).css('color', 'black');
                                                $(this).css('background-color', '#ffff00');
                                                $(this).append('<br>Deviation Approved');
                                                $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                            }
                                        }
                                        else if(sourced >= 4)
                                        {
                                            $(this).css('color', 'black');
                                            $(this).css('background-color', '#98fb98');
                                            $(this).append('<br>Attendance Not Recorded, Present');
                                            $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                        }
                                        else if(sourced == 0)
                                        {
                                            $(this).css('color', 'black');
                                            $(this).css('background-color', '#FFCCCB');
                                            $(this).append('<br>Absent');
                                            $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                        }
                                    }
                                    else if(present == 1)
                                    {
                                        $(this).css('color', 'black');
                                        $(this).css('background-color', '#98fb98');
                                        $(this).append('<br><small>Profiles Sourced: '+sourced+'</small>');
                                    }
                                }
                                else if($(this).closest('table').find('th').eq($(this).index()).text().toLowerCase() == "sat")
                                {
                                    if(present == 0)
                                    {
                                        satCount++;   
                                        
                                    }
                                    if(satCount <= 2)
                                    {
                                        if(present == 1)
                                        {
                                            $(this).css('color', 'black');
                                            $(this).css('background-color', '#98fb98');
                                            $(this).append('<br><small>Profiles Sourced: '+sourced+'</small>');
                                        }
                                        else if(present == 0)
                                        {
                                            $(this).append("<br><small>Week Off.</small>");
                                        }
                                    }
                                    if((satCount > 2 && row < 4) || (row == 4))
                                    {
                                        if(leaveApproved && leaveApproved == "1")
                                        {
                                            $(this).css('color', 'black');
                                            $(this).css('background-color', '#00B0F0');
                                            $(this).append('<br><small>Leave Approved</small>');
                                        }
                                        else if(leaveApplied && leaveApplied == "1")
                                        {
                                            $(this).css('color', 'black');
                                            $(this).css('background-color','#9BC2E6');
                                            $(this).append('<br><small>Leave Applied</small>');
                                        }
                                        else if(present == 0)
                                        {
                                            if(sourced > 0 && sourced < 4)
                                            {
                                                if(devApproved == 0)
                                                {
                                                    $(this).css('color', 'black');
                                                    $(this).css('background-color', '#fff9ae');
                                                    $(this).append('<br>Deviation');
                                                    $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                                    var devDate = new Date($(this).data("year")+'-'+$(this).data('month')+'-'+$(this).data('date'));
                                                    $("#approvalMultipleSelect").append("<option value='"+devDate.toJSON().slice(0,10).replace(/-/g,'-')+"'>"+devDate.toJSON().slice(0,10).replace(/-/g,'-')+"</option>");
                                                }
                                                else if(devApproved == 1)
                                                {
                                                    $(this).css('color', 'black');
                                                    $(this).css('background-color', '#ffff00');
                                                    $(this).append('<br>Deviation Approved');
                                                    $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                                }
                                            }
                                            else if(sourced >= 4)
                                            {
                                                $(this).css('color', 'black');
                                                $(this).css('background-color', '#98fb98');
                                                $(this).append('<br>Attendance Not Recorded, Present');
                                                $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                            }
                                            else if(sourced == 0)
                                            {
                                                $(this).css('color', 'black');
                                            $(this).css('background-color', '#FFCCCB');
                                            $(this).append('<br>Absent');
                                                $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                            }
                                        }
                                        else if(present == 1)
                                        {
                                            $(this).css('color', 'black');
                                            $(this).css('background-color', '#98fb98');
                                            $(this).append('<br><small>Profiles Sourced: '+sourced+'</small>');
                                        }
                                    }
                                }
                                else if($(this).closest('table').find('th').eq($(this).index()).text().toLowerCase() == "sun")
                                {
                                    $(this).append('<br><small>Week Off.</small>');
                                }
                                if($(this).html().toLowerCase().includes("holiday") || $(this).html().toLowerCase().includes("week off"))
                                {
                                    hol = hol + 1;
                                }
                                else if($(this).html().toLowerCase().includes("absent"))
                                {
                                    abs = abs + 1;
                                }
                                else if($(this).html().toLowerCase().includes("deviation"))
                                {
                                    pre = pre + 1;
                                }
                                else if($(this).html().toLowerCase().includes("profiles sourced"))
                                {
                                    pre = pre + 1;
                                }
                                tot = tot + 1;
                                $(this).attr('data-present', present);
                                $(this).attr('data-sourced', sourced);
                            }
                        })
                    })
                });
                window.dispatchEvent(new Event('resize'));
                $("#total").html("<h4>Total Working Days: "+tot+"</h4>");
                $("#worked").html("<h4>Worked Days: "+pre+"</h4>");
                $("#holidays").html("<h4>Non-Working Days: "+hol+"</h4>");
                $("#absent").html("<h4>Absent: "+abs+"</h4>");
                $("body").removeClass('busy');
                $('.wrapper').show();
            }
            if(res == "")
            {
                showCalendar(month2, year);
                $('body').removeClass('busy');
                $('.wrapper').show();
            }                    
        }
    }
        else
        {
            $(".wrapper").hide();
            document.getElementById("multiApprove").disabled=true;   
        }
    }

    function jump() {
        if($("#users").val())
        {
            $("#approvalMultipleSelect").empty();document.getElementById("multiApprove").disabled=false;
            selectYear.disabled=true;
            selectMonth.disabled=true;
            setTimeout(selectMonth.disabled=false,1000);
            setTimeout(selectYear.disabled=false,1000);
            $('body').addClass('busy');
            $('.wrapper').hide();
            currentYear = parseInt(selectYear.value);
            currentMonth = parseInt(selectMonth.value);
            var todaysDate = new Date(); console.log(todaysDate);
            if(currentMonth == todaysDate.getMonth() && currentYear == todaysDate.getFullYear())
            {
                $(".data").empty();
                $(".data").append("<center><h2>TODAY: "+todaysDate.getDate()+"-"+(todaysDate.getMonth()+1)+"-"+todaysDate.getFullYear()+"</h2><br></center>");
            }
            else
            { 
                $(".data").empty();
            }
            var date = new Date(currentYear, currentMonth);
            var month = date.getFullYear()+"-"+(date.getMonth()+1);
            $("#required").html("");
            var params = "month="+month+"&user="+user.value;
            var res;
            var xhr = new XMLHttpRequest();
            xhr.open('GET','<?php echo base_url('Attendance/FetchAttendanceOfUser')?>'+"?"+params, true);
            xhr.send();
            xhr.onload = function() {
                res = JSON.parse(xhr.responseText); 
                if(res != "")
                {                        
                    showCalendar(currentMonth, currentYear);
                    var satCount = 0; var abs = 0; var pre = 0; var hol = 0; var tot = 0;
                    Object.keys(res).forEach(function(key) {
                        var attDate = new Date(res[key].ATTENDANCE_DATE);
                        var todaysDate = new Date(); console.log(todaysDate);
                        var present = res[key].PRESENT;                    
                        var holiday = res[key].HOLIDAY;
                        var leaveApplied = res[key].LEAVE_APPLIED;
                        var leaveApproved = res[key].LEAVE_APPROVED;
                        var devApproved = res[key].DEV_APPROVED;
                        var sourced = res[key].SOURCED;
                        $("#calendar tr").each(function() {
                            var row = $(this).index();
                            $(this).find('td').each(function() {
                                $(this).attr('data-present', present);
                                $(this).attr('data-sourced', sourced);
                                if(attDate.getDate() == $(this).data('date'))
                                {

                                    if(holiday && holiday == "1")
                                    {
                                        $(this).css('background-color', 'silver');
                                        $(this).append('<br>Holiday');
                                    }
                                    else if($(this).data('date') >= todaysDate.getDate() && $(this).data('month') >= (todaysDate.getMonth()+1) && $(this).data('year') >= todaysDate.getFullYear())
                                    {
                                        
                                    }
                                    else if($(this).closest('table').find('th').eq($(this).index()).text().toLowerCase() != "sun" && $(this).closest('table').find('th').eq($(this).index()).text().toLowerCase() != "sat")
                                    {
                                        if(leaveApproved && leaveApproved == "1")
                                        {
                                            $(this).css('color', 'black');
                                            $(this).css('background-color', '#00B0F0');
                                            $(this).append('<br><small>Leave Approved</small>');
                                        }
                                        else if(leaveApplied && leaveApplied == "1")
                                        {
                                            $(this).css('color', 'black');
                                            $(this).css('background-color','#9BC2E6');
                                            $(this).append('<br><small>Leave Applied</small>');
                                        }
                                        else if(present == 0)
                                        {
                                            if(sourced > 0 && sourced < 4)
                                            {
                                                if(devApproved == 0)
                                                {
                                                    $(this).css('color', 'black');
                                                    $(this).css('background-color', '#fff9ae');
                                                    $(this).append('<br>Deviation');
                                                    $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                                    var devDate = new Date($(this).data("year")+'-'+$(this).data('month')+'-'+$(this).data('date'));
                                                    $("#approvalMultipleSelect").append("<option value='"+devDate.toJSON().slice(0,10).replace(/-/g,'-')+"'>"+devDate.toJSON().slice(0,10).replace(/-/g,'-')+"</option>");
                                                }
                                                else if(devApproved == 1)
                                                {
                                                    $(this).css('color', 'black');
                                                    $(this).css('background-color', '#ffff00');
                                                    $(this).append('<br>Deviation Approved');
                                                    $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                                }
                                            }
                                            else if(sourced >= 4)
                                            {
                                                $(this).css('color', 'black');
                                                $(this).css('background-color', '#98fb98');
                                                $(this).append('<br>Attendance Not Recorded, Present');
                                                $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                            }
                                            else if(sourced == 0)
                                            {
                                                $(this).css('color', 'black');
                                                $(this).css('background-color', '#FFCCCB');
                                                $(this).append('<br>Absent');
                                                $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                            }
                                        }
                                        else if(present == 1)
                                        {
                                            $(this).css('color', 'black');
                                            $(this).css('background-color', '#98fb98');
                                            $(this).append('<br><small>Profiles Sourced: '+sourced+'</small>');
                                        }
                                    }
                                    else if($(this).closest('table').find('th').eq($(this).index()).text().toLowerCase() == "sat")
                                    {
                                        if(present == 0)
                                        {
                                            satCount++;   
                                            
                                        }
                                        if(satCount <= 2)
                                        {
                                            if(present == 1)
                                            {
                                                $(this).css('color', 'black');
                                                $(this).css('background-color', '#98fb98');
                                                $(this).append('<br><small>Profiles Sourced: '+sourced+'</small>');
                                            }
                                            else if(present == 0)
                                            {
                                                $(this).append("<br><small>Week Off.</small>");
                                            }
                                        }
                                        if((satCount > 2 && row < 4) || (row == 4))
                                        {
                                            if(leaveApproved && leaveApproved == "1")
                                            {
                                                $(this).css('color', 'black');
                                                $(this).css('background-color', '#00B0F0');
                                                $(this).append('<br><small>Leave Approved</small>');
                                            }
                                            else if(leaveApplied && leaveApplied == "1")
                                            {
                                                $(this).css('color', 'black');
                                                $(this).css('background-color','#9BC2E6');
                                                $(this).append('<br><small>Leave Applied</small>');
                                            }
                                            else if(present == 0)
                                            {
                                                if(sourced > 0 && sourced < 4)
                                                {
                                                    if(devApproved == 0)
                                                    {
                                                        $(this).css('color', 'black');
                                                        $(this).css('background-color', '#fff9ae');
                                                        $(this).append('<br>Deviation');
                                                        $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                                        var devDate = new Date($(this).data("year")+'-'+$(this).data('month')+'-'+$(this).data('date'));
                                                        $("#approvalMultipleSelect").append("<option value='"+devDate.toJSON().slice(0,10).replace(/-/g,'-')+"'>"+devDate.toJSON().slice(0,10).replace(/-/g,'-')+"</option>");
                                                    }
                                                    else if(devApproved == 1)
                                                    {
                                                        $(this).css('color', 'black');
                                                        $(this).css('background-color', '#ffff00');
                                                        $(this).append('<br>Deviation Approved');
                                                        $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                                    }
                                                }
                                                else if(sourced >= 4)
                                                {
                                                    $(this).css('color', 'black');
                                                    $(this).css('background-color', '#98fb98');
                                                    $(this).append('<br>Attendance Not Recorded, Present');
                                                    $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                                }
                                                else if(sourced == 0)
                                                {
                                                    $(this).css('color', 'black');
                                                $(this).css('background-color', '#FFCCCB');
                                                $(this).append('<br>Absent');
                                                    $(this).append('<br><p><small>Profiles Sourced: '+sourced+'</small></p>');
                                                }
                                            }
                                            else if(present == 1)
                                            {
                                                $(this).css('color', 'black');
                                                $(this).css('background-color', '#98fb98');
                                                $(this).append('<br><small>Profiles Sourced: '+sourced+'</small>');
                                            }
                                        }
                                    }
                                    else if($(this).closest('table').find('th').eq($(this).index()).text().toLowerCase() == "sun")
                                    {
                                        $(this).append('<br><small>Week Off.</small>');
                                    }
                                    if($(this).html().toLowerCase().includes("holiday") || $(this).html().toLowerCase().includes("week off"))
                                    {
                                        hol = hol + 1;
                                    }
                                    else if($(this).html().toLowerCase().includes("absent"))
                                    {
                                        abs = abs + 1;
                                    }
                                    else if($(this).html().toLowerCase().includes("deviation"))
                                    {
                                        pre = pre + 1;
                                    }
                                    else if($(this).html().toLowerCase().includes("profiles sourced"))
                                    {
                                        pre = pre + 1;
                                    }
                                    tot = tot + 1;
                                    $(this).attr('data-present', present);
                                    $(this).attr('data-sourced', sourced);
                                }
                            })
                        })
                    });                        
                    window.dispatchEvent(new Event('resize'));
                    $("#total").html("<h4>Total Working Days: "+tot+"</h4>");
                    $("#worked").html("<h4>Worked Days: "+pre+"</h4>");
                    $("#holidays").html("<h4>Non-Working Days: "+hol+"</h4>");
                    $("#absent").html("<h4>Absent: "+abs+"</h4>");
                    $("body").removeClass('busy');
                    $('.wrapper').show();
                }
                if(res == "")
                {
                    showCalendar(month2, year);
                    $('body').removeClass('busy');
                    $('.wrapper').show();
                }                    
            }
        }
        else
        {
            $(".wrapper").hide();
            document.getElementById("multiApprove").disabled=true;
        }
    }

    function showCalendar(month, year) {

        var firstDay = ( new Date( year, month ) ).getDay();

        tbl = document.getElementById("calendar-body");

        
        tbl.innerHTML = "";

        
        monthAndYear.innerHTML = months[month] + " " + year;
        selectYear.value = year;
        selectMonth.value = month;

        // creating all cells
        var date = 1;
        for ( var i = 0; i < 6; i++ ) {
            
            var row = document.createElement("tr");

            
            for ( var j = 0; j < 7; j++ ) {
                if ( i === 0 && j < firstDay ) {
                    cell = document.createElement( "td" );
                    cellText = document.createTextNode("");
                    cell.appendChild(cellText);
                    row.appendChild(cell);
                } else if (date > daysInMonth(month, year)) {
                    break;
                } else {
                    cell = document.createElement("td");
                    cell.setAttribute("data-date", date);
                    cell.setAttribute("data-month", month + 1);
                    cell.setAttribute("data-year", year);
                    cell.setAttribute("data-month_name", months[month]);
                    cell.className = "date-picker";
                    cell.innerHTML = "<span>" + date + "</span>";

                    if ( date === today.getDate() && year === today.getFullYear() && month === today.getMonth() ) {
                        cell.className = "date-picker selected";
                    }
                    row.appendChild(cell);
                    date++;
                }
            }

            tbl.appendChild(row);
        }
    }

    $("#calendar tbody").on('td, click', function(e) {
        var searchString = "Absent";
        var searchString2 = "Deviation";
        var td = e.target.closest('td');
        if(td.innerHTML.includes(searchString2) && !td.innerHTML.includes("Approved"))
        {
            var date = td.getAttribute('data-date');
            var month_name = td.getAttribute('data-month_name');
            var month = td.getAttribute('data-month');
            var year = td.getAttribute('data-year');
            var currdate = new Date(year+"-"+month+"-"+date);
            var modal = $("#approvalModal");
            $("#approvalModal").modal("show");
            $("#approvalModal #approvalDate").val(currdate.toJSON().slice(0,10).replace(/-/g,'-'));
            $("#approvalModal #approvalRecruiter").val(user.value);
            $(".modal-body #showData").empty();
            $(".modal-body #showData").append("Date: "+date+" "+month+" "+year);
            $(".modal-body #showData").append("<br>Recruiter: "+user.options[user.selectedIndex].text);
            $(".modal-body #showData").append("<br>Do you want to approve the Deviation?");
        }
    })
    $("#approvalModal").on('hidden.bs.modal', function() {
        $("#approvalDate").val("");
        $("#approvalRecruiter").val("");
    })
    function daysInMonth(iMonth, iYear) {
        return 32 - new Date(iYear, iMonth, 32).getDate();
    }
</script>