<?= $this->extend('layout/template'); ?>
<?= $this->section('css'); ?>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/daterangepicker.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/select2.min.css">
<?= $this->endSection(); ?>


<?= $this->section('content'); ?>
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"><?= esc($menu); ?></h4>
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
                <a href="#"><?= esc($menu); ?></a>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">All <?= esc($menu); ?></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form">
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group form-inline">
                                    <label class="col-md-3 col-form-label">Nota number :</label>
                                    <div class="col-md-9 p-0">
                                        <label><?= $content['nota_number']; ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group form-inline">
                                    <label class="col-md-3 col-form-label">Total discount :</label>
                                    <div class="col-md-9 p-0">
                                        <label><?= 'Rp. ' . number_format($content['discount'], 0, ',', '.'); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group form-inline">
                                    <label class="col-md-3 col-form-label">Member :</label>
                                    <div class="col-md-9 p-0">
                                        <label><?= $content['member'] != 0 ? $content['memberName'] : 'General'; ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group form-inline">
                                    <label class="col-md-3 col-form-label">Point pay :</label>
                                    <div class="col-md-9 p-0">
                                        <label><?= $content['point_pay'] == null ? '-' : 'Rp. ' . number_format($content['point_pay'], 0, ',', '.'); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group form-inline">
                                    <label class="col-md-3 col-form-label">Cashier :</label>
                                    <div class="col-md-9 p-0">
                                        <label><?= $content['fullname']; ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group form-inline">
                                    <label class="col-md-3 col-form-label">Total pay :</label>
                                    <div class="col-md-9 p-0">
                                        <label><?= 'Rp. ' . number_format($content['total_pay'], 0, ',', '.'); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group form-inline">
                                    <label class="col-md-3 col-form-label"></label>
                                    <div class="col-md-9 p-0">
                                        <label></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group form-inline">
                                    <label class="col-md-3 col-form-label">Grand total :</label>
                                    <div class="col-md-9 p-0">
                                        <label><?= 'Rp. ' . number_format($content['grand_total'], 0, ',', '.'); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price per item</th>
                                    <th>Total</th>
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

<!-- Modal add new -->
<div class="modal fade" id="addnew" tabindex="-1" role="dialog" aria-labelledby="addnewLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addnewLabel">Add new discount</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="formAdd" action="<?= base_url() ?>/discountSave" method="POST">
                <div class="modal-body add-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal edit -->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="addnewLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addnewLabel">Edit discount</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="formEdit" action="<?= base_url() ?>/memberSave" method="POST">
                <div class="modal-body edited-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script src="<?= base_url() ?>assets/js/plugin/datatables/datatables.min.js"></script>
<script src="<?= base_url() ?>assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="<?= base_url() ?>assets/js/plugin/sweetalert/sweetalert.min.js"></script>

<script src="<?= base_url() ?>assets/js/moment.min.js"></script>
<script src="<?= base_url() ?>assets/js/daterangepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            ajax: '<?= base_url('transDetailData/') . $content['id'] ?>',
            method: 'POST',
            searching: false,
            serverSide: true,
            processing: true,
            paging: false,
            /* columnDefs: [{
                "width": "15%",
                "targets": 4
            }, {
                "targets": 0,
                "orderable": false
            }, {
                "targets": 4,
                "orderable": false
            }], */
            columns: [{
                    data: 'produckName'
                },
                {
                    data: 'qty',
                },
                {
                    data: 'price',
                    render: function(data) {
                        return rupiah(data)
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return rupiah(row.qty * row.price); // Calculate and render the multiplication result
                    }
                }
            ]
        });
    });

    $('.formAdd, .formEdit, .formChangePrice').submit(function(e) {
        e.preventDefault();
        saveData(this);
    });

    $('#addnew').on('shown.bs.modal', function(e) {
        $.ajax({
            type: 'get',
            url: `<?= base_url() ?>/discountAdd/`,
            success: function(data) {
                $('.add-body').html(data);
                $('.js-example-basic-single').select2({
                    dropdownParent: $('#addnew'),
                    placeholder: 'Select an option'
                });
                $('.daterange').daterangepicker({
                    timePicker: true,
                    timePicker24Hour: true,
                    locale: {
                        format: 'D MMMM YYYY, HH:mm'
                    },
                });
            }
        });
    });

    $('#edit').on('show.bs.modal', function(e) {
        let rowid = $(e.relatedTarget).data('id');
        if (typeof rowid != 'undefined') {
            $.ajax({
                type: 'get',
                url: `<?= base_url() ?>/discountEdit/${rowid}`,
                success: function(data) {
                    $('.edited-body').html(data);
                    $('.js-example-basic-single').select2({
                        dropdownParent: $('#addnew'),
                        placeholder: 'Select an option'
                    });
                    $('.daterange').daterangepicker({
                        timePicker: true,
                        timePicker24Hour: true,
                        locale: {
                            format: 'D MMMM YYYY, HH:mm'
                        },
                    });
                }
            });
        }
    });
</script>
<?= $this->endSection(); ?>