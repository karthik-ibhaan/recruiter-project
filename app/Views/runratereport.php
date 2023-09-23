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

            <?php if(session()->getFlashdata('count') !== NULL):?>
                <div class="alert alert-success">
                    Successfully inserted <?php echo session()->get('count') ?> records.
                </div>
            <?php endif;?>

            <?php if(session()->getFlashdata('success') !== NULL):?>
                <div class="alert alert-success">
                    <?php echo session()->getFlashdata('success')?>
                </div>
            <?php endif;?>

            <h2 class="text-uppercase text-center">RUN RATE STATISTICS</h2>
            <div class="col navs">
                <label>Navigate:</label>
                <button
                    type="button" 
                    class="btn btn-dark btn-sm" 
                    data-date="<?php echo date("Y-m-01",strtotime("-1 month"))?>"
                    name="prev"
                    id="prev"><i class="bi bi-caret-left"></i>
                </button>

                <button
                    type="button" 
                    class="btn btn-dark btn-sm" 
                    data-date="<?php echo date("Y-m-01")?>"
                    name="curMonth"
                    id="curMonth">This Month
                </button>
            </div>
            <br>
            <center>
            <div class="col-6 justify-content-center">
                <button type="button" name="btn-loading" class="btn btn-loading btn-primary w-100" id="btn-loading">Loading...
                <span class="spinner spinner-border spinner-border-sm mr-3" id="spinner" role="status" aria-hidden="true">
                </span></button>
            </div>
            </center>
            <br>
            <center><b><h3 id="displayDetails"></h3></b></center>
            <h3 class="text-center">INTERVIEWS SCHEDULED</h3>
            <div style="overflow-x: auto;">
                <table class="table table-striped table-bordered display nowrap" style="width:100%" id="interview" name="interview">
                </table>
            </div>
            <br>
            <br>
            <h3 class="text-center">SOURCED PROFILES</h3>
            <div style="overflow-x: auto;">
                <table class="table table-striped table-bordered display nowrap" style="width:100%" id="sourced" name="sourced">
                </table>
            </div>
            <br>
            <br>
            <h3 class="text-center">RUN RATE</h3>
            <div style="overflow-x: auto;">
                <table class="table table-striped table-bordered display nowrap" style="width:100%" id="hitratio" name="hitratio">
                </table>
            </div>
            <br>
            <br>
        </div>
        <?php include("Footers/footer.php")?>
    </body>
</html>

<style>
    #btn-loading { visibility:hidden;}
    #spinner { visibility:hidden; } 
    body.busy .spinner { visibility:visible !important; }
    body.busy .btn-loading { visibility:visible !important; }
    body.busy .navs { display: none !important}
    table thead { text-transform: uppercase;}
    table td.selected {
        background-color: #98fb98;
    }
</style>


