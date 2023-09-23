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
        <br>
        <center>
            <h4>To Check Demands That Have Been Assigned to Recruiters Today, Click Here:
            <button type="button" class="btn btn-primary mb-2" id="assignedToday" name="assignedToday">Demands Assigned</button></h4>
        </center>
        <center><h2>COORDINATOR DEMANDS</h2></center>
        <br>
        <div class="text-left">
            <table style="width:100%;" class="table table-striped table-bordered" id="demands" name="demands">
                <tfoot>
                    <tr>
                        <th></th>
                        <?php foreach($fieldNames as $keys=>$values):?>
                            <?php if(strtolower($values) !== "client_id"):?>                                
                                <th></th>
                            <?php endif;?>
                        <?php endforeach;?>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <br>
        <br>
        <br>
        <div class="text-left demands2" style="display: none;">
            <center><h4>DEMANDS ASSIGNED TO RECRUITERS</h4></center>
            <center><h4>Date: <?php echo date("Y-m-d")?></h4></center>
            <table style="width:100%;" class="table table-striped table-bordered" id="demands2" name="demands2">
                <thead></thead>
                <tbody></tbody>
                <tfoot></tfoot>
            </table>
        </div>

        <form id="assignForm" method="post" enctype="multipart/form-data">
            <div class="modal fade" id="assignMultipleModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Assign Demands Modal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <div class="row form-group">
                            <div class="row">
                                <input type="hidden" name="demandID" id="demandID">
                            </div>
                            <div class="col">
                                <label><b>ASSIGNED DEMAND</b></label>
                                <input type="text" class="form-control" name="assignDemand" id="assignDemand" style="overflow: auto;" disabled>
                            </div>
                            <div class="col">
                                <label><b>ASSIGN DROPDOWN</b></label>
                                <select name="recruiterMultipleSelect[]" id="recruiterMultipleSelect" multiple placeholder="Assign Recruiter..." required>
                                </select>
                            </div>
                        </div>
                        <br>
                        <span>WOULD YOU LIKE TO ASSIGN THESE DEMANDS TO SELECTED RECRUITERS?</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Assign Demands</button>
                    </div>
                </div>
            </div>
            </div>
        </form>
    </div>
    <?php include('Footers/footer.php')?>
    </body>
</html>
<style>
    .btn-group {
        margin-left: 0;
    }
    
    /* dataTables CSS modification & positioning */
    table.dataTable thead .sorting:before,
    table.dataTable thead .sorting_asc:before,
    table.dataTable thead .sorting_desc:before,
    table.dataTable thead .sorting_asc_disabled:before,
    table.dataTable thead .sorting_desc_disabled:before {
    right: 0 !important;
    content: "" !important;
    }
    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_desc:after,
    table.dataTable thead .sorting_asc_disabled:after,
    table.dataTable thead .sorting_desc_disabled:after {
    right: 0 !important;
    content: "" !important;
    }

    table.dataTable thead th {
        position: relative;
        background-image: none !important;
    }
  
    table.dataTable thead th.sorting:after,
    table.dataTable thead th.sorting_asc:after,
    table.dataTable thead th.sorting_desc:after {
        position: absolute !important;
        top: 12px !important;
        right: 8px !important;
        display: block !important;
    }
    table.dataTable thead th.sorting:after {
        content: url('data:image/svg+xml; utf8, <svg xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" width="16" height="16" clip-rule="evenodd" viewBox="0 0 322 511.21"><path fill-rule="nonzero" d="M295.27 211.54H26.71c-6.23-.02-12.48-2.18-17.54-6.58-11.12-9.69-12.29-26.57-2.61-37.69L144.3 9.16c.95-1.07 1.99-2.1 3.13-3.03 11.36-9.4 28.19-7.81 37.58 3.55l129.97 157.07a26.65 26.65 0 0 1 7.02 18.06c0 14.76-11.97 26.73-26.73 26.73zM26.71 299.68l268.56-.01c14.76 0 26.73 11.97 26.73 26.73 0 6.96-2.66 13.3-7.02 18.06L185.01 501.53c-9.39 11.36-26.22 12.95-37.58 3.55-1.14-.93-2.18-1.96-3.13-3.03L6.56 343.94c-9.68-11.12-8.51-28 2.61-37.69 5.06-4.4 11.31-6.56 17.54-6.57z"/></svg>') !important;
        color: #000000 !important;
        font-size: 1em !important;
        padding-top: 0.12em !important;
        opacity: 0.4 !important;
    }
    table.dataTable thead th.sorting_asc:after {
        color: #000000 !important;
        content: url('data:image/svg+xml; utf8, <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up-fill" viewBox="0 0 16 16"><path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"/></svg>') !important;
        vertical-align: text-top;
        opacity: 0.5 !important;
    }
    table.dataTable thead th.sorting_desc:after {
        color: #000000 !important;
        content: url('data:image/svg+xml; utf8, <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>') !important;
        vertical-align: text-bottom;
        opacity: 0.5 !important;
    }
    table.dataTable tfoot {background-color:white !important; opacity: 1.0 !important}
