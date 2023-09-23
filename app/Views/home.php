<!DOCTYPE html>
<html>
    <?php include('Headers/head.php')?>
    <body>
        <?php include('Headers/header.php')?>
        <!-- Page content holder -->
        <div class="page-content p-5" id="content">
            <!--  content -->
            <div id="mainContent">
                <center><h2 class="display-4 font-weight-bold text-uppercase">DASHBOARD</h2></center>
                <div class="separator"></div>
                <p class="lead mb-0 text-center text-uppercase">candidate overview</p>
                <div class="separator"></div>
                <div class="row">
                    <div class="col square">
                        <div class="boxContent">
                            <p class="lead mb-3 text-center text-uppercase">MONTHLY</p>
                            <div class="input-group input-group-sm mb-3 half">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">MONTH</span>
                                </div>
                                <button
                                    type="button" 
                                    class="btn btn-dark btn-sm" 
                                    data-date="<?php echo date("Y-m-01",strtotime("-1 month"))?>"
                                    name="prev"
                                    id="prev"><i class="bi bi-caret-left"></i>
                                </button>
                                <button class="btn btn-dark btn-sm" data-date="<?php echo date("Y-m-01")?>"
                                    name="curMonth"
                                    id="curMonth"><?php echo date("M Y")?>
                                </button>
                            </div>
                            <span id="monthly">
                                Month: <b><?php echo date('F Y')?></b><br>
                                Total Profiles: <?php print_r($candidatesTotal);?><br>
                                <?php foreach($candidateQueries as $status=>$value):?>
                                    <?php print_r($status)?>: <?php print_r($value);?><br>
                                <?php endforeach;?>
                                <b class="text-danger">Total (All Months) Feedback Pending: <?php print_r($feedbacksPending);?></b><br>
                            </span>
                        </div>
                    </div>
                    <div class="col-1"></div>
                    <div class="col square">
                        <div class="boxContent">
                            <p class="lead mb-3 text-center text-uppercase">DAILY</p>
                            <div class="input-group input-group-sm mb-3 half">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">DATE</span>
                                </div>
                                <button
                                    type="button" 
                                    class="btn btn-dark btn-sm" 
                                    data-date="<?php echo date("Y-m-d",strtotime("yesterday"))?>"
                                    name="prev-2"
                                    id="prev-2"><i class="bi bi-caret-left"></i>
                                </button>
                                <button class="btn btn-dark btn-sm" data-date="<?php echo date("Y-m-d")?>"
                                    name="today"
                                    id="today">TODAY
                                </button>
                            </div>
                            <span id="daily">
                                Date: <b><?php echo date('d F Y')?></b><br>
                                Total Profiles: <?php print_r($candidatesTotalToday)?><br>
                                Profiles Sent to Client: <?php print_r($candidateSentToday)?><br>
                                Interviews Scheduled: <?php print_r($interviews);?><br>
                                Demands Worked: <?php print_r($demandsTotal)?>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- <div class="separator"></div>
                <p class="lead mb-3 text-center text-uppercase">SUMMARY - FEEDBACK PENDING</p>
                <div class="feedbackData">
                    <table class="table table-striped table-bordered display nowrap" style="width:100%;" id="feedbackPendingTable" name="feedbackPendingTable">
                    </table>
                </div>
                <div class="separator"></div>
                <p class="lead mb-3 text-center text-uppercase">DEMANDS STATISTICS</p>
                <div class="demandsData">
                    <table class="table table-striped table-bordered display nowrap" style="width:100%" id="demandsTable" name="demandsTable">
                    </table>
                </div>
                <div class="separator"></div>
                <div class="separator"></div>
                <div class="separator"></div>
                <p class="lead mb-3 text-center text-uppercase">INTERVIEWS SCHEDULED</p>
                <div class="separator"></div>
                <div class="row navs">
                    <div class="col input-group input-group-sm mb-3 half">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-bold" id="basic-addon1">NAVIGATE</span>
                        </div>
                        <button
                            type="button"
                            class="btn btn-dark btn-sm" 
                            data-date="<?php echo date("Y-m-d",strtotime("-5 days"))?>"
                            name="prev5"
                            id="prev5"><i class="bi bi-caret-left"></i>
                        </button>
                        <button
                            type="button" 
                            class="btn btn-dark btn-sm" 
                            data-date="<?php echo date("Y-m-d")?>"
                            name="last5"
                            id="last5">Next 5 Days
                        </button>
                    </div>
                    <div class="col justify-content-end" style="width: auto;">
                        <input type="date" class="form-control" name="datepick" id="datepick">
                    </div>
                </div>
                <div class="separator"></div>
                <center><p id="range">Current Range: <b><?php print_r(date('Y-m-d',strtotime("today")))?></b> to <b><?php print_r(date('Y-m-d',strtotime('+5 days')))?></b></p></center>
                <div class="col-6">
                    <button type="button" name="btn-loading" class="btn btn-loading btn-primary w-100" id="btn-loading">Loading...
                    <span class="spinner spinner-border spinner-border-sm mr-3" id="spinner" role="status" aria-hidden="true">
                    </span></button>
                </div>
                <table class="table table-striped table-bordered display nowrap" style="width:100%" id="interview" name="interview">
                    <thead>
                        <tr>
                            <th>CLIENT</th>
                            <th>RECRUITER</th>
                            <th>CANDIDATE NAME</th>
                            <th>JOB TITLE</th>
                            <th>LOCATION</th>
                            <th>CUSTOMER SPOC</th>
                            <th>PHONE NO</th>
                            <th>EMAIL ADDRESS</th>
                            <th>INTERVIEW DATE</th>
                            <th>TIME</th>
                            <th>INTERVIEW STAGE</th>
                        </tr>
                    </thead>
                    <tbody class="container">
                        <?php foreach($scheduled as $key=>$data):?>
                            <tr>
                                <td><?php echo $scheduled[$key]["CLIENT_NAME"]?></td>
                                <td><?php echo $scheduled[$key]["FULL_NAME"]?></td>
                                <td><?php echo $scheduled[$key]["CANDIDATE_NAME"]?></td>
                                <td><?php echo $scheduled[$key]["JOB_TITLE"]?></td>
                                <td><?php echo $scheduled[$key]["WORK_LOCATION"]?></td>
                                <td><?php echo $scheduled[$key]["CUS_SPOC"]?></td>
                                <td><?php print_r($scheduled[$key]["PHONE_NO"])?></td>
                                <td><?php echo $scheduled[$key]["EMAIL_ADDRESS"]?></td>
                                <td><?php echo date('Y-m-d',strtotime($scheduled[$key]["INTERVIEW_DATE"]))?></td>
                                <td><?php echo $scheduled[$key]["TIME"]?></td>
                                <td><?php echo $scheduled[$key]["RECRUITMENT_STATUS"]?></td>                            
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                    <tfoot></tfoot>
                </table>
                <div class="separator"></div>
                <div id="selectionData">
                    <p class="lead mb-3 text-center text-uppercase">SELECTION DETAILS</p>
                    <div class="separator"></div>
                    <table class="table table-striped table-bordered display nowrap" style="" id="selection" name="selection">
                        <thead>
                            <tr>
                                <th>SELECTION MONTH</th>
                                <th>SELECTIONS</th>
                                <th>JOINED</th>
                                <th>DROP-OUTS</th>
                                <th>YET-TO-JOIN</th>
                                <th>MONTHLY JOINERS</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tfoot>
                    </table>
                    <div class="separator"></div>
                </div> -->
            </div>
        </div>
        <!-- End  content -->
        <?php include('Footers/footer.php')?>
    </body>
