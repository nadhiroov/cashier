<?= $this->extend('layout/template'); ?>
<?= $this->section('css'); ?>
<link href="<?= base_url() ?>/assets/css/select2.min.css" rel="stylesheet" />
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
                        <div class="card-title">All Brands</div>
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
                                    <th>No</th>
                                    <th>Brands</th>
                                    <th>Category</th>
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
                <h5 class="modal-title" id="addnewLabel">Add new brand</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="formEdit" action="<?= base_url() ?>/brandSave" method="POST">
                <div class="modal-body">
                    <div class="form-group form-inline">
                        <label for="category" class="col-md-3 col-form-label">Category</label>
                        <div class="col-md-9 p-0">
                            <select class="form-control category" id="category" name="form[category_id]" data-width="100%">
                                <option></option>
                                <?php foreach ($category as $row) : ?>
                                    <option value="<?= $row['id'] ?>"><?= $row['category'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-inline">
                        <label for="inputBrand" class="col-md-3 col-form-label">Brand</label>
                        <div class="col-md-9 p-0">
                            <input type="text" id="inputBrand" class="form-control input-full" placeholder="Enter brand" name="form[brand]">
                        </div>
                    </div>
                </div>
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
                <h5 class="modal-title" id="addnewLabel">Edit brand</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="formEdit" action="<?= base_url() ?>/brandSave" method="POST">
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
<script src="<?= base_url() ?>/assets/js/plugin/datatables/datatables.min.js"></script>
<script src="<?= base_url() ?>/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="<?= base_url() ?>/assets/js/plugin/sweetalert/sweetalert.min.js"></script>
<script src="<?= base_url() ?>assets/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            ajax: '<?= base_url('brandData') ?>',
            method: 'POST',
            pageLength: 10,
            serverSide: true,
            processing: true,
            "columnDefs": [{
                "width": "10%",
                "targets": 0
            }, {
                "width": "20%",
                "targets": 3
            }, {
                "targets": 2,
                "orderable": false
            }],
            columns: [{
                    data: 'id',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'brand'
                },
                {
                    data: 'category'
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return `<a href="brandDetail/${data}" class="btn btn-sm btn-round btn-primary"><i class="fas fa-external-link-alt"></i></a> <a href="#edit" data-toggle="modal" data-id="${data}" class="btn btn-sm btn-round btn-warning"><i class="fas fa-edit"></i></a>
                        <a onclick="confirmDelete(this)" target="<?= base_url() ?>/brandDelete/${data}" class="btn btn-delete btn-sm btn-round btn-danger"><i class="far fa-trash-alt"></i></a>`;
                    }
                }
            ]
        });
    })

    $("#addnew").on('show.bs.modal', function(e) {
        $('.category').select2({
            placeholder: "Please select a category",
            dropdownParent: $('#addnew'),
        });
    });


    $('.formAdd, .formEdit').submit(function(e) {
        e.preventDefault()
        saveData(this)
    })

    $('#edit').on('show.bs.modal', function(e) {
        var rowid = $(e.relatedTarget).data('id')
        if (typeof rowid != 'undefined') {
            $.ajax({
                type: 'get',
                url: `<?= base_url() ?>/brandEdit/${rowid}`,
                success: function(data) {
                    $('.edited-body').html(data)
                    $('.category').select2({
                        placeholder: "Please select a category",
                        dropdownParent: $('#edit'),
                    })
                }
            })
        }
    })
</script>
<?= $this->endSection(); ?>