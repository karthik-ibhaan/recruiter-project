<!doctype html>
<html lang="en">
    <?php include('Headers/head.php')?>
    <body>
        <?php include('Headers/header.php')?>
        <div class="page-content p-5" id="content">
        <?php if(session()->getFlashdata('updated') !== NULL):?>
        <div class="alert alert-success">
            <?php echo session()->get('updated') ?>
        </div>
        <?php endif;?>

        <div class="row">
            <div class="column">
                <input type="text" id="filter" placeholder="Enter Filter...">
                <button type="button" name="reset-button" id="reset-button" value="<?php echo ""?>">Reset Filter</button>
            </div>
            <div class="column">

            </div>
        </div>
        <br>
        <div class="text-center">
            <table style="width:100%;" class="table" id="demands" name="demands">
                <thead>
                    <tr>
                        <?php foreach($fieldNames as $keys=>$values):?>
                            <?php if(strtolower(strtolower($values) == "demand_id" || strtolower($values) == "client_id")):?>
                                <?php continue;?>
                            <?php endif;?>
                        <?php $display = str_replace('','', $values);?>
                        <th class="text-center col text-uppercase"><?php echo $display?></th>
                        <?php endforeach;?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($demands as $keys=>$data):?>
                    <tr>
                        <?php foreach($fieldNames as $keys=>$value):?>
                            <?php if(strtolower(strtolower($value) == "demand_id" || strtolower($value) == "client_id")):?>
                                <?php continue;?>
                            <?php endif;?>
                        <td class="text-center"><?php echo $data[$value]?></td>
                        <?php endforeach;?>
                    </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                    <tr>
                        <?php foreach($fieldNames as $keys=>$values):?>
                            <?php if(strtolower(strtolower($values) == "demand_id" || strtolower($values) == "client_id")):?>
                                <?php continue;?>
                            <?php endif;?>
                            <td></td>
                        <?php endforeach;?>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?= form_open('Demands/FileExport') ?>
            <?= csrf_field() ?>
            <center>
            <div class = "col-sm">
                <p>Click below to export the data</p>
                <input type="submit" class="btn btn-dark btn-sm" value="Export" />
            </div>
            </center>
        </form>
    </div>
    <?php include('Footers/footer.php')?>
    </body>
</html>

<script>
    $(document).ready(function() {
        $.noConflict();
        var table = $('.table').DataTable({
        // "rowCallback": function( row, data, index ) {
        //     var allData = this.api().column(8).data().toArray();
        //     console.log(allData);
        //     console.log(row);

        //     if (allData.indexOf(data[8]) != allData.lastIndexOf(data[8])) {
        //         math_random = '#'+(0x1000000+Math.random()*0xffffff).toString(16).substr(1,6);
        //         $('td:eq(8)', row).css('background-color', 'red');
        //         $('td:eq(8)', row).css('color', 'white');
        //     }
        // },
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
        scrollX: true,
        scrollCollapse: true,
        fixedColumns:   {
            right: 0,
            left: 0
        },
        order: [[1,"desc"]],
        });
    })
</script>