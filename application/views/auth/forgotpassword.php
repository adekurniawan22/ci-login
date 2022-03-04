<div class="container">

<!-- Outer Row -->
<div class="row justify-content-center">

    <div class="col-7">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Forgot your Password?</h1>
                            </div>
                            <?= $this->session->flashdata('message');
                                unset($_SESSION['message']); ?>
                            <form class="user" action="<?= base_url(); ?>auth/forgotpassword" method="post">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user"
                                        id="email" aria-describedby="emailHelp" name="email"
                                        placeholder="Email Address" value="<?= set_value('email'); ?>">
                                        <?= form_error('email','<small class="text-danger p-3">','</small>'); ?>
                                </div>
                             
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Reset Password
                                </button>                                        
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="<?= base_url(); ?>auth">Back to login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

</div>
