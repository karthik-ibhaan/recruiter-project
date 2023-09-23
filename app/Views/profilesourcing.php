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

            <h2 class="text-uppercase text-center">Sourced Profile Statistics</h2>
            <div class="col navs">
                <label>Navigate:</label>
                <button
                    type="button" 
                    class="btn btn-dark btn-sm" 
                    data-date="<?php echo date("Y-m-d",strtotime("yesterday"))?>"
                    name="prev"
                    id="prev"><i class="bi bi-caret-left"></i>
                </button>

                <button
                    type="button" 
                    class="btn btn-dark btn-sm" 
                    data-date="<?php echo date("Y-m-d")?>"
                    name="today"
                    id="today">Today
                </button>
                <button
                    type="button" 
                    class="btn btn-dark btn-sm" 
                    data-date="<?php echo date("Y-m-d")?>"
                    name="weekly"
                    id="weekly">Weekly
                </button>
                <div class="col-4 justify-content-end">
                    <label>Date: </label>
                    <input type="date" class="form-control" name="datepick" id="datepick">
                </div>
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
            <center><h3 id="displayDetails"></h3></center>
            <table class="table table-striped table-bordered display nowrap" style="width:100%" id="sourced" name="sourced">
            </table>
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
</style>


<script>
    $('#today').click(function(){
        if( $.fn.DataTable.isDataTable('table')){
            $('table').DataTable().destroy();
        }
        $('body').addClass('busy');
        $('table').hide();
        $('#displayDetails').hide();
        var table = document.getElementById("sourced");
        table.innerHTML = "";
        var date = $(this).data('date');
        $('#displayDetails').html('<b>DAILY REPORT</b> <br> DATE: '+date);
        $("#datepick").val(date);
        var prev = new Date(date);
        prev.setDate(prev.getDate() - 1);
        $('#prev').data('date', prev.toJSON().slice(0,10).replace(/-/g,'-'));
        var xhr = new XMLHttpRequest();
        var params = "date="+date;
        var res;
        xhr.open('GET','<?php echo base_url('ProfileSourcing/GetData')?>'+"?"+params, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            result = {};
            if(res != "")
            {
                let overall = {};
                var obj = res[2];
                let clients = [];
                clients.push("Recruiter");
                clients.push("Total");
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
                            let name = obj[key][i].CLIENT_NAME;
                            if(!(name in lookup))
                            {
                                lookup[name] = 1;
                                clients.push(name);
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
                clients = new Set(clients);
                headers = "<thead><tr>";
                clients.forEach(key =>
                {
                    headers = headers+"<th id="+key+">"+key+"</th>";
                });
                headers = headers+"</tr></thead><tbody></tbody>";
                $(headers).appendTo("#sourced");
                var keys = new Set();
                for(var key in overall)
                {
                    var table = document.getElementById("sourced");
                    var tableRow = table.tBodies[0].insertRow();
                    for(var i=0;i<clients.size;i++)
                    {
                        tableRow.insertCell(i);
                    }
                    var recruiter = tableRow.cells[0];
                    recruiter.innerHTML = key;
                    Object.entries(overall[key]).forEach(([key2, value2]) => {
                        keys.add(key2);
                        var index = $('#sourced th:contains('+key2+')');
                        if(index)
                        {
                            var col = tableRow.cells[index.index()];
                            col.innerHTML = value2;
                        }
                    });
                }
                overall = {};
                var table = $('table').DataTable({
                    dom: 'Bfrtip',
                    buttons: ['copy', {extend: 'csv', filename: function() { return getFileName();}}, {extend: 'excel', filename: function() { return getFileName();}}, {extend: 'pdf', filename: function() { return getFileName();}}, 'print'],
                    scrollX: true,
                    scrollCollapse: true,
                    order: [["1",'desc']]
                });            
                $('table').show();
                $('#displayDetails').show();
                $('body').removeClass('busy');
                window.dispatchEvent(new Event('resize'));
            }
        }
    })

    $('#weekly').click(function(){
        if( $.fn.DataTable.isDataTable('table')){
            $('table').DataTable().destroy();
        }
        $('body').addClass('busy');
        $('table').hide();
        $('#displayDetails').hide();
        var table = document.getElementById("sourced");
        table.innerHTML = "";
        var startOfWeek = moment().startOf('isoweek').toDate();
        startOfWeek.setDate(startOfWeek.getDate()+1);
        var endOfWeek = moment().endOf('isoweek').toDate();
        endOfWeek.setDate(endOfWeek.getDate()-1);
        var date = startOfWeek.toJSON().slice(0,10).replace(/-/g,'-');
        var curDate = endOfWeek.toJSON().slice(0,10).replace(/-/g,'-');
        console.log(date);
        var xhr = new XMLHttpRequest();
        var params = "date="+date;
        var res;
        xhr.open('GET','<?php echo base_url('ProfileSourcing/GetDataOfWeek')?>'+"?"+params, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            result = {};
            if(res != "")
            {
                let overall = {};
                var obj = res[2];
                let clients = [];
                clients.push("Recruiter");
                clients.push("Total");
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
                            let name = obj[key][i].CLIENT_NAME;
                            if(!(name in lookup))
                            {
                                lookup[name] = 1;
                                clients.push(name);
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
                clients = new Set(clients);
                headers = "<thead><tr>";
                clients.forEach(key =>
                {
                    headers = headers+"<th id="+key+">"+key+"</th>";
                });
                headers = headers+"</tr></thead><tbody></tbody>";
                $(headers).appendTo("#sourced");
                var keys = new Set();
                for(var key in overall)
                {
                    var table = document.getElementById("sourced");
                    var tableRow = table.tBodies[0].insertRow();
                    for(var i=0;i<clients.size;i++)
                    {
                        tableRow.insertCell(i);
                    }
                    var recruiter = tableRow.cells[0];
                    recruiter.innerHTML = key;
                    Object.entries(overall[key]).forEach(([key2, value2]) => {
                        keys.add(key2);
                        var index = $('#sourced th:contains('+key2+')');
                        if(index)
                        {
                            var col = tableRow.cells[index.index()];
                            col.innerHTML = value2;
                        }
                    });
                }
                overall = {};
                var table = $('table').DataTable({
                    dom: 'Bfrtip',
                    buttons: ['copy', {extend: 'csv', filename: function() { return getFileName2();}}, {extend: 'excel', filename: function() { return getFileName2();}}, {extend: 'pdf', filename: function() { return getFileName2();}}, 'print'],
                    scrollX: true,
                    scrollCollapse: true,
                    order: [["1",'desc']]
                });            
                $('table').show();
                $('body').removeClass('busy');
                $('#displayDetails').show();
                $('#displayDetails').html('<b>WEEKLY REPORT</b> <br> WEEK: '+date+' - '+curDate);
                window.dispatchEvent(new Event('resize'));
            }
        }
    })

    $('#datepick').change(function(){
        if( $.fn.DataTable.isDataTable('table')){
            $('table').DataTable().destroy();
        }
        $('body').addClass('busy');
        $('table').hide();
        $('#displayDetails').hide();
        var date = $(this).val();
        $('#displayDetails').html('<b>DAILY REPORT</b> <br> DATE: '+date);
        console.log(date);
        if(date != "")        
        {
            var xhr = new XMLHttpRequest();
            var params = "date="+date;
            var res;
            xhr.open('GET','<?php echo base_url('ProfileSourcing/GetData')?>'+"?"+params, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send();
            xhr.onload = function() {
                res = JSON.parse(xhr.responseText);
                result = {};
                if(res != "")
                {
                    let overall = {};
                    var obj = res[2];
                    let clients = [];
                    clients.push("Recruiter");
                    clients.push("Total");
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
                                let name = obj[key][i].CLIENT_NAME;
                                if(!(name in lookup))
                                {
                                    lookup[name] = 1;
                                    clients.push(name);
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
                    clients = new Set(clients);
                    var table = document.getElementById("sourced");
                    table.innerHTML = "";
                    headers = "<thead><tr>";
                    clients.forEach(key =>
                    {
                        headers = headers+"<th id="+key+">"+key+"</th>";
                    });
                    headers = headers+"</tr></thead><tbody></tbody>";
                    $(headers).appendTo("#sourced");
                    var body = "<tbody>";
                    var keys = new Set();
                    for(var key in overall)
                    {
                        var table = document.getElementById("sourced");
                        var tableRow = table.tBodies[0].insertRow();
                        for(var i=0;i<clients.size;i++)
                        {
                            tableRow.insertCell(i);
                        }
                        var recruiter = tableRow.cells[0];
                        recruiter.innerHTML = key;
                        Object.entries(overall[key]).forEach(([key2, value2]) => {
                            keys.add(key2);
                            var index = $('#sourced th:contains('+key2+')');
                            if(index)
                            {
                                var col = tableRow.cells[index.index()];
                                col.innerHTML = value2;
                            }
                        });
                    }
                    overall = {};
                    var table = $('table').DataTable({
                        dom: 'Bfrtip',
                        buttons: ['copy', {extend: 'csv', filename: function() { return getFileName();}}, {extend: 'excel', filename: function() { return getFileName();}}, {extend: 'pdf', filename: function() { return getFileName();}}, 'print'],
                        scrollX: true,
                        scrollCollapse: true,
                        order: [["1",'desc']]
                    });            
                    $('table').show();
                    $('body').removeClass('busy');
                    $('#displayDetails').show();
                    window.dispatchEvent(new Event('resize'));
                }
            }    
        }
    });
    function getFileName(){
        var datepick = $("#datepick").val();
        return 'Sourced Profile Details - '+datepick;
    }
    function getFileName2(){
        var startOfWeek = moment().startOf('isoweek').toDate();
        startOfWeek.setDate(startOfWeek.getDate() +1);
        var endOfWeek = moment().endOf('isoweek').toDate();
        endOfWeek.setDate(endOfWeek.getDate() - 1);
        var date = startOfWeek.toJSON().slice(0,10).replace(/-/g,'-');
        var curDate = endOfWeek.toJSON().slice(0,10).replace(/-/g,'-');
        return 'Weekly Report - '+date+' - '+curDate;
    }
    $('#prev').click(function(){
        if( $.fn.DataTable.isDataTable('table')){
            $('table').DataTable().destroy();
        }
        $('body').addClass('busy');
        $('table').hide();
        $('#displayDetails').hide();
        var date = $(this).data('date');
        $('#displayDetails').html('<b>DAILY REPORT</b> <br> DATE: '+date);
        $("#datepick").val(date);
        var xhr = new XMLHttpRequest();
        var params = "date="+date;
        var res;
        xhr.open('GET','<?php echo base_url('ProfileSourcing/GetData')?>'+"?"+params, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            var prev = new Date(date);
            prev.setDate(prev.getDate() - 1);
            $('#prev').data('date', prev.toJSON().slice(0,10).replace(/-/g,'-'));
            result = {};
            if(res != "")
            {
                let overall = {};
                var obj = res[2];
                let clients = [];
                clients.push("Recruiter");
                clients.push("Total");
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
                            let name = obj[key][i].CLIENT_NAME;
                            if(!(name in lookup))
                            {
                                lookup[name] = 1;
                                clients.push(name);
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
                clients = new Set(clients);
                var table = document.getElementById("sourced");
                table.innerHTML = "";
                headers = "<thead><tr>";
                clients.forEach(key =>
                {
                    headers = headers+"<th id="+key+">"+key+"</th>";
                });
                headers = headers+"</tr></thead><tbody></tbody>";
                $(headers).appendTo("#sourced");
                var body = "<tbody>";
                var keys = new Set();
                for(var key in overall)
                {
                    var table = document.getElementById("sourced");
                    var tableRow = table.tBodies[0].insertRow();
                    for(var i=0;i<clients.size;i++)
                    {
                        tableRow.insertCell(i);
                    }
                    var recruiter = tableRow.cells[0];
                    recruiter.innerHTML = key;
                    Object.entries(overall[key]).forEach(([key2, value2]) => {
                        keys.add(key2);
                        var index = $('#sourced th:contains('+key2+')');
                        if(index)
                        {
                            var col = tableRow.cells[index.index()];
                            col.innerHTML = value2;
                        }
                    });
                }
                var table = $('table').DataTable({
                    dom: 'Bfrtip',
                    buttons: ['copy', {extend: 'csv', filename: function() { return getFileName();}}, {extend: 'excel', filename: function() { return getFileName();}}, {extend: 'pdf', filename: function() { return getFileName();}}, 'print'],
                    scrollX: true,
                    scrollCollapse: true,
                    order: [["1",'desc']]
                });            
                overall = {};
                $('table').show();
                $('#displayDetails').show();
                $('body').removeClass('busy');
                window.dispatchEvent(new Event('resize'));
            }
        }
    })

</script>