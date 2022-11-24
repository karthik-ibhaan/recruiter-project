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
        <div class="text-center">
            <table class="table" id="candidates" name="candidates">
                <thead>
                    <tr>
                    <?php foreach($fieldNames as $keys=>$values):?>
                        <?php $display = str_replace('_',' ', $values);?>
                        <th scope="col" class="col">
                            <?php echo $display?>
                        </th>
                    <?php endforeach;?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($candidates as $keys=>$data):?>
                        <tr>
                            <?php foreach($fieldNames as $keys=>$value):?>
                            <td><?php echo $data[$value]?></td>
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
            </table>
        </div>
    </div>

    <?php include('Footers/footer.php')?>
    </body>
</html>

<script>
    $(document).ready(function() {
        $.noConflict();
        var table = $('table').DataTable({
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
            right: 1,
            left: 0
        },
        order: [[1,"desc"]],
        });
    })
</script>