<script>
    $(document).ready(function() {
        var values = <?php echo $details?>;
        var sourced, interview, hitratio;
        if(values)
        {
            $('#displayDetails').html("MONTH: "+$("#curMonth").data("date").slice(0,7));
            headers="<thead><tr><th>RECRUITER</th>";
            for(dates in values[0]["DATES"])
            {
                headers=headers+"<th>"+values[0]["DATES"][dates]+"</th>";
            }
            headers=headers+"<th>TOTAL</th><th>RUN RATE</th></tr></thead>";
            headerValues = Object.keys(values);
            // var recruiterIDs = {};
            sourced=headers;
            interview=headers;
            sourced=sourced+"<tbody>";
            interview=interview+"<tbody>";
            hitratio="<thead><tr><th>RECRUITER</th><th>HIT RATIO</th></tr></thead><tbody>";

            for(keys in values)
            {
                var date = new Date();
                var arraySourced = []; 
                var arrayInterview = [];
                const average = arr => arr.reduce( ( p, c ) => p + c, 0 ) / arr.length;
                sourced=sourced+"<tr><td>"+values[keys]["RECRUITER_NAME"]+"</td>";
                interview=interview+"<tr><td>"+values[keys]["RECRUITER_NAME"]+"</td>";
                hitratio=hitratio+"<tr><td>"+values[keys]["RECRUITER_NAME"]+"</td>";
                for(data in values[keys]["SOURCED"])
                {
                    sourced=sourced+"<td>"+values[keys]["SOURCED"][data]+"</td>";
                    if(data <= date.getDate())
                    {
                        arraySourced.push(values[keys]["SOURCED"][data]);
                    }
                }
                sourcedAvg = arraySourced.reduce((c, p) => c + p)/arraySourced.length;
                sourcedAvg = Math.round((sourcedAvg + Number.EPSILON) * 100) / 100;
                sourcedTot = arraySourced.reduce( (a,b) => a+b);
                console.log(sourcedTot);
                sourced=sourced+"<td>"+sourcedTot+"</td>"+"<td>"+sourcedAvg+"</td>"
                sourced=sourced+"</tr>";
                for(data2 in values[keys]["INTERVIEWS"])
                {
                    interview=interview+"<td>"+values[keys]["INTERVIEWS"][data2]+"</td>";
                    if(data2 <= date.getDate())
                    {
                        arrayInterview.push(values[keys]["INTERVIEWS"][data2]);
                    }
                }
                console.log(arrayInterview, arrayInterview.length);
                interviewAvg = arrayInterview.reduce((c, p) => c + p)/arrayInterview.length;
                interviewAvg = Math.round((interviewAvg + Number.EPSILON) * 100) / 100;
                interviewTot = arrayInterview.reduce( (a,b) => a+b );
                console.log("INTERVIEW TOTAL " + interviewTot);
                // interview=interview+"<td>"+interviewTot+"</td>+"<td>"+interviewAvg+"</td>";
                interview=interview+"<td>"+interviewTot+"</td>"+"<td>"+interviewAvg+"</td>"
                interview=interview+"</tr>";
                runrate = Math.round(((interviewAvg / sourcedAvg) + Number.EPSILON) * 100) / 100;
                if(runrate)
                {
                    hitratio = hitratio+"<td>"+runrate+"</td></tr>";
                }
                else
                {
                    hitratio = hitratio+"<td>0</td>";
                }
            }
            sourced=sourced+"</tbody>";
            hitratio=hitratio+"</tbody>";
            interview=interview+"</tbody>";
            $("#interview").empty();
            $("#sourced").empty();
            $("#hitratio").empty();

            $(hitratio).appendTo("#hitratio");
            $(sourced).appendTo("#sourced");
            $(interview).appendTo("#interview");
            
            var $currentTable = $("#sourced");
            $currentTable.find('td').removeClass('selected');
            $currentTable.find('td').each(function() {
                if($(this).closest('table').find('th').eq($(this).index()).text().toLowerCase() == date.getDate())
                {
                    $(this).addClass('selected');
                }
            });
            var $currentTable2 = $("#interview");
            $currentTable2.find('td').removeClass('selected');
            $currentTable2.find('td').each(function() {
                if($(this).closest('table').find('th').eq($(this).index()).text().toLowerCase() == date.getDate())
                {
                    $(this).addClass('selected');
                }
            });
        }
    })

    $('#curMonth').click(function(){
        $('body').addClass('busy');
        $('table').hide();
        $('#displayDetails').hide();
        var table = document.getElementById("sourced");
        table.innerHTML = "";
        var date = $(this).data('date');
        $('#displayDetails').html('MONTH: '+date.slice(0,7));
        var prev = new Date(date);
        prev.setMonth(prev.getMonth() - 1);
        $('#prev').data('date', prev.toJSON().slice(0,10).replace(/-/g,'-'));
        var xhr = new XMLHttpRequest();
        var params = "date="+date;
        var res;
        xhr.open('GET','<?php echo base_url('RunRateReport/GetDataOfMonth')?>'+"?"+params, true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            var values = res;
            var sourced, interview, hitratio;
            if(values)
            {
                headers="<thead><tr><th>RECRUITER</th>";
                for(dates in values[0]["DATES"])
                {
                    headers=headers+"<th>"+values[0]["DATES"][dates]+"</th>";
                }
                headers=headers+"<th>RUN RATE</th></tr></thead>";
                headerValues = Object.keys(values);
                // var recruiterIDs = {};
                sourced=headers;
                interview=headers;
                sourced=sourced+"<tbody>";
                interview=interview+"<tbody>";
                hitratio="<thead><tr><th>RECRUITER</th><th>HIT RATIO</th></tr></thead><tbody>";

                for(keys in values)
                {
                    var date = new Date();
                    var arraySourced = []; 
                    var arrayInterview = [];
                    const average = arr => arr.reduce( ( p, c ) => p + c, 0 ) / arr.length;
                    sourced=sourced+"<tr><td>"+values[keys]["RECRUITER_NAME"]+"</td>";
                    interview=interview+"<tr><td>"+values[keys]["RECRUITER_NAME"]+"</td>";
                    hitratio=hitratio+"<tr><td>"+values[keys]["RECRUITER_NAME"]+"</td>";
                    for(data in values[keys]["SOURCED"])
                    {
                        sourced=sourced+"<td>"+values[keys]["SOURCED"][data]+"</td>";
                        if(data <= date.getDate())
                        {
                            arraySourced.push(values[keys]["SOURCED"][data]);
                        }
                    }
                    sourcedAvg = arraySourced.reduce((c, p) => c + p)/arraySourced.length;
                    sourcedAvg = Math.round((sourcedAvg + Number.EPSILON) * 100) / 100;
                    sourced=sourced+"<td>"+sourcedAvg+"</td>"
                    sourced=sourced+"</tr>";
                    for(data2 in values[keys]["INTERVIEWS"])
                    {
                        interview=interview+"<td>"+values[keys]["INTERVIEWS"][data2]+"</td>";
                        if(data2 <= date.getDate())
                        {
                            arrayInterview.push(values[keys]["INTERVIEWS"][data2]);
                        }
                    }
                    interviewAvg = arrayInterview.reduce((c, p) => c + p)/arrayInterview.length;
                    interviewAvg = Math.round((interviewAvg + Number.EPSILON) * 100) / 100;
                    interview=interview+"<td>"+interviewAvg+"</td>";
                    interview=interview+"</tr>";
                    runrate = Math.round(((interviewAvg / sourcedAvg) + Number.EPSILON) * 100) / 100;
                    if(runrate)
                    {
                        hitratio = hitratio+"<td>"+runrate+"</td></tr>";
                    }
                    else
                    {
                        hitratio = hitratio+"<td>0</td>";
                    }
                }
                sourced=sourced+"</tbody>";
                hitratio=hitratio+"</tbody>";
                interview=interview+"</tbody>";
                $("#interview").empty();
                $("#sourced").empty();
                $("#hitratio").empty();
                $(hitratio).appendTo("#hitratio");
                $(sourced).appendTo("#sourced");
                $(interview).appendTo("#interview");
                $('body').removeClass('busy');
                $('table').show();
                $('#displayDetails').show();
                
                var $currentTable = $("#sourced");
                $currentTable.find('td').removeClass('selected');
                $currentTable.find('td').each(function() {
                    if($(this).closest('table').find('th').eq($(this).index()).text().toLowerCase() == date.getDate())
                    {
                        $(this).addClass('selected');
                    }
                });
                var $currentTable2 = $("#interview");
                $currentTable2.find('td').removeClass('selected');
                $currentTable2.find('td').each(function() {
                    if($(this).closest('table').find('th').eq($(this).index()).text().toLowerCase() == date.getDate())
                    {
                        $(this).addClass('selected');
                    }
                });
            }
        }
    })

    function getFileName(){
        return 'Sourced Profile Details - '+datepick;
    }
    function getFileName2(){
        var startOfWeek = moment().startOf('isoweek').toDate();
        var endOfWeek = moment().endOf('isoweek').toDate();
        var date = startOfWeek.toJSON().slice(0,10).replace(/-/g,'-');
        var curDate = endOfWeek.toJSON().slice(0,10).replace(/-/g,'-');
        return 'Weekly Report - '+date+' - '+curDate;
    }

    $('#prev').click(function(){
        $('body').addClass('busy');
        $('table').hide();
        $('#displayDetails').hide();
        $('#displayDetails').html("MONTH: "+$("#prev").data("date").slice(0,7));
        var date = $(this).data('date');
        var xhr = new XMLHttpRequest();
        var params = "date="+date;
        var res;
        var prev = new Date(date);
        prev.setMonth(prev.getMonth() - 1);
        $('#prev').data('date', prev.toJSON().slice(0,10).replace(/-/g,'-'));
        xhr.open('GET','<?php echo base_url('RunRateReport/GetDataOfMonth')?>'+"?"+params, true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            var values = res;
            var sourced, interview, hitratio;
            if(values)
            {
                headers="<thead><tr><th>RECRUITER</th>";
                for(dates in values[0]["DATES"])
                {
                    headers=headers+"<th>"+values[0]["DATES"][dates]+"</th>";
                }
                headers=headers+"<th>RUN RATE</th></tr></thead>";
                headerValues = Object.keys(values);
                // var recruiterIDs = {};
                sourced=headers;
                interview=headers;
                sourced=sourced+"<tbody>";
                interview=interview+"<tbody>";
                hitratio="<thead><tr><th>RECRUITER</th><th>HIT RATIO</th></tr></thead><tbody>";

                for(keys in values)
                {
                    var date = new Date();
                    var arraySourced = []; 
                    var arrayInterview = [];
                    const average = arr => arr.reduce( ( p, c ) => p + c, 0 ) / arr.length;
                    sourced=sourced+"<tr><td>"+values[keys]["RECRUITER_NAME"]+"</td>";
                    interview=interview+"<tr><td>"+values[keys]["RECRUITER_NAME"]+"</td>";
                    hitratio=hitratio+"<tr><td>"+values[keys]["RECRUITER_NAME"]+"</td>";
                    for(data in values[keys]["SOURCED"])
                    {
                        sourced=sourced+"<td>"+values[keys]["SOURCED"][data]+"</td>";
                        arraySourced.push(values[keys]["SOURCED"][data]);
                    }
                    sourcedAvg = arraySourced.reduce((c, p) => c + p)/arraySourced.length;
                    sourcedAvg = Math.round((sourcedAvg + Number.EPSILON) * 100) / 100;
                    sourced=sourced+"<td>"+sourcedAvg+"</td>"
                    sourced=sourced+"</tr>";
                    for(data2 in values[keys]["INTERVIEWS"])
                    {
                        interview=interview+"<td>"+values[keys]["INTERVIEWS"][data2]+"</td>";
                        arrayInterview.push(values[keys]["INTERVIEWS"][data2]);
                    }
                    interviewAvg = arrayInterview.reduce((c, p) => c + p)/arrayInterview.length;
                    interviewAvg = Math.round((interviewAvg + Number.EPSILON) * 100) / 100;
                    interview=interview+"<td>"+interviewAvg+"</td>";
                    interview=interview+"</tr>";
                    runrate = Math.round(((interviewAvg / sourcedAvg) + Number.EPSILON) * 100) / 100;
                    if(runrate)
                    {
                        hitratio = hitratio+"<td>"+runrate+"</td></tr>";
                    }
                    else
                    {
                        hitratio = hitratio+"<td>0</td>";
                    }
                }
                sourced=sourced+"</tbody>";
                hitratio=hitratio+"</tbody>";
                interview=interview+"</tbody>";
                $("#interview").empty();
                $("#sourced").empty();
                $("#hitratio").empty();
                $(hitratio).appendTo("#hitratio");
                $(sourced).appendTo("#sourced");
                $(interview).appendTo("#interview");
            }
        }
        $('body').removeClass('busy');
        $('table').show();
        $('#displayDetails').show();
    })

</script>