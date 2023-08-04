<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('errors')) : ?>
                            <div class="alert alert-danger">
                                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                    <p><?= $error ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (session()->getFlashdata('msg')) : ?>
                            <div class="alert alert-danger">
                                <p><?= session()->getFlashdata('msg') ?></p>
                            </div>
                        <?php endif; ?>
                        <form action="<?= base_url('loggingin'); ?>" method="post" class="needs-validation" novalidate>
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="validationCustom03" class="form-label">Username</label>
                                <input type="text" id="validationCustom03" autofocus name="username" placeholder="input username" class="form-control" value="<?= old('username'); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="validationCustom05" class="form-label">Password</label>
                                <input type="password" id="validationCustom05" name="password" class="form-control" placeholder="input password">
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url() ?>assets/js/core/popper.min.js"></script>
    <script src="<?= base_url() ?>assets/js/core/bootstrap.min.js"></script>
</body>

</html>