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
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">All <?= esc($menu); ?></div>
                        <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addnew">
                            <i class="fa fa-plus"></i>
                            Add new
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Number</th>
                                    <th>Product</th>
                                    <th>Discount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th class="col-xs-1">Action</th>
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
            <form class="formEdit" action="<?= base_url() ?>/discountSave" method="POST">
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
            ajax: '<?= base_url('discountData') ?>',
            method: 'POST',
            pageLength: 10,
            serverSide: true,
            processing: true,
            "columnDefs": [{
                "width": "7%",
                "targets": 0
            }, {
                "width": "160px",
                "targets": 5
            }, {
                "targets": 0,
                "orderable": false
            }, {
                "targets": 5,
                "orderable": false
            }],
            columns: [{
                    data: 'id',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    data: 'name'
                },
                {
                    data: 'discount',
                    render: function(data, type, row) {
                        return rupiah(data)
                    }
                },
                {
                    data: 'date_start',
                    render: function(data, type, row) {
                        return moment(data).format('D MMMM YYYY, h:mm:ss') + ' - ' + moment(row.date_end).format('D MMMM YYYY, h:mm:ss');
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        var currentDate = new Date(); // Get the current date and time
                        var startDate = new Date(row.date_start); // Convert date_start to a Date object
                        var endDate = new Date(row.date_end); // Convert date_end to a Date object

                        // Check if currentDate is between startDate and endDate
                        if (currentDate >= startDate && currentDate <= endDate) {
                            return '<span class="badge badge-primary">Active</span>';
                        } else {
                            return '<span class="badge badge-danger">Not Active</span>';
                        }
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return `<a href="memberDetail/${data}" class="btn btn-sm btn-round btn-primary"><i class="fas fa-external-link-alt"></i></a> 
                        <a href="#edit" data-toggle="modal" data-id="${data}" class="btn btn-sm btn-round btn-warning"><i class="fas fa-edit"></i></a>
                        <a onclick="confirmDelete(this)" target="<?= base_url() ?>/discountDelete/${data}" class="btn btn-delete btn-sm btn-round btn-danger"><i class="far fa-trash-alt"></i></a>`;
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
                // dataType: 'json',
                url: `<?= base_url() ?>/discountEdit/${rowid}`,
                success: function(data) {
                    // console.log(data)
                    $('.edited-body').html(data);
                    $('.js-example-basic-single').select2({
                        dropdownParent: $('#edit'),
                        placeholder: 'Select an option'
                    });
                    $('.daterange').daterangepicker({
                        timePicker: true,
                        timePicker24Hour: true,
                        locale: {
                            format: 'D MMMM YYYY, HH:mm'
                        },
                        startDate: moment($('#start').val()),
                        endDate: moment($('#end').val())
                    });
                }
            });
        }
    });
</script>
<?= $this->endSection(); ?>