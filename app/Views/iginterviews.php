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
            <div class="alert alert-success" style="display: none;" id="successDisplay">
            </div>
            <div class="alert alert-warning" style="display: none;" id="errorDisplay">
            </div>

            <center><h2>IBHAAN INTERVIEWS LIST</h2></center>

            <table style="width:100%;" class="table table-striped table-bordered" id="interviews" name="interviews">
                <thead></thead>
                <tbody></tbody>
                <tfoot></tfoot>
            </table>
        </div>
        <?php include('Footers/footer.php')?>
    </body>
</html>
<script>
    var interviews = <?php echo json_encode($interviews);?>;
    var fieldNames = <?php echo json_encode($fieldNames)?>;
    console.log(fieldNames);
    var fieldNames = fieldNames;
    var columns = [];
    for(var field in fieldNames)
    {
        if(fieldNames[field] == "INTERVIEW_ID")
        {

        }
        else if(fieldNames[field] == "INTERVIEW_SELECTION")
        {
            columns.push({ data: null, title: "SELECTION STATUS", className: "text-center justify-content-center", defaultContent: "&nbsp;",
            render: function(data) {
                if(data.INTERVIEW_SELECTION == "" || !data.INTERVIEW_SELECTION)
                {
                    return "";
                }
                else if(data.INTERVIEW_SELECTION == "1")
                {
                    return "SELECTED";
                }
                else if(data.INTERVIEW_SELECTION == "0")
                {
                    return "REJECTED";
                }
            }})
        }
        else if(fieldNames[field] == "SKILL_ANALYSIS")
        {
            columns.push({ data: null, title: "SKILL ANALYSIS EXPORT", className: "text-center justify-content-center", defaultContent: "&nbsp;",
            render: function(data){
                if(data.SKILL_ANALYSIS == "" || !data.SKILL_ANALYSIS)
                {
                }
                else
                {
                    var btn="<button type='button' class='btn btn-dark btn-sm editButton skillExport' data-id='"+data.SKILL_ANALYSIS+"'>EXPORT</button>";
                    return btn;
                }
            }})
        }
        else
        {
            columns.push({ data: fieldNames[field], title: fieldNames[field].replace(/_+/g, ' '), className: "text-center justify-content-center", defaultContent: "&nbsp;"});
        }
    }
    var table2 = $('#interviews').DataTable({
        columns: columns,
        data: interviews,
        refreshTableData: function(dt, data) {
            if (!data) {
            data = dt.data();}
            
            dt.clear();
            dt.rows.add( data ).draw();
        },
        // scrollX: true,
        responsive: true,
        scrollCollapse: true,
        order: [[1,"asc"]],
        initComplete: function( settings, json ){
            $("#interviews").wrap("<div class='cover' style='overflow:auto; width:100%;position:relative;'></div>");
        }
    });
    $(document).on('click', '#interviews tbody .skillExport', function() {
        var location = $(this).data('id');
        var httpRequest = new XMLHttpRequest();
        var params = 'location='+location;
        var res;
        httpRequest.open('GET','<?php echo base_url('InterviewApproval/Download')?>'+"?"+params, true);
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
  
    var $container = $(".dataTables_wrapper");
    var $scroller = $(".cover");;
    
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
</script>