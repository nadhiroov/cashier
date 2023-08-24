<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login AIS</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="<?= base_url() ?>assets/login/images/favicon.ico">
    <!--===============================================================================================-->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login/css/util.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/login/css/main.css">
    <!--===============================================================================================-->
</head>

<body>

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-form-title" style="background-image: url(<?= base_url() ?>assets/login/images/bg-01.jpg);">
                    <span class="login100-form-title-1">
                        Sign In
                    </span>
                </div>

                <form class="login100-form validate-form" action="<?= base_url('loggingin'); ?>" method="post" novalidate>
                    <?= csrf_field() ?>
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
                    <div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
                        <span class="label-input100">Username</span>
                        <input class="input100" type="text" autofocus name="username" placeholder="Enter username" value="<?= old('username'); ?>">
                        <span class="focus-input100"></span>
                    </div>

                    <div class="wrap-input100 validate-input m-b-18" data-validate="Password is required">
                        <span class="label-input100">Password</span>
                        <input class="input100" type="password" name="password" placeholder="Enter password">
                        <span class="focus-input100"></span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--===============================================================================================-->
    <script src="<?= base_url() ?>assets/js/core/jquery.3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="<?= base_url() ?>assets/js/core/popper.min.js"></script>
    <script src="<?= base_url() ?>assets/js/core/bootstrap.min.js"></script>
</body>

</html>