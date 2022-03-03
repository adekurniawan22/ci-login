
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $title ;?></h1>
                    <div class="row">
                    
                        <div class="col-lg-6">
                            <?= form_open_multipart('user/edit');?>
                                <div class="row mb-3">
                                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                    <input type="hidden" name="id" value="<?= $user['id']; ?>">
                                    <input type="email" readonly class="form-control" id="email" name="email" value="<?= $user['email'];?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="name" class="col-sm-2 col-form-label">Fullname</label>
                                    <div class="col-sm-10">
                                    <input type="name"  class="form-control" id="name" name="name" value="<?= $user['name'];?>">
                                    <?= form_error('name','<small class="text-danger p-3">','</small>'); ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-2">
                                        Picture
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <img src="<?= base_url('assets/img/profile/'). $user['image'];?>" class="img-thumbnail">
                                            </div>
                                            <div class="col-sm-9">
                                            <div class="mb-3">
                                                <input class="form-control" type="file" id="image" name="image">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-2"></div>
                                    <div class="col-lg-10">
                                        <button type="submit" class="btn btn-primary">Edit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->       