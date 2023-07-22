<nav class="navbar navbar-header navbar-expand-lg" data-background-color="<?= session()->get('theme') == 'light' ? 'blue2' : 'dark' ?>">

    <div class="container-fluid">
        <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
            <li class="nav-item dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                        <img src="<?= base_url() ?>/uploads/profile/<?= session()->get('image') ?>" alt="profile" class="avatar-img rounded-circle">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                <div class="avatar-lg"><img src="<?= base_url() ?>/uploads/profile/<?= session()->get('image') ?>" alt="image profile" class="avatar-img rounded"></div>
                                <div class="u-text">
                                    <h4><?= session()->get('fullname') ?></h4>
                                    <p class="text-muted"><?= session()->get('email') ?></p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                            <!-- <a class="dropdown-item" href="#">My Profile</a>
                            <a class="dropdown-item" href="#">My Balance</a>
                            <a class="dropdown-item" href="#">Inbox</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Account Setting</a>
                            <div class="dropdown-divider"></div> -->
                            <a class="dropdown-item" href="<?= base_url() ?>/logout">Logout</a>
                        </li>
                    </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>