</style>
<script>
    $(document).ready(function() {
        /* Printing DataTable */
        var selectOptions = <?php echo json_encode($users)?>;
        var userOptions = [];
        console.log(selectOptions)
        for(index in selectOptions)
        {
            userOptions.push({ title: selectOptions[index].FULL_NAME, value: selectOptions[index].USER_ID});
        }
        var select = $("#recruiterMultipleSelect").selectize({
            plugins: ["remove_button"],
            maxItems: null,
            valueField: 'value',
            labelField: 'title',
            searchField: 'title',
            options: userOptions,
            create: false,
        });
        var control = select[0].selectize;
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
            else if(fieldNames[field].toLowerCase() == "client_id")
            {
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
        columns.push({ data: null, orderable: false, className: 'text-center justify-content-center', title: "ASSIGN", defaultContent: '' ,
            render: function(data) {
                if(data.PRIORITY != null && data.PRIORITY != "")
                {
                    if(data.DEMAND_STATUS.toLowerCase() == "open" && (data.PRIORITY.toLowerCase() == "high" || data.PRIORITY.toLowerCase() == "new"))
                    {
                        var btn = '<button type="button" class="btn btn-success mb-2 assignButton" data-bs-toggle="modal" data-bs-target="#assignMultipleModal"><svg xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"  width="24" height="24" viewBox="0 0 512 315.77"><path fill-rule="nonzero" d="M205.68 177.81h76.12c-4.7 4.48-9.38 9.05-13.97 13.69l-1.73 1.76h-60.42c-4.7 0-8.98 1.93-12.08 5.03-3.11 3.11-5.03 7.39-5.03 12.09v72.81c0 4.55 1.79 8.69 4.7 11.77l.33.31c3.11 3.11 7.39 5.04 12.08 5.04h13.35v.03h44.12l.02-.03h43.15c4.69 0 8.97-1.93 12.08-5.03 3.1-3.11 5.03-7.39 5.03-12.09V218.7c4.76-5.64 9.56-11.22 14.36-16.72.72 2.68 1.1 5.5 1.1 8.4v72.81c0 8.93-3.67 17.07-9.57 22.97l-.03.03c-5.91 5.9-14.05 9.58-22.97 9.58H205.68c-8.95 0-17.1-3.67-23.01-9.58l-.43-.47c-5.64-5.86-9.13-13.81-9.13-22.53v-72.81c0-8.97 3.66-17.12 9.56-23.01 5.9-5.9 14.04-9.56 23.01-9.56zM45.12 77.77c-2.81-4.47-8.07-10.54-8.07-15.78 0-2.96 2.33-6.82 5.67-7.68-.27-4.43-.44-8.93-.44-13.38 0-2.63.05-5.29.15-7.9.14-1.65.45-3.29.89-4.88 2.05-6.7 6.53-12.39 12.55-15.96 2.14-1.34 4.45-2.39 6.82-3.25C67.01 7.36 64.92.1 69.65.01c11.05-.29 29.23 9.83 36.32 17.5a28.07 28.07 0 0 1 7.24 18.18l-.45 19.36c1.97.48 4.16 2.01 4.65 3.98 1.51 6.11-4.83 13.72-7.78 18.58-1.71 2.82-6.47 9.63-9.75 14.3-2.18 3.1-4.41 4.1-2.43 7.06 16.13 22.17 56.98 6.33 56.98 50.42H0c0-44.12 40.86-28.25 56.98-50.42 2.17-3.19.36-3.58-2.11-7.13-3.62-5.22-9.04-12.95-9.75-14.07zm357.57 0c-2.81-4.47-8.07-10.54-8.07-15.78 0-2.96 2.33-6.82 5.67-7.68-.27-4.43-.44-8.93-.44-13.38 0-2.63.05-5.29.15-7.9.14-1.65.45-3.29.89-4.88 2.05-6.7 6.53-12.39 12.56-15.96 2.13-1.34 4.44-2.39 6.82-3.25 4.31-1.58 2.22-8.84 6.95-8.93 11.06-.29 29.23 9.83 36.32 17.5a28.019 28.019 0 0 1 7.24 18.18l-.45 19.36c1.97.48 4.17 2.01 4.65 3.98 1.51 6.11-4.83 13.72-7.78 18.58-1.71 2.82-6.47 9.63-9.75 14.3-2.18 3.1-4.41 4.1-2.43 7.06 16.13 22.17 56.98 6.33 56.98 50.42H357.57c0-44.12 40.87-28.25 56.98-50.42 2.17-3.19.36-3.58-2.11-7.13-3.62-5.22-9.04-12.95-9.75-14.07zm-178.79 0c-2.8-4.47-8.07-10.54-8.07-15.78 0-2.96 2.34-6.82 5.67-7.68-.26-4.43-.43-8.93-.43-13.38 0-2.63.05-5.29.14-7.9.15-1.65.45-3.29.89-4.88 2.05-6.7 6.54-12.39 12.56-15.96 2.14-1.34 4.45-2.39 6.82-3.25 4.31-1.58 2.22-8.84 6.95-8.93 11.06-.29 29.23 9.83 36.32 17.5A28.03 28.03 0 0 1 292 35.69l-.45 19.36c1.97.48 4.16 2.01 4.65 3.98 1.51 6.11-4.83 13.72-7.78 18.58-1.72 2.82-6.47 9.63-9.76 14.3-2.18 3.1-4.4 4.1-2.43 7.06 16.13 22.17 56.98 6.33 56.98 50.42H178.79c0-44.12 40.86-28.25 56.98-50.42 2.16-3.19.36-3.58-2.11-7.13-3.62-5.22-9.05-12.95-9.76-14.07zM36.14 177.81h82.14c8.94 0 17.08 3.67 22.99 9.58 5.92 5.89 9.59 14.04 9.59 22.99v72.81c0 8.95-3.68 17.09-9.59 23l-.47.43c-5.88 5.65-13.83 9.15-22.52 9.15H36.14c-8.91 0-17.07-3.68-22.98-9.59-5.92-5.88-9.59-14.03-9.59-22.99v-72.81c0-8.97 3.67-17.12 9.56-23.01 5.9-5.9 14.05-9.56 23.01-9.56zm82.14 15.45H36.14c-4.7 0-8.98 1.93-12.08 5.03-3.1 3.11-5.03 7.39-5.03 12.09v72.81c0 4.69 1.94 8.97 5.04 12.07v.03c3.09 3.1 7.37 5.02 12.07 5.02h82.14c4.54 0 8.68-1.8 11.75-4.71l.32-.34c3.11-3.11 5.05-7.39 5.05-12.07v-72.81c0-4.69-1.94-8.97-5.05-12.08v-.03a17.053 17.053 0 0 0-12.07-5.01zm275.44-15.45h82.14c8.94 0 17.07 3.67 22.98 9.58 5.92 5.89 9.59 14.04 9.59 22.99v72.81c0 8.95-3.68 17.09-9.58 23l-.48.43c-5.87 5.65-13.83 9.15-22.51 9.15h-82.14c-8.93 0-17.07-3.67-22.98-9.58h-.03c-5.89-5.89-9.57-14.04-9.57-23v-72.81c0-8.97 3.67-17.12 9.57-23.01l.47-.44c5.86-5.64 13.81-9.12 22.54-9.12zm82.14 15.45h-82.14c-4.55 0-8.7 1.8-11.78 4.71l-.31.32c-3.1 3.11-5.03 7.39-5.03 12.09v72.81c0 4.69 1.94 8.97 5.04 12.07 3.1 3.12 7.38 5.05 12.08 5.05h82.14c4.53 0 8.68-1.8 11.74-4.71l.32-.34c3.11-3.11 5.05-7.39 5.05-12.07v-72.81c0-4.69-1.93-8.97-5.04-12.08v-.03c-3.09-3.08-7.36-5.01-12.07-5.01zm-241.65 34.18c7.49 4.31 12.37 7.9 18.18 14.31 15.07-24.26 32.08-38.35 53.35-57.43l2.08-.8h23.28c-31.21 34.66-54.41 62.34-77.73 104.13-14.01-23.62-23.45-38.83-43.82-54.64l24.66-5.57z"/></svg></button>';
                        return btn;
                    }
                }
            },
        });

        var demands = <?php echo json_encode($coordinatorDemands)?>;
        var table = $('#demands').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', {extend: 'csv', filename: function() { return getFileName();}}, {extend: 'excel', filename: function() { return getFileName();}}, 'print',
                {
                    text: 'Collapse All',
                    className: 'disable-button close-child-rows',
                    action: function ( e, dt, node, config ) {
                    lastPage = $('#demands').DataTable().page();
                    var table = $('#demands').DataTable();

                    table.rows().every(function () {
                        var row = this;
                        if (row.child.isShown()) {
                            row.child.hide();
                            $(this.node()).removeClass('shown');
                            // disable close child rows button
                            $(".close-child-rows").addClass("disable-button");
                            }
                        })
                    }
                }
            ],
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
            fixedColumns: {
                left: 3,
                right: 1
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

        $(document).on('click', '#assignedToday', function() {
            var httpRequest = new XMLHttpRequest();
            var params = "";
            var res;
            httpRequest.open('GET','<?php echo base_url('CoordinatorDemands/AssignedToday')?>'+"?"+params, true);
            httpRequest.send();
            httpRequest.onload = function() {
                $("#assignedToday").prop('disabled', true);
                var res = JSON.parse(httpRequest.responseText);
                var assignedData = res[0];
                var assignedFieldNames = res[1];
                var assignedColumns = [];

                assignedColumns.push({ title: "CLIENT", data: "CLIENT_NAME" });
                assignedColumns.push({ title: "DEMAND ID", data: "ASSIGN_DEMAND_ID" });
                assignedColumns.push({ title: "JOB TITLE", data: "JOB_TITLE" });
                assignedColumns.push({ title: "ASSIGNED TO", data: "FULL_NAME" });

                console.log(assignedFieldNames);
                var demands2 = $("#demands2").DataTable({
                    columns: assignedColumns,
                    data: assignedData
                })
                $(".demands2").show();
                $(".demands2")[0].scrollIntoView({behavior: 'smooth'});
            }
        })
        var $container = $(".container");
        var $scroller = $(".dataTables_scrollBody");;
        
        $('#demands tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = $("#demands").DataTable().row(tr);
            var data = row.data();
            var index = row.index();
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                if($("#child_details"+index).DataTable().destroy())
                {
                    console.log("DESTROYED")
                }
                $(window).resize();
                tr.removeClass('shown');
            } else {
                // Open this row
                var httpRequest = new XMLHttpRequest();
                if(data)
                {
                    var params = "demand_id="+data["DEMAND_ID"];
                    var res;
                    httpRequest.open('GET','<?php echo base_url('CoordinatorDemands/CandidateDetails')?>'+"?"+params, true);
                    httpRequest.send();
                    httpRequest.onload = function() {
                        res = JSON.parse(httpRequest.responseText);
                        if(res[0] && res[1])
                        {
                            row.child(
                            '<div style="display: block">'+ 
                            '<table class="child_table table table-bordered table-striped" id = "child_details' + index + '">'+
                            '<thead></thead><tbody>' +
                            '</tbody></table>' + '<button type="button" class="btn btn-sm btn-dark exportButton" id = "exportBtn-'+ index + '" name = "exportBtn-'+ index + '" data-id = "'+ index + '" data-client="' + data['CLIENT_NAME'] +'" data-demand = "' + data['DEMAND_ID'] + '" id="submit-'+ index + '">Export</button>'+'<button type="button" class="btn btn-sm btn-dark exportButton2" id = "exportAllBtn" name = "exportAllBtn" data-id = "'+ index + '" data-client="' + data['CLIENT_NAME'] +'" data-demand = "' + data['DEMAND_ID'] + '">Export All</button>'+'</div>').show();
                            var childData = res[0];
                            var childFieldNames = res[1];
                            var btn = "exportBtn-" + index;
                            $("#demands tbody #"+btn).attr('disabled', true);
                            var childColumns = [];
                            childColumns.push({ data: null, title: "EXPORT", 
                                render: function(data) {
                                    var btn = '<input type="checkbox" class="mt-0 form-check-input" id="export-'+ index + '" name="export-'+ index + '[]" value="' + data.CANDIDATE_ID + '">';
                                    return btn;
                                },
                                className: "text-center justify-content-center"
                            });
                            childColumns.push({ data: "CANDIDATE_ID" , title: "CANDIDATE ID", className: "text-center justify-content-center"});
                            childColumns.push({ data: "CANDIDATE_NAME", title: "CANDIDATE NAME", className: "text-center justify-content-center"});
                            childColumns.push({ data: "RECRUITMENT_STATUS", title: "RECRUITMENT STATUS", className: "text-center justify-content-center"});
                            childColumns.push({ data: "FULL_NAME", title: "RECRUITER NAME", className: "text-center justify-content-center"});
                            childColumns.push({ data: "SUBMISSION_DATE", title: "SUBMISSION_DATE", className: "text-center justify-content-center"});
                            var childTable = $('#child_details' + index).DataTable({
                                columns: childColumns,
                                data: childData,
                                destroy: true,
                                scrollX: true,
                                fixedColumns:   {
                                    right: 1,
                                    left: 0
                                },
                                order: [[1,"asc"]],
                                ordering: true,
                                responsive: true,
                                scrollCollapse: true,
                            });
                            $(window).resize();
                            tr.addClass('shown');
                        }
                    }
                }
            }
        });
        
        $("#assignMultipleModal").on("hidden.bs.modal", function () {
            $("#showData").empty();
            $("#showData2").empty();
            control.clear();
            $("#assignDemand").val('').trigger('change');
            $("#demandID").val('').trigger('change');
        });

        $(document).on('click','#demands tbody .assignButton', function(e){
            e.preventDefault();
            var tr = $(this).closest('tr');
            var row = $("#demands").DataTable().row(tr);
            var data = row.data();            
            $("#assignDemand").val(data.CLIENT_NAME + "-" + data.DEMAND_ID + "-" + data.JOB_TITLE).trigger('change');
            $("#demandID").val(data.DEMAND_ID).trigger('change');
        })
    })

    $("#assignForm").submit(function(e){
        e.preventDefault();
        $("#assignMultipleModal").modal('hide');
        var params = new FormData($("#assignForm")[0]);
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('CoordinatorDemands/AssignMultiple')?>',
            data: params,
            processData: false,
            contentType: false,
            success: function(data)
            {
                var str = JSON.parse(data);
                if(str[0].valueOf() === "success".valueOf())
                {
                    $("#successDisplay").html(str[1]);
                    $("#successDisplay").show();
                    confirm(str[1]);
                    document.getElementById("successDisplay").scrollIntoView({behavior: 'smooth'});
                }
                else 
                {
                    $("#errorDisplay").html(str[1]);
                    $("#errorDisplay").show();
                    alert(str[1]);
                    document.getElementById("errorDisplay").scrollIntoView({behavior: 'smooth'});
                }
            },
            statusCode: {
                500: function() {
                    alert("Error Occurred. Please Refresh The Page and Try Again.");
                },
                403: function() {
                    alert("Error Occurred. Please Refresh The Page and Try Again.");
                },
            },
        })
    });

    window.onmousedown = function (e) {
        var el = e.target;
        if (el.tagName.toLowerCase() == 'option' && el.parentNode.hasAttribute('multiple') && el.parentNode.id == "assignMultipleSelect") {
            e.preventDefault();

            // toggle selection
            if (el.hasAttribute('selected')) { el.removeAttribute('selected'); $("#"+el.value).remove(); }
            else {
                el.setAttribute('selected', '');
                $("#showData").append("<b id='"+el.value+"'>"+el.innerHTML+"<br></b>");
            }

            // hack to correct buggy behavior
            var select = el.parentNode.cloneNode(true);
            el.parentNode.parentNode.replaceChild(select, el.parentNode);
        }

        else if (el.tagName.toLowerCase() == 'option' && el.parentNode.hasAttribute('multiple') && el.parentNode.id == "recruiterMultipleSelect")
        {
            e.preventDefault();

            // toggle selection
            if (el.hasAttribute('selected')) { el.removeAttribute('selected'); $("#"+el.value).remove(); }
            else {
                el.setAttribute('selected', ''); 
                $("#showData2").append("<b id='"+el.value+"'>"+el.innerHTML+"<br></b>"); 
            }

            // hack to correct buggy behavior
            var select = el.parentNode.cloneNode(true);
            el.parentNode.parentNode.replaceChild(select, el.parentNode);
        }
    }

    function disablebutton(){
        $('#addsubmit').attr("disabled", true);
    }

    function enablebutton(){
        $('#addsubmit').attr("disabled", false);
    }

    
    var value;
    var timeout = null;
    var today = new Date().toISOString().split('T')[0];

    $(document).on('click', '#demands tbody .form-check-input', function() {
        var id = $(this).attr('id');
        var index = id.split('-');
        var btn = "exportBtn-" + index[1];
        if($("div #export-" + index[1] + ":checked").length >= 1)
        {
            $("#demands tbody #"+btn).attr('disabled', false);
        }
        else
        {
            $("#demands tbody #"+btn).attr('disabled', true);
        }
    })
    $(document).on('click', '#demands tbody .exportButton', function() {
        var demand = $(this).data('demand');
        var client = $(this).data('client');
        var index = $(this).data('id');
        var checkboxValues = [];
        var params = "";
        $("div #export-"+ index+":checked").map(function() {
            params += "candidate_ids[]=" + $(this).val() + "&";
        })
        params = params.substring(0, params.length -1);
        var httpRequest = new XMLHttpRequest();
        var res;
        httpRequest.open('GET','<?php echo base_url('AssignedDemands/CVDownloads')?>'+"?"+params, true);
        httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        httpRequest.responseType = 'blob';
        httpRequest.send();
        httpRequest.onload = function() {
            res = httpRequest.response;
            var fileName = client + " - " + demand + ".zip";
            var link = document.createElement('a');
            link.href=window.URL.createObjectURL(res);
            link.download=fileName;
            link.click();
        };
    })

    $(document).on('click', '#demands tbody .exportButton2', function() {
        var demand = $(this).data('demand');
        var client = $(this).data('client');
        var index = $(this).data('id');
        var checkboxValues = [];
        var params = "";
        params = "demand_id="+demand;
        var httpRequest = new XMLHttpRequest();
        var res;
        httpRequest.open('GET','<?php echo base_url('AssignedDemands/CVDownloads2')?>'+"?"+params, true);
        httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        httpRequest.responseType = 'blob';
        httpRequest.send();
        httpRequest.onload = function() {
            res = httpRequest.response;
            var fileName = client + " - " + demand + ".zip";
            var link = document.createElement('a');
            link.href=window.URL.createObjectURL(res);
            link.download=fileName;
            link.click();
        };
    })

</script>