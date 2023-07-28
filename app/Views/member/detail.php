<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"><?= $menu; ?></h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="<?= base_url() ?>/member"><?= $menu; ?></a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Detail</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">Detail info</div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table mt-3">
                        <tbody>
                            <tr>
                                <td>Name</td>
                                <td><?= $content['name']; ?></td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td><?= $content['phone']; ?></td>
                            </tr>
                            <tr>
                                <td>Point</td>
                                <td><?= $content['point']; ?></td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td><?= $content['address']; ?></td>
                            </tr>
                            <tr>
                                <td>Date created</td>
                                <td><?= date('d, M Y H:i', strtotime($content['created_at'])); ?></td>
                            </tr>
                            <tr>
                                <td>Last updated</td>
                                <td><?= date('d, M Y H:i', strtotime($content['updated_at'])); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Point History</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Point</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($point_history as $row) : ?>
                                    <tr>
                                        <td><?= date('d, M Y', strtotime($row->date)); ?></td>
                                        <td>
                                            <p class="<?= $row->type == 'add' ? "text-success" : "text-warning"; ?>"><?= ($row->type == 'add' ? "+ " : "- ") . $row->point; ?></p>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script src="<?= base_url() ?>/assets/js/plugin/datatables/datatables.min.js"></script>
<script>
    $(document).ready(function() {});
    $('#datatable').DataTable({
        pageLength: 10,
        searching: false,
        columnDefs: [{
            "width": "120px",
            "targets": 0
        }],
    });

    // submited form
    $('.formAdd, .formEdit').submit(function(e) {
        e.preventDefault();
        saveData(this);
    });
</script>
<?= $this->endSection(); ?>