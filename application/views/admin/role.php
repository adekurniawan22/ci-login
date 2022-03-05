<div class="container-fluid">

        <h1 class="h3 mb-4 text-gray-800"><?= $title ;?></h1>      
            <?= form_error('menu', '<div class="alert alert-danger col-3" role="alert">','</div>');?> 
            <?= $this->session->flashdata('message');
                unset($_SESSION['message']); ?>
        <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newRoleModal">Add new Role</a>
        <div class="row">
            <div class="col-lg-6">
                <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Role</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; ?>
                    <?php foreach($role as $r) : ?>
                    <tr>
                        <th scope="row"><?= $i++; ?></th>
                        <td><?= $r['role'] ?></td>
                        <td>
                            <a href="<?= base_url('admin/roleAccess/') . $r['id'] ; ?>" class="badge badge-warning">Role acces</a>
                            <a href="" class="badge badge-success" data-toggle="modal" data-target="#editMenu<?= $r['id'];?>">Edit</a>
                            <a href="" class="badge badge-danger" data-toggle="modal" data-target="#deleteMenu<?= $r['id'];?>">Delete</a>
                        </td>
                    </tr> 
                    <?php endforeach; ?>
                </tbody>    
                </table>
            </div>
        </div>
    </div>
</div> 


<!-- Modal Add-->
<div class="modal fade" id="newRoleModal" tabindex="-1" aria-labelledby="newRoleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newRoleModalLabel">Add Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form action="<?= base_url(); ?>admin/role" method="post">
            <div class="modal-body">
                    <div class="form-group">
                        <input type="text" id="role" name="role" class="form-control" required>
                        <?= form_error('role','<small class="text-danger p-3">','</small>'); ?>
                    </div>
            </div>     
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Update -->
<?php foreach($role as $a) : ?>
<div class="modal fade" id="editMenu<?= $a['id'] ?>" tabindex="-1" aria-labelledby="updateMenuLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateMenuLabel">Hapus Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form action="<?= base_url(); ?>menu/edit" method="post">
            <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
                        <input type="text" id="menu" name="menu" class="form-control" value="<?= $a['role'] ?>">
                    </div>
            </div>     
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" >Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>

<!-- Modal delete -->
<?php foreach($role as $a) : ?>
<div class="modal fade" id="deleteMenu<?= $a['id'] ?>" tabindex="-1" aria-labelledby="updateMenuLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Hapus Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <p>Apakah kamu yakin ingin menghapus menu ini?</p>
      </div>
      <form action="<?= base_url(); ?>menu/delete" method="post">
      <div class="modal-footer" method="post">
        <input type="hidden" name="id" value="<?= $a['id'] ?>">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach; ?>