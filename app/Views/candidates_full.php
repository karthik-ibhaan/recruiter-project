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

        <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>

        <div class="row">
            <div class="column">
                <input type="text" id="filter" value="<?php if($statusFilter != ""):?><?php echo $statusFilter?><?php endif;?>" placeholder="Enter Filter...">
                <button type="button" name="reset-button" class="btn btn-primary mb-2" id="reset-button" value="<?php echo ""?>" >Reset Filter</button>
            </div>
            <div class="column">
                <label>Filter By</label>

            </div>
        </div>
        <div class="text-center">
            <table class="table" id="candidates" name="candidates">
                <thead>
                    <tr>
                    <?php foreach($fieldNames as $keys=>$values):?>
                        <?php $display = str_replace('_',' ', $values);?>
                        <th scope="col">
                            <?php echo $display?>
                        </th>
                    <?php endforeach;?>
                    <th class="col align-middle">EDITS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($candidates as $keys=>$data):?>
                        <tr>
                            <?php foreach($fieldNames as $keys=>$value):?>
                            <td><?php echo $data[$value]?></td>
                            <?php endforeach;?>
                            <td>
                                <div class="col">
                                    <button
                                        type="button" 
                                        class="btn btn-dark btn-sm editButton" 
                                        data-bs-toggle="modal" 
                                        data-id="<?php echo $data['CANDIDATE_ID']?>" 
                                        data-name="<?php echo $data['CANDIDATE_NAME']?>" 
                                        data-bs-target="#editModal">EDIT</button>
                                    <button 
                                        type="button" 
                                        class="btn btn-dark btn-sm deleteButton" data-bs-toggle="modal" 
                                        data-id="<?php echo $data['CANDIDATE_ID']?>" 
                                        data-name="<?php echo $data['CANDIDATE_NAME']?>" 
                                        data-bs-target="#deleteModal">DELETE</button>
                                </div>
                            </td>
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

    <button type="button" class="btn mb-2" href="/candidates_archive">Candidates Archive</button>
    <?php include('Footers/footer.php')?>
    </body>
</html>

<script>

</script>