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

            <h2 class="text-uppercase text-center">Scheduled Interviews</h2>
            <br>
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
                    data-date="<?php echo date("Y-m-d",strtotime("tomorrow"))?>"
                    name="next"
                    id="next"><i class="bi bi-caret-right"></i>
                </button>
                

                <br>
                <br>
                <label>Current Week:</label>
                <button
                    type="button" 
                    class="btn btn-dark btn-sm" 
                    data-date="<?php echo date("Y-m-d")?>"
                    name="weekly"
                    id="weekly">Weekly
                </button>

                <br>
                <div class="col-4 justify-content-end">
                    <label>Date: </label>
                    <input type="date" class="form-control" name="datepick" id="datepick">
                </div>
            </div>
            <br>
            <center>
            <div class="col-6">
                <button type="button" name="btn-loading" class="btn btn-loading btn-primary w-100" id="btn-loading">Loading...
                <span class="spinner spinner-border spinner-border-sm mr-3" id="spinner" role="status" aria-hidden="true">
                </span></button>
            </div>
            </center>
            <br>
            <table class="table table-striped table-bordered display nowrap" style="width:100%" id="interview" name="interview">
                <thead>
                    <tr>
                        <th class="col">CLIENT</th>
                        <th class="col">RECRUITER</th>
                        <th class="col">CANDIDATE NAME</th>
                        <th class="col">JOB TITLE</th>
                        <th class="col">LOCATION</th>
                        <th class="col">CUSTOMER SPOC</th>
                        <th class="col">PHONE NO</th>
                        <th class="col">EMAIL ADDRESS</th>
                        <th class="col">TIME</th>
                        <th class="col">INTERVIEW STAGE</th>
                    </tr>
                </thead>
                <tbody class="container"></tbody>
                <tfoot>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tfoot>
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
    function getFileName(){
        var datepick = $("#datepick").val();
        return 'Interview Details - '+datepick;
    }
    var table = $('#interview').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', {extend: 'csv', filename: function() { return getFileName();}}, {extend: 'excel', filename: function() { return getFileName();}}, {extend: 'pdf', filename: function() { return getFileName();}}, 'print'],
        columns: [
            { data: "CLIENT_NAME"},
            { data: "FULL_NAME"},
            { data: "CANDIDATE_NAME"},
            { data: "JOB_TITLE"},
            { data: "WORK_LOCATION"},
            { data: "CUS_SPOC"},
            { data: "PHONE_NO"},
            { data: "EMAIL_ADDRESS"},
            { data: "TIME"},
            { data: "RECRUITMENT_STATUS"},
        ],
        scrollX: true,
        scrollCollapse: true,
        footerCallback: function () {
            this.api().columns()
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
        orderBy: [[0, "asc"]]
    });
    
    var container = $(".container");
    var scrollbody = $(".dataTables_scrollBody");

    bindDragScroll(container, scrollbody);
    
    $('#today').click(function(){
        $('body').addClass('busy');
        $('table').hide();
        var date = $(this).data('date');
        $("#datepick").val(date);
        var prev = new Date(date);
        var next = new Date(date);
        prev.setDate(prev.getDate() - 1);
        next.setDate(next.getDate() + 1);
        $('#prev').data('date', prev.toJSON().slice(0,10).replace(/-/g,'-'));
        $('#next').data('date', next.toJSON().slice(0,10).replace(/-/g,'-'));
        var xhr = new XMLHttpRequest();
        var params = "date="+date;
        var res;
        xhr.open('GET','<?php echo base_url('InterviewList/GetData')?>'+"?"+params, true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            if(res != "")
            {
                
                table.clear();
                table.rows.add( res ).draw();                
                table.columns.adjust().draw();
                window.dispatchEvent(new Event('resize'));
                $('table').show();
            }
            if(res == "")
            {
                table.clear();
                table.rows.add( res ).draw();                
                $('body').removeClass('busy');
                $('table').show();
            }
            $('body').removeClass('busy');
            window.dispatchEvent(new Event('resize'));
        }
    })
    $('#prev').click(function(){
        $('body').addClass('busy');
        $('table').hide();
        var date = $(this).data('date');
        $("#datepick").val(date);
        var prev = new Date(date);
        var next = new Date(date);
        prev.setDate(prev.getDate() - 1);
        next.setDate(next.getDate() + 1);
        $('#prev').data('date', prev.toJSON().slice(0,10).replace(/-/g,'-'));
        $('#next').data('date', next.toJSON().slice(0,10).replace(/-/g,'-'));
        var xhr = new XMLHttpRequest();
        var params = "date="+date;
        var res;
        xhr.open('GET','<?php echo base_url('InterviewList/GetData')?>'+"?"+params, true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            if(res != "")
            {
                
                table.clear();
                table.rows.add( res ).draw();                
                table.columns.adjust().draw();
                window.dispatchEvent(new Event('resize'));
                $('table').show();
            }
            if(res == "")
            {
                table.clear();
                table.rows.add( res ).draw();                
                $('body').removeClass('busy');
                $('table').show();
            }
            $('body').removeClass('busy');
            window.dispatchEvent(new Event('resize'));
        }
    })

    $('#next').click(function(){
        $('body').addClass('busy');
        $('table').hide();
        var date = $(this).data('date');
        $("#datepick").val(date);
        var prev = new Date(date);
        var next = new Date(date);
        prev.setDate(prev.getDate() - 1);
        next.setDate(next.getDate() + 1);
        $('#prev').data('date', prev.toJSON().slice(0,10).replace(/-/g,'-'));
        $('#next').data('date', next.toJSON().slice(0,10).replace(/-/g,'-'));
        var xhr = new XMLHttpRequest();
        var params = "date="+date;
        var res;
        xhr.open('GET','<?php echo base_url('InterviewList/GetData')?>'+"?"+params, true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            if(res != "")
            {
                
                table.clear();
                table.rows.add( res ).draw();                
                table.columns.adjust().draw();
                window.dispatchEvent(new Event('resize'));
                $('table').show();
            }
            if(res == "")
            {
                table.clear();
                table.rows.add( res ).draw();                
                $('body').removeClass('busy');
                $('table').show();
            }
            $('body').removeClass('busy');
            window.dispatchEvent(new Event('resize'));
        }
    })


    $('#weekly').click(function(){
        $('body').addClass('busy');
        $('table').hide();
        var startOfWeek = moment().startOf('isoweek').toDate();
        startOfWeek.setDate(startOfWeek.getDate()+1);
        var endOfWeek = moment().endOf('isoweek').toDate();
        endOfWeek.setDate(endOfWeek.getDate()-1);
        var date = startOfWeek.toJSON().slice(0,10).replace(/-/g,'-');
        var curDate = endOfWeek.toJSON().slice(0,10).replace(/-/g,'-');
        var xhr = new XMLHttpRequest();
        var params = "date="+date+"&date2="+curDate;
        var res;
        xhr.open('GET','<?php echo base_url('InterviewList/GetDataOfWeek')?>'+"?"+params, true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            if(res != "")
            {
                
                table.clear();
                table.rows.add( res ).draw();                
                table.columns.adjust().draw();
                window.dispatchEvent(new Event('resize'));
                $('table').show();
            }
            if(res == "")
            {
                table.clear();
                table.rows.add( res ).draw();                
                $('body').removeClass('busy');
                $('table').show();
            }
            $('body').removeClass('busy');
            window.dispatchEvent(new Event('resize'));
        }
    })

    $(window).resize(function() {
        table.columns.adjust().draw();        
    });

    $('#datepick').change(function() {
        $('body').addClass('busy');
        $('table').hide();
        var date = $(this).val();
        var prev = new Date(date);
        var next = new Date(date);
        prev.setDate(prev.getDate() - 1);
        next.setDate(next.getDate() + 1);
        $('#prev').data('date', prev.toJSON().slice(0,10).replace(/-/g,'-'));
        $('#next').data('date', next.toJSON().slice(0,10).replace(/-/g,'-'));
        var xhr = new XMLHttpRequest();
        var params = "date="+date;
        var res;
        xhr.open('GET','<?php echo base_url('InterviewList/GetData')?>'+"?"+params, true);
        xhr.send();
        xhr.onload = function() {
            res = JSON.parse(xhr.responseText);
            if(res != "")
            {
                
                table.clear();
                table.rows.add( res ).draw();                
                table.columns.adjust().draw();
                window.dispatchEvent(new Event('resize'));
                $('table').show();
            }
            if(res == "")
            {
                table.clear();
                table.rows.add( res ).draw();                
                $('body').removeClass('busy');
                $('table').show();
            }
            $('body').removeClass('busy');
            window.dispatchEvent(new Event('resize'));
        }
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

</script>