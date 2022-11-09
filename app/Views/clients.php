<!doctype html>
<html lang="en">
    <?php include('Headers/head.php')?>
    <body>
        <?php include('Headers/header.php')?>

        <?php if(isset($validation)):?>
            <div class="alert alert-warning">
            <?= $validation->listErrors() ?>
            </div>
        <?php endif;?>
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

            <h2>CLIENTS</h2>
            <div class="text-center">
                <table class="table" id="clients" name="clients">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center"><?php echo $fieldNames["0"]?>
                            <th scope="col" class="text-center"><?php echo $fieldNames["1"]?>
                            <th scope="col" class="text-center"><?php echo $fieldNames["3"]?>
                            <th scope="col" class="text-center">EDITS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($clients as $keys=>$data):?>
                            <tr>
                                <td class="text-center"><?php echo $data["CLIENT_ID"]?></td>
                                <td class="text-center"><?php echo $data["CUSTOMER_NAME"]?></td>
                                <td class="text-center"><?php echo $data["CLIENT_NAME"]?></td>
                                <td>
                                    <div class="col" class="text-center">
                                        <button type="button" class="btn btn-dark btn-sm editButton" data-bs-toggle="modal" data-id="<?php echo $data['CLIENT_ID']?>" data-name="<?php echo $data['CLIENT_NAME']?>" data-bs-target="#editModal">EDIT</button>
                                        <button type="button" class="btn btn-dark btn-sm deleteButton" data-bs-toggle="modal" data-id="<?php echo $data['CLIENT_ID']?>" data-name="<?php echo $data['CLIENT_NAME']?>" data-bs-target="#deleteModal">DELETE</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>

            <?= form_open('Clients/AddClient')?>
                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Client</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Write the full name of the Client.Ex: Alstom">Full Name</label>
                            <input type="text" class="form-control" name="client_name" placeholder="Client" required>
                        </div>
                        <div>
                            <label>Customer</label>
                            <select name="customer_name" class="form-control" required>
                                <option value="">-Select-</option>
                                <?php foreach($customers as $keys=>$data):?>
                                        <option value="<?php echo (int) $data['CUSTOMER_ID']?>"><?php echo $data['CUSTOMER_NAME']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                    </div>
                </div>
                </div>
            </form>

            <?= form_open('Clients/EditClient')?>
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Current Client</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Client ID in Database">Client ID</label>
                            <input type="text" class="form-control" style="pointer-events:none" name="client_id" id="client_id" required>
                        </div>
                        <div class="form-group">
                            <label data-bs-toggle="tooltip" data-bs-placement="left" title="Write the full name of the Client.Ex: Alstom">Full Name</label>
                            <input type="text" class="form-control" name="client_name" id="client_name" placeholder="Client" required>
                        </div>
                        <div class="form-group">
                            <label>Customer</label>
                            <select name="customer_name" class="form-control" required>
                                <option value="">-Select-</option>
                                <?php foreach($customers as $keys=>$data):?>
                                        <option value="<?php echo (int) $data['CUSTOMER_ID']?>"><?php echo $data['CUSTOMER_NAME']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                    </div>
                </div>
                </div>
            </form>

            <?= form_open('Clients/DeleteClient')?>
                <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete Client</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <?= csrf_field() ?>
                                <div class="form-group">
                                    <label data-bs-toggle="tooltip" data-bs-placement="left" title="Client ID in Database">Client ID</label>
                                    <input type="text" class="form-control" style="pointer-events:none" name="client_id" id="client_id" required>
                                </div>
                                <div class="form-group">
                                    <label data-bs-toggle="tooltip" data-bs-placement="left" title="Write the full name of the Client.Ex: Alstom">Full Name</label>
                                    <input type="text" class="form-control" style="pointer-events:none" name="client_name" id="client_name" placeholder="Client" required>
                                </div>
                                <center>
                                    <h3>Are you sure you want to delete this client details?</h3>
                                </center>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <?= form_open('Clients/FileExport') ?>
                <?= csrf_field() ?>
                <center>
                <div class = "col-sm">
                    <p>Click below to export the data</p>
                    <input type="submit" class="btn btn-dark btn-sm" value="Export" />
                </div>
                </center>
            </form>
        </div>
        <?php include("Footers/footer.php")?>
    </body>
</html>

<script>
    $(document).on("click", ".editButton", function () {
        var clientID = $(this).data('id');
        var clientName = $(this).data('name');
        console.log(clientID);
        $(".form-group #client_id").val( clientID );
        $(".form-group #client_name").val( clientName);
    });

    $(document).on("click", ".deleteButton", function() {
        var clientID = $(this).data('id');
        var clientName = $(this).data('name');
        console.log(clientID);
        $(".form-group #client_id").val( clientID );
        $(".form-group #client_name").val( clientName);
    });

    $(document).ready(function() {
        $.noConflict();
        $(".table").DataTable({
            fixedColumns:   {
            right: 1,
            left: 0
        }
        });
    })
</script>