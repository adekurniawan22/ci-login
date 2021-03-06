<div class="container-fluid">

        <h1 class="h3 mb-4 text-gray-800"><?= $title ;?></h1>      
            <?= $this->session->flashdata('message');
                unset($_SESSION['message']); ?>
            <h5>Role : <?= $role['role']?></h5>
        <div class="row">
            <div class="col-lg-6">
                <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Menu</th>
                        <th scope="col">Acces</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; ?>
                    <?php foreach($menu as $m) : ?>
                    <tr>
                        <th scope="row"><?= $i++; ?></th>
                        <td><?= $m['menu'] ?></td>
                        <td>
                            <div class="formcheck">
                                <input type="checkbox" class="form-check-input" <?= check_access($role['id'],$m['id']); ?> data-role="<?= $role['id'] ?>" data-menu="<?= $m['id'] ?>">
                            </div>
                        </td>
                    </tr> 
                    <?php endforeach; ?>
                </tbody>    
                </table>
            </div>
        </div>
    </div>
</div> 


