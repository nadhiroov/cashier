<div class="sidebar sidebar-style-2" data-background-color="<?= session()->get('theme') == 'light' ? '' : 'dark2' ?>">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <img src="<?= base_url() ?>assets/img/<?= session()->get('image') ?>" alt="profile" class="avatar-img rounded-circle">
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            <?= session()->get('fullname') ?>
                            <?php if (session()->get('is_admin') == 1) : ?>
                                <span class="user-level">Administrator</span>
                            <?php else : ?>
                                <span class="user-level">User</span>
                            <?php endif; ?>
                        </span>
                    </a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <ul class="nav nav-primary">
                <li class="nav-item <?= @$menu_dashboard; ?>">
                    <a href="<?= base_url() ?>dashboard">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-section">
                    <h4 class="text-section">Feature</h4>
                </li>

                <li class="nav-item <?= $menu_warehouse ?? ''; ?>">
                    <a data-toggle="collapse" href="#warehouse">
                        <i class="fas fa-warehouse"></i>
                        <p>Warehouse</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?= isset($menu_warehouse) ? 'show' : '' ?>" id="warehouse">
                        <ul class="nav nav-collapse">
                            <li class="<?= $submenu_category ?? ''; ?>">
                                <a href="<?= base_url() ?>category">
                                    <span class="sub-item">Categories</span>
                                </a>
                            </li>
                            <li class="<?= $submenu_brand ?? ''; ?>">
                                <a href="<?= base_url() ?>brand">
                                    <span class="sub-item">Brands</span>
                                </a>
                            </li>
                            <li class="<?= $submenu_product ?? ''; ?>">
                                <a href="<?= base_url() ?>product">
                                    <span class="sub-item">Products</span>
                                </a>
                            </li>
                            <li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item <?= $menu_selling ?? ''; ?>">
                    <a data-toggle="collapse" href="#selling">
                        <i class="fas fa-dollar-sign"></i>
                        <p>Selling</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?= isset($menu_selling) ? 'show' : '' ?>" id="selling">
                        <ul class="nav nav-collapse">
                            <li class="<?= $submenu_transaction ?? ''; ?>">
                                <a href="<?= base_url() ?>transaction">
                                    <span class="sub-item">Transaction</span>
                                </a>
                            </li>
                            <li class="<?= $submenu_save ?? ''; ?>">
                                <a href="<?= base_url() ?>brand">
                                    <span class="sub-item">Saved transaction</span>
                                </a>
                            </li>
                            <li class="<?= $submenu_history ?? ''; ?>">
                                <a href="<?= base_url() ?>brand">
                                    <span class="sub-item">History</span>
                                </a>
                            </li>
                            <li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item <?= $menu_member ?? ''; ?>">
                    <a data-toggle="collapse" href="#member">
                        <i class="fas fa-users"></i>
                        <p>Member</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?= isset($menu_member) ? 'show' : '' ?>" id="member">
                        <ul class="nav nav-collapse">
                            <li class="<?= $submenu_members ?? ''; ?>">
                                <a href="<?= base_url() ?>member">
                                    <span class="sub-item">Members</span>
                                </a>
                            </li>
                            <li class="<?= $submenu_point ?? ''; ?>">
                                <a href="<?= base_url() ?>point">
                                    <span class="sub-item">Point</span>
                                </a>
                            </li>
                            <li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item <?= @$menu_report; ?>">
                    <a href="<?= base_url() ?>discount">
                        <i class="fas fa-percentage"></i>
                        <p>Discount</p>
                    </a>
                </li>

                <li class="nav-item <?= @$menu_report; ?>">
                    <a href="<?= base_url() ?>/report">
                        <i class="fas fa-clipboard-list"></i>
                        <p>Report</p>
                    </a>
                </li>

                <li class="nav-section">
                    <h4 class="text-section">Setting</h4>
                </li>
                <?php if (session()->get('is_admin') == 1) : ?>
                    <li class="nav-item <?= @$menu_user; ?>">
                        <a href="<?= base_url() ?>/user">
                            <i class="fas fa-user"></i>
                            <p>User</p>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item <?= $menu_setting ?? ''; ?>">
                    <a href="<?= base_url() ?>setting">
                        <i class="fas fa-cogs"></i>
                        <p>Setting</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>