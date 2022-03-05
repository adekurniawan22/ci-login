<div class="container-fluid">

        <h1 class="h3 mb-4 text-gray-800"><?= $title ;?></h1>      
            <?php if(validation_errors()) : ?>
                <div class="alert alert-danger col-3" role="alert"><?= validation_errors() ?></div>
            <?php endif; ?>
            <?= $this->session->flashdata('message'); ?>
            
        <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newSubMenuModal">Add new sub Menu</a>
        <div class="row">
            <div class="col-lg">
                <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Sub Menu</th>
                        <th scope="col">Menu</th>
                        <th scope="col">URL</th>
                        <th scope="col">Icon</th>
                        <th scope="col" class="text-center">Status active</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; ?>
                    <?php foreach($subMenu as $m) : ?>
                    <tr>
                        <th scope="row"><?= $i++; ?></th>
                        <td><?= $m['title'] ?></td>
                        <td><?= $m['menu'] ?></td>
                        <td><?= $m['url'] ?></td>
                        <td><?= $m['icon'] ?></td>
                        <td class="text-center"><?= $m['is_active'] ?></td>
                        <td>
                            <a href="" class="badge badge-success" data-toggle="modal" data-target="#editSubMenu<?= $m['id'];?>">Edit</a>
                            <a href="" class="badge badge-danger" data-toggle="modal" data-target="#deleteSubMenu<?= $m['id'];?>">Delete</a>
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
<div class="modal fade" id="newSubMenuModal" tabindex="-1" aria-labelledby="newSubMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newSubMenuModalLabel">Add Sub Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form action="<?= base_url(); ?>menu/submenu" method="post">
            <div class="modal-body">
                    <div class="form-group">
                        <input type="text" id="title" name="title" class="form-control" placeholder="Sub Menu Name">
                    </div>
                    <div class="form-group">
                        <select name="menu_id" id="menu_id" class="form-control">
                            <option value="">Select Menu</option>
                            <?php foreach($menu as $m) : ?>
                                <option value="<?= $m['id'] ?>"><?= $m['menu'] ?></option>

                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" id="title" name="url" class="form-control" placeholder="Sub Menu URL">
                    </div>
                    <div class="form-group">
                        <input type="text" id="title" name="icon" class="form-control" placeholder="Sub Menu Icon">
                    </div>
                    <div class="form-group">
                        <div class="ml-2 form-check">
                            <input type="checkbox" class="form-check-input" value="1" name="is_active"  id="is_active" checked>
                            <label class="form-check-label">Active? </label>
                        </div>
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
<?php foreach($subMenu as $a) : ?>
<div class="modal fade" id="editSubMenu<?= $a['id'] ?>" tabindex="-1" aria-labelledby="updateMenuLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateMenuLabel">Update Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form action="<?= base_url(); ?>menu/updatesubmenu" method="post">
            <div class="modal-body">
                    <div class="form-group">
                      <input type="hidden" name="id" value="<?= $a['id'] ?>">
                        <input type="text" id="title" name="title" value="<?= $a['title'] ?>"class="form-control" placeholder="Sub Menu Name" required>
                    </div>
                    <div class="form-group">
                        <select name="menu_id" id="menu_id" class="form-control" required>
                            <option value="">Select Menu</option>
                            <?php foreach($menu as $m) : ?>
                              <?php if($m['id']==$a['menu_id']) :?>
                                <option value="<?= $m['id'] ?>" selected><?= $m['menu'] ?></option>
                                <?php else :;?>
                                <option value="<?= $m['id'] ?>"><?= $m['menu'] ?></option>
                              <?php endif;?> 
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" id="url" name="url" value="<?= $a['url'] ?>" class="form-control" placeholder="Sub Menu URL" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="icon" name="icon" value="<?= $a['icon'] ?>" class="form-control" placeholder="Sub Menu Icon" required>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="is_active" id="is_active" required value="1">
                          <label class="form-check-label">
                            Active
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="is_active" id="is_active" value="0">
                          <label class="form-check-label">
                            No Active
                          </label>
                        </div>
                    </div>
            </div>     
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>

<!-- Modal delete -->
<?php foreach($subMenu as $a) : ?>
<div class="modal fade" id="deleteSubMenu<?= $a['id'] ?>" tabindex="-1" aria-labelledby="updateMenuLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <p>Are you sure to delete this sub menu?</p>
      </div>
      <form action="<?= base_url(); ?>menu/deletesubmenu" method="post">
      <div class="modal-footer" method="post">
        <input type="hidden" name="id" value="<?= $a['id'] ?>">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Yes, Delete</button>
      </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach; ?>
