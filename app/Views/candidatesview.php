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

        <center><h2>ALL CANDIDATES</h2></center>
        <br>
        <div class="row">
            <div class="column mt-2">
                <?php foreach($status as $keys=>$data):?>
                    <button type="button" name="<?php echo $keys?>-category" id="<?php echo $keys?>-category" class="btn btn-danger mb-2 statuses" data-id="<?php echo $keys?>"><?php echo $keys?></button>
                <?php endforeach;?>
                <button type="button" name="reset-button" class="btn btn-primary mb-2" id="reset-button" value="<?php echo ""?>" >Reset Filter</button>
            </div>
        </div>
        <div class="text-left">
            <table class="table table-striped table-bordered" id="candidates" name="candidates" style="width:100%;">
                <tfoot>
                    <tr>
                        <th></th>
                        <?php foreach($fieldNames as $keys=>$values):?>
                            <th class="text-center justify-content-center"></th>
                        <?php endforeach;?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <?php include('Footers/footer.php')?>
    </body>
</html>
<script>
    $(document).ready(function() {
        /* Printing DataTable */

        var fieldNames = <?php echo json_encode($fieldNames)?>;
        var fieldNames = fieldNames;
        var columns = [];
        columns.push({ data: null, orderable: false, className: 'dt-control', defaultContent: '' });
        for(var field in fieldNames)
        {
            if(fieldNames[field] == "FULL_NAME")
            {
                columns.push({ data: fieldNames[field] , title: "RECRUITER NAME", className: "text-center justify-content-center col"});
            }
            else
            {
                columns.push({ data: fieldNames[field] , title: fieldNames[field].replace("_"," "), className: "text-center justify-content-center col"});
            }
        }
        var candidates = <?php echo json_encode($candidates)?>;
        Object.entries(candidates).forEach(([key, value]) => {
            var phno = JSON.parse(value["PHONE_NO"]);
            if(phno[1])
            {
                candidates[key]["PHONE_NO"] = phno[0]+", "+phno[1];
            }
            else
            {
                candidates[key]["PHONE_NO"] = phno[0];
            }
        })

        var table = $('table').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', {extend: 'csv', filename: function() { return getFileName();}}, {extend: 'excel', filename: function() { return getFileName();}}, {extend: 'pdf', filename: function() { return getFileName();}}, 'print'],
            columns: columns,
            data: candidates,
            columnDefs: [ {
                defaultContent: "",
                targets: "_all"
            }],
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
                $("#candidates").wrap("<div class='cover' style='overflow:auto; width:100%;position:relative;'></div>");
            },
            order: [[6,"desc"]],
            columnDefs: [{ width: 200, targets: 0 }],
            saveState: false,
            // scrollX:        true,
            scrollCollapse: true,
            fixedColumns:   {
                right: 1,
                left: 0
            }
        });
                
        $("#candidates")[0].tBodies[0].className = "container";

        $('#candidates tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = $("#candidates").DataTable().row(tr);
            var data = row.data();
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                cv_location = data["CV_LOCATION"];
                console.log(cv_location);
                row.child(loader).show();
                row.child(format(cv_location)).show();
                tr.addClass('shown');
            }
        });

        function loader() {
            return (
                '<div class="d-flex justify-content-left spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
            );
        }
        function format(d) {
            // `d` is the original data object for the row
            if(d != undefined)
            return (
                '<div class="form-group inline-block" style="text-align: left;"><h4 class="col">Download The CV With The Button Below:</h4><button style="width: auto; display: inline-block;" type="button" class="btn btn-outline-dark btn-sm" data-id="'+d+'" name="cvView2" id="cvView2"><i class="bi bi-download"></i> Download</button></div>'
            )
            else 
            return (
                '<h4>No CV Available</h4>'
            )
        }

        /* CV Download */
        $("#candidates tbody").on('click', '#cvView2', function() {
            var location = $(this).data('id');
            var httpRequest = new XMLHttpRequest();
            var params = 'cvLocation='+location;
            var res;
            httpRequest.open('GET','<?php echo base_url('Candidates/cvDownload')?>'+"?"+params, true);
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
        function getFileName(){
            var date = new Date();
            
            return "Candidates As of: "+" - "+date.getDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear();
        }
        /* DataTable Printing End */
        
        $("#reset-button").attr('disabled',true);

        $("#reset-button").click(function(){
            $("#reset-button").attr('disabled', true);
            $('.statuses').attr('disabled', false);
            $("#candidates").DataTable().columns().search('').draw();
        });

        $(".statuses").on('click', function(){
            $("#reset-button").attr('disabled',false);
            var id = $(this).data('id');
            $('.statuses').attr('disabled', false);
            $(this).attr('disabled', true);
            debound(filter_table2(id), 500);
        });

        function filter_table2(e)
        {
            var id = e;
            var category = id.split(".");
            console.log(category[0]);
            var val = $.fn.dataTable.util.escapeRegex(category[0]);
            $("#candidates").DataTable().columns(7).search('^'+val, true, false).draw();
        }

        function getFileName(){
            var date = new Date();
            
            return "CandidatesData"+" - "+date.getDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear();
        }
        
        function debound(func, timeout) {
            let timer
            return (...args) => {
                if (!timer) {
                func.apply(this, args);
                }
                clearTimeout(timer)
                timer = setTimeout(() => {
                func.apply(this, args)
                timer = undefined
                }, timeout)
            }
        }

        bindDragScroll($container, $scroller);
        window.dispatchEvent(new Event('resize'));

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
    })
</script>