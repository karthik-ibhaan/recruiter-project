<!doctype html>
<html lang="en">
    <?php include('Headers/head.php')?>
    <body>
        <?php include('Headers/header.php')?>
        <div class="page-content p-5" id="content">


                <input type="text" id="filter" placeholder="Enter Filter...">
                <button type="button" name="reset-button" class="btn btn-primary mb-2" id="reset-button" value="<?php echo ""?>" >Reset Filter</button>

            <div class="text-center">
                <table class="table" id="candidates-archive" name="candidates-archive">
                    <thead>
                        <tr>
                            <?php foreach($fieldNames as $keys=>$values):?>
                            <?php $display = str_replace('_',' ', $values);?>
                            <th scope="col" class="text-uppercase text-center"><?php echo $display?></th>
                            <?php endforeach;?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($candidatesArchive as $keys=>$data):?>
                            <tr>
                                <?php foreach($fieldNames as $keys=>$value):?>
                                <td><?php echo ucwords($data[$value])?></td>
                                <?php endforeach;?>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <?php foreach($fieldNames as $keys=>$values):?>
                                    <td></td>
                            <?php endforeach;?>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
        <?php include("Footers/footer.php")?>
    </body>
</html>

<script>
    $.noConflict();
    var table = $('#candidates-archive').DataTable({
    initComplete: function () {
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
        order: [[0, 'desc']],
        scrollX:        true,
        scrollCollapse: true,
        paging: true,
        fixedColumns:   {
            right: 1,
            left: 0
        },
        searchDelay: 2000
    });
    table.columns.adjust().draw();
    var timeout = null;
    document.getElementById('filter').addEventListener('input keyup keydown', debound(filter_table, 500))

    function filter_table(e) {
    const rows = document.querySelectorAll('tbody tr')
    rows.forEach(row => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            e.target.value = e.target.value.toLowerCase();
            table.search(e.target.value).draw();
        })
    })
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

    $("#reset-button").click(function(){
        $("#filter").val('');
    });
</script>
