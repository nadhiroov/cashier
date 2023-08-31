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
                <a href="<?= base_url() ?>/category"><?= $menu; ?></a>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">Brand : <?= $content['brand']; ?></div>
                        <!-- <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addnew">
                            <i class="fa fa-plus"></i>
                            Add new
                        </button> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
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
<script src="<?= base_url() ?>/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="<?= base_url() ?>/assets/js/plugin/sweetalert/sweetalert.min.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            ajax: '<?= base_url('productData/') . $content['id'] ?>',
            method: 'POST',
            pageLength: 10,
            serverSide: true,
            processing: true,
            columns: [{
                    data: 'name'
                },
                {
                    data: 'category'
                },
                {
                    data: 'stock'
                },
                {
                    data: 'price',
                    render: function(data, type, row) {
                        return rupiah(data)
                    }
                }
            ]
        });
    });

    // submited form
    $('.formAdd, .formEdit').submit(function(e) {
        e.preventDefault();
        saveData(this);
    });

    $('#edit').on('show.bs.modal', function(e) {
        var rowid = $(e.relatedTarget).data('id');
        if (typeof rowid != 'undefined') {
            $.ajax({
                type: 'get',
                url: `<?= base_url() ?>/categoryEditBrand/${rowid}`,
                success: function(data) {
                    $('.hasil-data').html(data);
                }
            });
        }
    });

    $('#addnew').on('shown.bs.modal', function() {
        $('#myInput').trigger('focus')
    })
</script>
<?= $this->endSection(); ?>