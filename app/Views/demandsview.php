<!doctype html>
<html lang="en">
    <?php include('Headers/head.php')?>
    <style>
        th, td { white-space: nowrap; overflow: hidden; };
    </style>
    <body>
        <?php include('Headers/header.php')?>
        <div class="page-content p-5" id="content">
        <?php if(session()->getFlashdata('updated') !== NULL):?>
        <div class="alert alert-success">
            <?php echo session()->get('updated') ?>
        </div>
        <?php endif;?>

        <h2>ALL DEMANDS</h2>

        <br>
        <div class="text-left">
            <table style="width:100%;" class="table table-striped table-bordered" id="demands" name="demands">
                <tfoot>
                    <tr>
                        <th></th>
                        <?php foreach($fieldNames as $keys=>$values):?>
                            <th></th>
                        <?php endforeach;?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <?php include('Footers/footer.php')?>
    </body>
</html>
<style>
    .btn-group {
        margin-left: 0;
    }
</style>
<script>
    $(document).ready(function() {
        /* Printing DataTable */

        var fieldNames = <?php echo json_encode($fieldNames)?>;
        var fieldNames = fieldNames;
        var columns = [];
        columns.push({ data: null, orderable: false, className: 'dt-control', defaultContent: '' });
        for(var field in fieldNames)
        {
            if(fieldNames[field].toLowerCase() == "primary_skill")
            {
                columns.push({ data: fieldNames[field] , title: "PRIMARY SKILL", className: "text-center justify-content-center"});
            }
            else if(fieldNames[field].toLowerCase() == "secondary_skill")
            {
                columns.push({ data: fieldNames[field] , title: "SECONDARY SKILL", className: "text-center justify-content-center"});
            }
            else if(fieldNames[field].toLowerCase() == "full_name")
            {
                columns.push({ data: fieldNames[field] , title: "RECRUITER", className: "text-center justify-content-center col"});
            }
            else
            {
                columns.push({ data: fieldNames[field] , title: fieldNames[field].replace("_"," "), className: "text-center justify-content-center col"});
            }
        }

        var demands = <?php echo json_encode($demands)?>;
        var table = $('#demands').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', {extend: 'csv', filename: function() { return getFileName();}}, {extend: 'excel', filename: function() { return getFileName();}}, 'print'],
            columns: columns,
            data: demands,
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
            refreshTableData: function(dt, data) {
                if (!data) {
                data = dt.data();}
                
                dt.clear();
                dt.rows.add( data ).draw();
            },
            scrollX: true,
            responsive: true,
            scrollCollapse: true,
            order: [[1,"asc"]],
        });
        
        function getFileName(){
            var date = new Date();
            
            return "Demands As of: "+" - "+date.getDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear();
        }
        
        $("#demands").DataTable().columns.adjust().draw();
        $("#demands")[0].tBodies[0].className = "container";

        window.dispatchEvent(new Event('resize'));

        $('#demands tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = $("#demands").DataTable().row(tr);
            var data = row.data();
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                jd_location = data["JD_LOCATION"];
                job_description = data["JOB_DESCRIPTION"];
                console.log(jd_location);
                row.child(loader).show();
                row.child(format(jd_location, job_description)).show();
                tr.addClass('shown');
            }
        });

        function loader() {
            return (
                '<div class="d-flex justify-content-left spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
            );
        }
        
        function format(d, e) {
            // `d` is the original data object for the row
            var a;
            var b;
            if(d != undefined && d!== "")
            {
                a = '<div class="form-group inline-block" style="text-align: left;"><h4 class="col">Download The JD With The Button Below:</h4><button style="width: auto; display: inline-block;" type="button" class="btn btn-outline-dark btn-sm" data-id="'+d+'" name="jdView2" id="jdView2"><i class="bi bi-download"></i> | Download</button></div>';
            }
            if(e != undefined && e !== "")
            {
                b = '<div style="white-space: pre-wrap;"><b>JOB DESCRIPTION</b><br>'+e+'</div>'
            }
            else
            {
                a = '<h4>No JD Document Available.</h4>'
                b = ''
            }
            if(a && b)
            return ( a + '<br>' + b )
            else if(a)
            return ( a )
            else if(b)
            return ( b )
        }

        /* JD Download */
        $("#demands tbody").on('click', '#jdView2', function() {
            var location = $(this).data('id');
            var httpRequest = new XMLHttpRequest();
            var params = 'jdLocation='+location;
            var res;
            httpRequest.open('GET','<?php echo base_url('Demands/JDDownload')?>'+"?"+params, true);
            httpRequest.responseType = 'blob';
            httpRequest.send();
            httpRequest.onload = function() {
                res = httpRequest.response;
                var fileName = location.split(/(\\|\/)/g).pop();
                var link = document.createElement('a');
                link.href=window.URL.createObjectURL(res);
                link.download=fileName;
                link.click();
            };
        })
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