</html>

<style>
    a:hover{
        color:inherit;
    }
    #btn-loading { visibility:hidden;}
    #spinner { visibility:hidden; } 
    body.busy .spinner { visibility:visible !important; }
    body.busy .btn-loading { visibility:visible !important; }
    body.busy .navs { display: none !important}
    table thead { text-transform: uppercase;}
    .square {
        background-color: rgb(250, 242, 242);
        border: 10px solid #006CB5;
        margin:2px;
        text-align:left;
        display: inline-block;
    }

    .boxContent
    {
        text-align:left;
        margin: 10px;
    }

    .square .boxContent {
        text-align: left;
    }
</style>

<script>
    $(document).ready(function(){
        var selection = <?php echo json_encode($selections)?>;
        months = new Array();
        selectionData = {};
        selectionCount = 0;
        for( var key in selection )
        {
            var month = new Date(selection[key].SELECTION_DATE);
            if(month.getFullYear() < "2022")
            {
                continue;
            }
            var selectionMonth = month.toJSON().slice(0,7).replace(/-/g,'-');
            if(selectionMonth === "1970-01" || selectionMonth === "" || selectionMonth === null)
            {
            }
            if(!selectionData[selectionMonth])
            {
                selectionData[selectionMonth] = {};
                selectionData[selectionMonth]["MONTHYEAR"] = month.toLocaleString('default', {month: 'long', year: 'numeric'});
                selectionData[selectionMonth]["SELECTIONS"] = 0;
                selectionData[selectionMonth]["JOINED"] = 0;
                selectionData[selectionMonth]["DROPOUTS"] = 0;
                selectionData[selectionMonth]["YETTOJOIN"] = 0;
                selectionData[selectionMonth]["MONTHLYJOINERS"] = 0;
            }
            // months.indexOf(selectionMonth) === -1 ? months.push(selectionMonth) : console.log("This item already exists");
            selectionData[selectionMonth]["SELECTIONS"] = selectionData[selectionMonth]["SELECTIONS"] + 1;
            const regexp = new RegExp('^(11|09)');
            if(regexp.test(selection[key].RECRUITMENT_STATUS))
            {
                selectionData[selectionMonth]["DROPOUTS"] = selectionData[selectionMonth]["DROPOUTS"] + 1;
            }
            var month2 = selection[key].ACTUAL_DOJ;

            if(String(month2) === "0000-00-00" || String(month2) === "" || String(month2) === null)
            {
                continue;
            }
            var joinDate = new Date(month2);
            var joinMonth = joinDate.toJSON().slice(0,7).replace(/-/g,'-');
            if(joinMonth === "0000-00" || joinMonth === "1970-01" || joinMonth === null)
            {
                continue;
            }
            const regexp3 = new RegExp('^10');
            if(regexp3.test(selection[key].RECRUITMENT_STATUS))
            {
                selectionData[selectionMonth]["JOINED"] = selectionData[selectionMonth]["JOINED"] + 1;
            }
            if(regexp3.test(selection[key].RECRUITMENT_STATUS))
            {
                if(!selectionData[joinMonth])
                {
                    selectionData[joinMonth] = {};
                    selectionData[joinMonth]["MONTHYEAR"] = joinDate.toLocaleString('default', {month: 'long', year: 'numeric'});
                    selectionData[joinMonth]["SELECTIONS"] = 0;
                    selectionData[joinMonth]["JOINED"] = 0;
                    selectionData[joinMonth]["DROPOUTS"] = 0;
                    selectionData[joinMonth]["YETTOJOIN"] = 0;
                    selectionData[joinMonth]["MONTHLYJOINERS"] = 0;
                }
                selectionData[joinMonth]["MONTHLYJOINERS"] = selectionData[joinMonth]["MONTHLYJOINERS"] + 1;
            }
        }
        selectionDetails = [];
        var count = 0;
        for(var temp in selectionData)
        {
            selectionDetails[count] = selectionData[temp];
            count++;
        }
        for(var temp=0;temp<count;temp++)
        {
            console.log(selectionDetails[temp]);
            selectionDetails[temp].YETTOJOIN = selectionDetails[temp].SELECTIONS - selectionDetails[temp].JOINED - selectionDetails[temp].DROPOUTS;
        }
        
        var table = $('#interview').DataTable({
            columns: [
                { data: "CLIENT_NAME"},
                { data: "FULL_NAME"},
                { data: "CANDIDATE_NAME"},
                { data: "JOB_TITLE"},
                { data: "WORK_LOCATION"},
                { data: "CUS_SPOC"},
                { data: "PHONE_NO"},
                { data: "EMAIL_ADDRESS"},
                { data: "INTERVIEW_DATE"},
                { data: "TIME"},
                { data: "RECRUITMENT_STATUS"},
            ],
            scrollX: true,
            scrollCollapse: true,
            orderBy: [[0, "asc"]]
        });

        var $container = $("#interview_wrapper .container");
        var $scroller = $("#interview_wrapper .dataTables_scrollBody");
        
        bindDragScroll($container, $scroller);
        window.dispatchEvent(new Event('resize'));
        function getFileName2(){
            let today = new Date().toISOString().slice(0, 10)
            return 'Current Total Selection Details - '+today;
        }
        $.fn.dataTable.moment('MMMM YYYY');
        var table2 = $('#selection').DataTable({
            // dom: 'Bfrtip',
            // buttons: ['copy', {extend: 'csv', filename: function() { return getFileName2();}}, {extend: 'excel', filename: function() { return getFileName2();}}, {extend: 'pdf', filename: function() { return getFileName2();}}, 'print'],
            paging: true,
            columns: [
                { data: "MONTHYEAR", title: "MONTHYEAR" },
                { data: "SELECTIONS", title: "SELECTIONS" },
                { data: "JOINED", title: "JOINED" },
                { data: "DROPOUTS", title: "DROPOUTS" },
                { data: "YETTOJOIN", title:"YETTOJOIN" },
                { data: "MONTHLYJOINERS", title: "MONTHLYJOINERS" },
            ],
            data: selectionDetails,
            scrollX: true,
            scrollCollapse: true,
            responsive: true,
            orderBy: [[0, "asc"]],
            drawCallback: function () {
                var api = this.api();
                var sum = 0;
                var formated = 0;
                //to show first th
                $(api.column(0).footer()).html('Sub-Total');

                for(var i=1; i<=5;i++)
                {
                    sum = api.column(i, {page:'current'}).data().sum();

                    //to format this sum
                    formated = parseInt(sum);
                    $(api.column(i).footer()).html(formated);
                }
		    }

        });
        $.fn.dataTable.moment('MMMM YYYY');
        table2.columns.adjust().draw();

        getFeedbackDetails();
        getDemandsData();
    })

    function getDemandsData() {
    var xhr = new XMLHttpRequest();
    var res;
    var demandsData = [];
    var demandsTableHtml = document.getElementById('demandsTable');
    demandsTableHtml.innerHTML = '<tfoot></tfoot>';
    xhr.open('GET', '<?php echo base_url('Home/GetDemandDetails')?>', true);
    xhr.send();
    xhr.onload = function() {
        res = JSON.parse(xhr.responseText);
        var demandsTableFoot = document.getElementById('demandsTable').tFoot.insertRow();
        Object.entries(res[0]).forEach(([key2, value2]) => {
            demandsTableFoot.insertCell();            
        });
        var demandsTable = $("#demandsTable").DataTable({
            data: res,
            columns: 
            [
                { data: "RECRUITER", title: "RECRUITER"},
                { data: "OPEN", title: "OPEN"},
                { data: "HOLD", title: "HOLD"},
                { data: "CLOSED", title: "CLOSED"},
                { data: "GRANDTOTAL", title: "GRAND TOTAL"}
            ],
            drawCallback: function () {
                var api = this.api();
                var sum = 0;
                var formated = 0;
                //to show first th
                $(api.column(0).footer()).html('Sub-Total');

                for(var i=1; i<5;i++)
                {
                    sum = api.column(i, {page:'current'}).data().sum();

                    //to format this sum
                    formated = parseInt(sum);
                    $(api.column(i).footer()).html(formated);
                }
            }
        });
    }
}

    function getFeedbackDetails() {
        var sourcedTable = document.getElementById("feedbackPendingTable");
        sourcedTable.innerHTML = "<tfoot></tfoot>";
        var footer = sourcedTable.tFoot.insertRow();
        var xhr = new XMLHttpRequest();
        var res;
        xhr.open('GET','<?php echo base_url('Home/GetFeedbackPendingDetails')?>', true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            result = {};
            if(res != "")
            {
                let overall = {};
                var obj = res[2];
                let users = [];
                users.push("Client");
                users.push("Total");
                for(var key in obj)
                {
                    if(obj[key].length > 0)
                    {
                        overall[key] = {};
                        let counts = {};
                        let lookup = {};
                        let count = 0;
                        const removeDuplicates = (array) => [...new Set (array)];
                        for(var i=0;i<obj[key].length; i++)
                        {
                            let name = obj[key][i].FULL_NAME;
                            if(!(name in lookup))
                            {
                                lookup[name] = 1;
                                users.push(name);
                            }
                            else if(name in lookup)
                            {
                                lookup[name] = lookup[name]+1;
                            }
                        }
                        overall[key] = lookup;
                        overall[key]["Total"] = [];
                        overall[key]["Total"] = obj[key].length;
                    }
                }
                users = new Set(users);
                var keys = new Set();
                var overallPending = [];
                var i=0;
                for(var key in overall)
                {
                    overallPending[i] = {};
                    overallPending[i]['Client'] = key;
                    overall[key]['Client'] = key;
                    let columnHeaders = new Set(Object.keys(overall[key]));
                    let a_minus_b = new Set([...users].filter(x => !columnHeaders.has(x)));
                    Object.entries(overall[key]).forEach(([key2, value2]) => {
                        overallPending[i][key2] = value2;
                    });
                    a_minus_b.forEach(diff => {
                        overallPending[i][diff] = 0;
                    })
                    i=i+1;
                }
                columns = [];
                users.forEach(key => {
                    columns.push({
                        data: key,
                        title: key
                    })
                    footer.insertCell();
                })
                overall = {};
                var sourcedTable2 = $("#feedbackPendingTable").DataTable({
                    columns: columns,
                    data: overallPending,
                    scrollX: true,
                    scrollCollapse: true,
                    drawCallback: function () {
                        var api = this.api();
                        var sum = 0;
                        var formated = 0;
                        //to show first th
                        $(api.column(0).footer()).html('Sub-Total');

                        for(var i=1; i<users.size;i++)
                        {
                            sum = api.column(i, {page:'all'}).data().sum();
                            //to format this sum
                            formated = parseInt(sum);
                            $(api.column(i).footer()).html(formated);
                        }
                    }
                });
                // var sourcedTable2 = $(".feedbackData #feedbackPendingTable").DataTable({
                //     dom: 'Bfrtip',
                //     buttons: ['copy', {extend: 'csv', filename: function() { return getFeedbackFileName();}}, {extend: 'excel', filename: function() { return getFeedbackFileName();}}, {extend: 'pdf', filename: function() { return getFileName();}}, 'print'],
                //     order: [["0",'asc']],
                //     scrollX: true,
                //     scrollCollapse: true,
                //     autoWidth: false,
                // });
                $(".feedbackData #feedbackPendingTable_wrapper").show();
                window.dispatchEvent(new Event('resize'));
            }
        }
    }

    function getFeedbackFileName(){
        let today = new Date().toISOString().slice(0, 10)
        return 'Feedback Pending Details - '+today;
    }    
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

    $(document).ready( function(){
        if(<?php echo session()->get('level')?> == "3")
        {
            $('td:nth-child(2), th:nth-child(2)').hide();
            $("#selectionData").hide();
            window.dispatchEvent(new Event('resize'));
        }
    })
    $('#today').click(function(){
        var date = $(this).data('date');
        var prev = new Date(date);
        var dateString = new Date(date);
        prev.setDate(prev.getDate() - 1);
        $('#prev-2').data('date', prev.toJSON().slice(0,10).replace(/-/g,'-'));
        var xhr = new XMLHttpRequest();
        var params = "date="+date;
        var res;
        xhr.open('GET','<?php echo base_url('Home/GetDailyData')?>'+"?"+params, true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            result = {};
            if(res != "")
            {
                $("#daily").html("");
                $("#daily").append("Date: <b>"+dateString.toLocaleString('default', {day: 'numeric', month: 'long', year: 'numeric'})+"</b>");
                $("#daily").append("<br>");
                $("#daily").append('Total Profiles: '+res[0]);
                $("#daily").append("<br>");
                $("#daily").append('Profiles Sent to Client: '+res[2]);
                $("#daily").append("<br>");
                $("#daily").append('Interviews Scheduled: '+res[3]);
                $("#daily").append("<br>");
                $("#daily").append('Demands Worked: '+res[1]);
                $("#daily").append("<br>");
            }
        }
    })
    $('#prev-2').click(function(){
        var date = $(this).data('date');
        var prev = new Date(date);
        var dateString = new Date(date);
        prev.setDate(prev.getDate() - 1);
        $('#prev-2').data('date', prev.toJSON().slice(0,10).replace(/-/g,'-'));
        var xhr = new XMLHttpRequest();
        var params = "date="+date;
        var res;
        xhr.open('GET','<?php echo base_url('Home/GetDailyData')?>'+"?"+params, true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            result = {};
            if(res != "")
            {
                $("#daily").html("");
                $("#daily").append("Date: <b>"+dateString.toLocaleString('default', {day: 'numeric', month: 'long', year: 'numeric'})+"</b>");
                $("#daily").append("<br>");
                $("#daily").append('Total Profiles: '+res[0]);
                $("#daily").append("<br>");
                $("#daily").append('Profiles Sent to Client: '+res[2]);
                $("#daily").append("<br>");
                $("#daily").append('Interviews Scheduled: '+res[3]);
                $("#daily").append("<br>");
                $("#daily").append('Demands Worked: '+res[1]);
                $("#daily").append("<br>");
            }
        }
    })

    $('#curMonth').click(function(){
        var date = $(this).data('date');
        var prev = new Date(date);
        var dateString = new Date(date);
        prev.setMonth(prev.getMonth() - 1);
        $('#prev').data('date', prev.toJSON().slice(0,10).replace(/-/g,'-'));
        var xhr = new XMLHttpRequest();
        var params = "date="+date;
        var res;
        xhr.open('GET','<?php echo base_url('Home/GetMonthlyData')?>'+"?"+params, true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            result = {};
            if(res != "")
            {
                $("#monthly").html("");
                $("#monthly").append("Month: <b>"+dateString.toLocaleString('default', {month: 'long', year: 'numeric'})+"</b>");
                $("#monthly").append('<br>');
                $("#monthly").append('Total Profiles: '+res[1]);
                $("#monthly").append('<br>');
                for(var k in res[0])
                {
                    $("#monthly").append(k+": "+res[0][k]);
                    $("#monthly").append('<br>');
                }
                $("#monthly").append('<b class="text-danger">Total (All Months) Feedback Pending: '+res[2]+'</b>');
                $("#monthly").append('<br>');
            }
        }
    })

    $('#prev').click(function(){
        var date = $(this).data('date');
        var prev = new Date(date);
        var dateString = new Date(date);
        prev.setMonth(prev.getMonth() - 1);
        $('#prev').data('date', prev.toJSON().slice(0,10).replace(/-/g,'-'));
        var xhr = new XMLHttpRequest();
        var params = "date="+date;
        var res;
        xhr.open('GET','<?php echo base_url('Home/GetMonthlyData')?>'+"?"+params, true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            result = {};
            if(res != "")
            {
                $("#monthly").html("");
                $("#monthly").append("Month: <b>"+dateString.toLocaleString('default', {month: 'long', year: 'numeric'})+"</b>");
                $("#monthly").append('<br>');
                $("#monthly").append('Total Profiles: '+res[1]);
                $("#monthly").append('<br>');
                for(var k in res[0])
                {
                    $("#monthly").append(k+": "+res[0][k]);
                    $("#monthly").append('<br>');
                }
                $("#monthly").append('<b class="text-danger">Total (All Months) Feedback Pending: '+res[2]+'</b>');
                $("#monthly").append('<br>');
            }
        }
    })

    $('#last5').click(function(){
        $('body').addClass('busy');
        $('#interview_wrapper').hide();
        var date = $(this).data('date');
        var cur = new Date(date);
        $("#datepick").val(date);
        var prev = new Date(date);
        prev.setDate(prev.getDate() - 5);
        $('#prev5').data('date', prev.toJSON().slice(0,10).replace(/-/g,'-'));
        var xhr = new XMLHttpRequest();
        var params = "date="+date;
        var res;
        xhr.open('GET','<?php echo base_url('Home/GetInterviewDetails')?>'+"?"+params, true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            if(res != "")
            {
                if( $.fn.DataTable.isDataTable('#interview'))
                {
                    var table = $("#interview").DataTable();
                    table.clear();
                    table.rows.add( res ).draw();                
                    table.columns.adjust().draw();
                    window.dispatchEvent(new Event('resize'));
                }
                $("#range").html('');
                $("#range").append('Current Range: <b>'+cur.toJSON().slice(0,10).replace(/-/g,'-')+'</b> to ');
                cur.setDate(cur.getDate() + 5);
                $("#range").append('<b>'+cur.toJSON().slice(0,10).replace(/-/g,'-')+'</b>');
                table.columns.adjust().draw();
                window.dispatchEvent(new Event('resize'));
                $('#interview_wrapper').show();
            }
            if(res === "")
            {
                if( $.fn.DataTable.isDataTable('#interview'))
                {
                    var table = $("#interview").DataTable();
                    table.clear();
                    table.rows.add( res ).draw();                
                    table.columns.adjust().draw();
                    window.dispatchEvent(new Event('resize'));
                }
                $("#range").html('');
                $("#range").append('Current Range: <b>'+cur.toJSON().slice(0,10).replace(/-/g,'-')+'</b> to ');
                cur.setDate(cur.getDate() + 5);
                $("#range").append('<b>'+cur.toJSON().slice(0,10).replace(/-/g,'-')+'</b>');
                $('body').removeClass('busy');
                $('#interview_wrapper').show();
            }
            $('body').removeClass('busy');
            if(<?php echo session()->get('level')?> === "3")
            {
                $('td:nth-child(2), th:nth-child(2)').hide();
                window.dispatchEvent(new Event('resize'));
            }
            else
            {
                window.dispatchEvent(new Event('resize'));
            }
        }
    })

    $('#prev5').click(function(){
        $('body').addClass('busy');
        $('#interview_wrapper').hide();
        var date = $(this).data('date');
        $("#datepick").val(date);
        var cur = new Date(date);
        var prev = new Date(date);
        prev.setDate(prev.getDate() - 5);
        $('#prev5').data('date', prev.toJSON().slice(0,10).replace(/-/g,'-'));
        var xhr = new XMLHttpRequest();
        var params = "date="+date;
        var res;
        xhr.open('GET','<?php echo base_url('Home/GetInterviewDetails')?>'+"?"+params, true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            if(res != "")
            {
                if( $.fn.DataTable.isDataTable('#interview'))
                {
                    var table = $("#interview").DataTable();
                    table.clear();
                    table.rows.add( res ).draw();                
                    table.columns.adjust().draw();
                    window.dispatchEvent(new Event('resize'));
                }              
                $("#range").html('');
                $("#range").append('Current Range: <b>'+cur.toJSON().slice(0,10).replace(/-/g,'-')+'</b> to ');
                cur.setDate(cur.getDate() + 5);
                $("#range").append('<b>'+cur.toJSON().slice(0,10).replace(/-/g,'-')+'</b>');
                table.columns.adjust().draw();
                window.dispatchEvent(new Event('resize'));
                $('#interview_wrapper').show();
            }
            if(res === "")
            {
                if( $.fn.DataTable.isDataTable('#interview'))
                {
                    var table = $("#interview").DataTable();
                    table.clear();
                    table.rows.add( res ).draw();                
                    table.columns.adjust().draw();
                    window.dispatchEvent(new Event('resize'));
                }
                $("#range").html('');
                $("#range").append('Current Range: <b>'+cur.toJSON().slice(0,10).replace(/-/g,'-')+'</b> to ');
                cur.setDate(cur.getDate() + 5);
                $("#range").append('<b>'+cur.toJSON().slice(0,10).replace(/-/g,'-')+'</b>');
                $('body').removeClass('busy');
                $('#interview_wrapper').show();
            }
            $('body').removeClass('busy');
            if(<?php echo session()->get('level')?> === "3")
            {
                $('td:nth-child(2), th:nth-child(2)').hide();
                window.dispatchEvent(new Event('resize'));
            }
            else
            {
                window.dispatchEvent(new Event('resize'));
            }
        }
    })

    $('#datepick').change(function() {
        if($(this).val() != "")
        {
            $('body').addClass('busy');
            $('#interview_wrapper').hide();
            var date = $(this).val();
            var cur = new Date(date);
            var xhr = new XMLHttpRequest();
            var params = "date="+date;
            var res;
            xhr.open('GET','<?php echo base_url('Home/GetInterviewDetails')?>'+"?"+params, true);
            xhr.send();
            xhr.onload = function() {
                res = JSON.parse(xhr.responseText);
                if(res != "")
                {
                    if( $.fn.DataTable.isDataTable('#interview'))
                    {
                        var table = $("#interview").DataTable();
                        table.clear();
                        table.rows.add( res ).draw();                
                        table.columns.adjust().draw();
                        window.dispatchEvent(new Event('resize'));
                    }               
                    $("#range").html("");
                    $("#range").append('Current Range: <b>'+cur.toJSON().slice(0,10).replace(/-/g,'-')+'</b> to ');
                    cur.setDate(cur.getDate() + 5);
                    $("#range").append('<b>'+cur.toJSON().slice(0,10).replace(/-/g,'-')+'</b>');
                    $('#interview_wrapper').show();
                }
                if(res === "")
                {
                    if( $.fn.DataTable.isDataTable('#interview'))
                    {
                        var table = $("#interview").DataTable();
                        table.clear();
                        table.rows.add( res ).draw();                
                        table.columns.adjust().draw();
                        window.dispatchEvent(new Event('resize'));
                    }             
                    $("#range").html("");
                    $("#range").append('Current Range: <b>'+cur.toJSON().slice(0,10).replace(/-/g,'-')+'</b> to ');
                    cur.setDate(cur.getDate() + 5);
                    $("#range").append('<b>'+cur.toJSON().slice(0,10).replace(/-/g,'-')+'</b>');
                    $('body').removeClass('busy');
                    $('#interview_wrapper').show();
                }
                $('body').removeClass('busy');
                if(<?php echo session()->get('level')?> === "3")
                {
                    $('td:nth-child(2), th:nth-child(2)').hide();
                    window.dispatchEvent(new Event('resize'));
                }
                else
                {
                    window.dispatchEvent(new Event('resize'));
                }
            }
        }
    })
</script>