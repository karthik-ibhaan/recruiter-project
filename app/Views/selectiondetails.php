<!doctype html>
<html lang="en">
    <?php include('Headers/head.php')?>
    <body>
        <?php include('Headers/header.php')?>
        <div class="page-content p-5" id="content">

            <h2 class="text-uppercase text-center">SELECTION DETAILS</h2>
            <br>
            <center>
            <div class="col-6 justify-content-center">
                <button type="button" name="btn-loading" class="btn btn-loading btn-primary w-100" id="btn-loading">Loading...
                <span class="spinner spinner-border spinner-border-sm mr-3" id="spinner" role="status" aria-hidden="true">
                </span></button>
            </div>
            </center>
            <br>
            <table class="table table-striped table-bordered display nowrap" style="width:100%" id="selection" name="selection">
                <thead>
                    <tr>
                        <th>SELECTION MONTH</th>
                        <th>SELECTIONS</th>
                        <th>JOINED</th>
                        <th>YET-TO-JOIN</th>
                        <th>DROP-OUTS</th>
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
            <br>
            <br>
            <br>
            <div>
                <h4 class="text-uppercase text-center">Selected Candidates</h4>
                <table class="table table-striped table-bordered display nowrap" style="width:100%" id="fulldetails" name="fulldetails">
                    <thead>
                        <tr>
                        <?php foreach($fieldNames as $keys=>$values):?>
                            <?php $display = str_replace('_',' ', $values);?>
                            <th scope="col" class="text-center justify-content-center col">
                                <?php if(strtolower($display) == "full name"):?>
                                    <?php echo "RECRUITER"?>
                                <?php else:?>
                                    <?php echo $display?>
                                <?php endif;?>
                            </th>
                        <?php endforeach;?>
                        </tr>
                    </thead>
                    <tbody class="container">
                        <?php foreach($selectionData as $keys=>$data):?>
                            <tr>
                                <?php foreach($fieldNames as $keys=>$value):?>
                                    <?php if($value == "PHONE_NO"):?>
                                        <?php $a = json_decode($data[$value])?>
                                        <?php if($a[1] != ""):?>
                                            <td class="text-center"><?php echo $a[0].", ".$a[1]?></td>
                                        <?php else:?>
                                            <td class="text-center"><?php echo $a[0]?></td>
                                        <?php endif;?>
                                    <?php else:?>
                                        <td class="text-center"><?php echo $data[$value]?></td>
                                    <?php endif;?>
                                <?php endforeach;?>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
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
        function getFileName2(){
            let today = new Date().toISOString().slice(0, 10)
            return 'Current Total Selection Details - '+today;
        }
        $.fn.dataTable.moment('MMMM YYYY');
        var table2 = $('#selection').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', {extend: 'csv', filename: function() { return getFileName2();}}, {extend: 'excel', filename: function() { return getFileName2();}}, {extend: 'pdf', filename: function() { return getFileName2();}}, 'print'],
            columns: [
                { data: "MONTHYEAR"},
                { data: "SELECTIONS"},
                { data: "JOINED"},
                { data: "DROPOUTS"},
                { data: "YETTOJOIN"},
                { data: "MONTHLYJOINERS"},
            ],
            scrollX: true,
            scrollCollapse: true,
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
        function getFileName(){
            let today = new Date().toISOString().slice(0, 10)
            return 'Full Details - '+today;
        }
        var table = $('#fulldetails').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', {extend: 'csv', filename: function() { return getFileName();}}, {extend: 'excel', filename: function() { return getFileName();}}, {extend: 'pdf', filename: function() { return getFileName();}}, 'print'],
            scrollX: true,
            scrollCollapse: true
        });
        for(var key in selectionData)
        {
            table2.row.add( selectionData[key] ).draw();        
            window.dispatchEvent(new Event('resize'));
        }
        var $container = $("#fulldetails .container");
        var $scroller = $("#fulldetails_wrapper .dataTables_scrollBody");;
        
        bindDragScroll($container, $scroller);
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
    });
</script>