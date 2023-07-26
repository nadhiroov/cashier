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
                <a href="<?= base_url() ?>/procduct"><?= $menu; ?></a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#"><?= $submenu; ?></a>
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
                                <td>Barcode</td>
                                <td><?= $content['barcode']; ?></td>
                            </tr>
                            <tr>
                                <td>Stock</td>
                                <td><?= $content['stock']; ?></td>
                            </tr>
                            <tr>
                                <td>Price</td>
                                <td><?= $content['price']; ?></td>
                            </tr>
                            <tr>
                                <td>Last updated</td>
                                <td><?= date('D, M Y', strtotime($content['updated_at'])); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Line Chart</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Bar Chart</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script>
    $(document).ready(function() {});

    // submited form
    $('.formAdd, .formEdit').submit(function(e) {
        e.preventDefault();
        saveData(this);
    });
</script>
<?= $this->endSection(); ?>