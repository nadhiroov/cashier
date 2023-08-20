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
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-6">
                        <div class="form-group form-inline">
                            <label for="inlineinput" class="col-md-3 col-form-label">Date</label>
                            <div class="col-md-7 p-0 input-group">
                                <input type="text" class="form-control daterange" placeholder="Pick a date" name="form[date]">
                                <input type="hidden" id="start_date">
                                <input type="hidden" id="end_date">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa fa-calendar-check"></i>
                                    </span>
                                </div>
                            </div>
                            <button type="button" id="resetButton" class="btn btn-icon btn-primary">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Number</th>
                                    <th>Product</th>
                                    <th>Sold total</th>
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
<script>
    $(document).ready(function() {
        $('.daterange').daterangepicker({
            autoUpdateInput: false,
            timePicker: true,
            timePicker24Hour: true,
            locale: {
                format: 'D MMMM YYYY'
            },
        });

        let dttb = $('#datatable').DataTable({
            ajax: {
                url: '<?= base_url('rbyProductData') ?>',
                data: function(d) {
                    d.startDate = $('#start_date').val(),
                        d.endDate = $('#end_date').val()
                }
            },
            method: 'POST',
            pageLength: 10,
            serverSide: true,
            processing: true,
            loadingMessage: "Loading...",
            "columnDefs": [{
                "width": "15%",
                "targets": 3
            }, {
                "targets": 0,
                "orderable": false
            }, {
                "targets": 3,
                "orderable": false
            }],
            columns: [{
                    data: 'id',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'name'
                },
                {
                    data: 'total',
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return `<a href="memberDetail/${data}" class="btn btn-sm btn-round btn-primary"><i class="fas fa-external-link-alt"></i></a>`;
                    }
                }
            ]
        });

        $('.daterange').on('apply.daterangepicker', function(ev, picker) {
            $('#start_date').val(picker.startDate.format('DD-MM-YYYY'))
            $('#end_date').val(picker.endDate.format('DD-MM-YYYY'))
            dttb.ajax.reload()
            console.log($('#end_date').val())
            $(this).val(picker.startDate.format('D MMMM YYYY') + ' - ' + picker.endDate.format('D MMMM YYYY'));
        });

        $('#resetButton').click(function() {
            // Reset the DateRangePicker input by setting its value to an empty string
            $('#start_date').val('')
            $('#end_date').val('')
            $('.daterange').val('');
            dttb.ajax.reload()
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