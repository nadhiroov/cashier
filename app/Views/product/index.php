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
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Price</th>
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
                <h5 class="modal-title" id="addnewLabel">Add new product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="formAdd" action="<?= base_url() ?>/productSave" method="POST">
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
                <h5 class="modal-title" id="addnewLabel">Edit product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="formEdit" action="<?= base_url() ?>/productSave" method="POST">
                <div class="modal-body edited-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal change price -->
<div class="modal fade" id="changePrice" tabindex="-1" role="dialog" aria-labelledby="addnewLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addnewLabel">Change price</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="formChangePrice" action="<?= base_url() ?>/productSavePrice" method="POST">
                <div class="modal-body price-body"></div>
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
<script src="<?= base_url() ?>/assets/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            ajax: '<?= base_url('productData') ?>',
            method: 'POST',
            pageLength: 10,
            serverSide: true,
            processing: true,
            "columnDefs": [{
                "width": "190px",
                "targets": 4
            }, {
                "targets": 4,
                "orderable": false
            }],
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
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return `<a href="#changePrice" data-toggle="modal" data-id="${data}" class="btn btn-sm btn-round btn-success"><i class="fas fa-chart-line"></i></a>
                        <a href="productDetail/${data}" class="btn btn-sm btn-round btn-primary"><i class="fas fa-external-link-alt"></i></a>
                        <a href="#edit" data-toggle="modal" data-id="${data}" class="btn btn-sm btn-round btn-warning"><i class="fas fa-edit"></i></a>
                        <a onclick="confirmDelete(this)" target="<?= base_url() ?>/productDelete/${data}" class="btn btn-delete btn-sm btn-round btn-danger"><i class="far fa-trash-alt"></i></a>`;
                    }
                }
            ]
        });
    });

    $('.formAdd, .formEdit').submit(function(e) {
        e.preventDefault();
        saveData(this);
    });

    $('.formChangePrice').submit(function(e) {
        e.preventDefault();
        saveData(this);
        $("#changePrice").modal("hide");
    });

    $('.formAdd, .formEdit').on("keyup keypress", function(e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });

    $('#addnew').on('show.bs.modal', function(e) {
        $.ajax({
            type: 'get',
            url: '<?= base_url() ?>/productAdd',
            success: function(data) {
                $('.add-body').html(data)
                $('.brand').select2({
                    placeholder: "Please select a brand",
                    dropdownParent: $('#addnew'),
                })
                $('.purchase').keyup(function() {
                    let inputValue = $(this).val().replace(/\./g, '').replace(/,/g, '')
                    let formattedValue = Number(inputValue).toLocaleString("id-ID")
                    $(this).val(formattedValue)
                })

                $('.purchase, .percent').keyup(function() {
                    let purchase = parseFloat($('.purchase').val().replace(/\./g, '').replace(/,/g, '.'))
                    let percent = parseFloat($('.percent').val())

                    if (!isNaN(purchase) && !isNaN(percent) && percent !== 0) {
                        let selling = purchase + (purchase * percent / 100)
                        let formattedSelling = selling.toLocaleString('id-ID', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0,
                        })
                        $('.sell').val(formattedSelling)
                    }
                })
            }
        });
    });

    $('#edit').on('show.bs.modal', function(e) {
        let rowid = $(e.relatedTarget).data('id')
        if (typeof rowid != 'undefined') {
            $.ajax({
                type: 'get',
                url: `<?= base_url() ?>/productEdit/${rowid}`,
                success: function(data) {
                    $('.edited-body').html(data)
                    $('.brand').select2({
                        placeholder: "Please select a category",
                        dropdownParent: $('#edit'),
                    })
                    $('.purchase').keyup(function() {
                        let inputValue = $(this).val().replace(/\./g, '').replace(/,/g, '')
                        let formattedValue = Number(inputValue).toLocaleString("id-ID")
                        $(this).val(formattedValue)
                    })

                    $('.purchase, .percent').keyup(function() {
                        let purchase = parseFloat($('.purchase').val().replace(/\./g, '').replace(/,/g, '.'))
                        let percent = parseFloat($('.percent').val())

                        if (!isNaN(purchase) && !isNaN(percent) && percent !== 0) {
                            let selling = purchase + (purchase * percent / 100)
                            let formattedSelling = selling.toLocaleString('id-ID', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0,
                            })
                            $('.sell').val(formattedSelling)
                        }
                    })
                }
            });
        }
    });

    $('#changePrice').on('show.bs.modal', function(e) {
        let rowid = $(e.relatedTarget).data('id')
        if (typeof rowid != 'undefined') {
            $.ajax({
                type: 'get',
                url: `<?= base_url() ?>/productChangePrice/${rowid}`,
                success: function(data) {
                    $('.price-body').html(data)
                    // format price
                    $('.purchase').keyup(function() {
                        let inputValue = $(this).val().replace(/\./g, '').replace(/,/g, '')
                        let formattedValue = Number(inputValue).toLocaleString("id-ID")
                        $(this).val(formattedValue)
                    })

                    // trigger purchase sell price
                    $('.purchase, .percent').keyup(function() {
                        let purchase = parseFloat($('.purchase').val().replace(/\./g, '').replace(/,/g, '.'))
                        let percent = parseFloat($('.percent').val())

                        if (!isNaN(purchase) && !isNaN(percent) && percent !== 0) {
                            let selling = purchase + (purchase * percent / 100)
                            let formattedSelling = selling.toLocaleString('id-ID', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0,
                            })
                            $('.sell').val(formattedSelling)
                        }
                    })

                    // show hide form
                    let checkPrice = $('.checkPrice');
                    let checkStock = $('.checkStock');
                    let priceForm = $('.priceForm');
                    let stockForm = $('.stockForm');

                    checkPrice.change(function() {
                        if (checkPrice.is(':checked')) {
                            priceForm.show()
                        } else {
                            priceForm.hide()
                        }
                    })

                    checkStock.change(function() {
                        if (checkStock.is(':checked')) {
                            stockForm.show()
                        } else {
                            stockForm.hide()
                        }
                    })

                }
            });
        }
    });
</script>
<?= $this->endSection(); ?>