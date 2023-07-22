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
                <a href="<?= base_url() ?>/tkl"><?= $menu; ?></a>
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
                        <div class="card-title">Category : <?= $content['category']; ?></div>
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

<!-- Modal add new -->
<div class="modal fade" id="addnew" tabindex="-1" role="dialog" aria-labelledby="addnewLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addnewLabel">Add new in this category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="formEdit" action="<?= base_url() ?>/saveTkl" method="POST">
                <div class="modal-body data-add">
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
                <h5 class="modal-title" id="addnewLabel">Edit produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="formEdit" action="<?= base_url() ?>/saveTkl" method="POST">
                <div class="modal-body hasil-data">
                </div>
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
<script src="<?= base_url() ?>/public/assets/js/plugin/datatables/datatables.min.js"></script>
<script src="<?= base_url() ?>/public/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="<?= base_url() ?>/public/assets/js/plugin/sweetalert/sweetalert.min.js"></script>
<script src="<?= base_url() ?>/public/assets/js/plugin/chart.js/chart.min.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            ajax: '<?= base_url() ?>/categoryDetail/<?= $content['id'] ?>',
            method: 'POST',
            pageLength: 10,
            serverSide: true,
            processing: true,
            columnDefs: [{
                "targets": 7,
                "width": "15%"
            }, {
                "targets": 7,
                "orderable": false
            }],
            columns: [{
                    data: 'department',
                },
                {
                    data: 'bulan',
                },
                {
                    data: 'total_produksi',
                },
                {
                    data: 'sur',
                },
                {
                    data: 'jam',
                },
                {
                    data: 'tarif',
                },
                {
                    data: 'biaya',
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return /* html */ `<a href="#edit" data-toggle="modal" data-id="${data}" class="btn btn-sm btn-round btn-warning"><i class="fas fa-edit"></i></a>
                        <a data-id="${data}" onclick="confirmDelete(this)" target="<?= base_url() ?>/deleteTkl" class="btn btn-delete btn-sm btn-round btn-danger"><i class="far fa-trash-alt"></i></a>`;
                    }
                }
            ]
        });

        $.ajax({
            url: '<?= base_url() ?>/tklGraph',
            data: {
                'id': '<?= $content['id']; ?>'
            },
            method: "POST",
            dataType: "json",
            success: function(data) {
                let lineChart = document.getElementById('lineChart').getContext('2d')
                let myLineChart = new Chart(lineChart, {
                    type: 'line',
                    data: {
                        labels: data.bulan,
                        datasets: [{
                            label: "Total biaya TKL",
                            borderColor: "#1d7af3",
                            pointBorderColor: "#FFF",
                            pointBackgroundColor: "#1d7af3",
                            pointBorderWidth: 2,
                            pointHoverRadius: 4,
                            pointHoverBorderWidth: 1,
                            pointRadius: 4,
                            backgroundColor: 'transparent',
                            fill: true,
                            borderWidth: 2,
                            data: data.total
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 10,
                                fontColor: '#1d7af3',
                            }
                        },
                        tooltips: {
                            bodySpacing: 4,
                            mode: "nearest",
                            intersect: 0,
                            position: "nearest",
                            xPadding: 10,
                            yPadding: 10,
                            caretPadding: 10
                        },
                        layout: {
                            padding: {
                                left: 15,
                                right: 15,
                                top: 15,
                                bottom: 15
                            }
                        }
                    }
                });

                barChart = document.getElementById('barChart').getContext('2d')
                var myBarChart = new Chart(barChart, {
                    type: 'bar',
                    data: {
                        labels: data.bulan,
                        datasets: [{
                            label: "Total biaya TKL",
                            backgroundColor: 'rgb(23, 125, 255)',
                            borderColor: 'rgb(23, 125, 255)',
                            data: data.total,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        },
                    }
                });
            },
            error: function(err) {
                notif(err.status, err.title, err.message);
            },
        });
    });

    // submited form
    $('.formAdd, .formEdit').submit(function(e) {
        e.preventDefault();
        saveData(this);
    });

    $('#addnew').on('show.bs.modal', function(e) {
        $.ajax({
            type: 'post',
            url: '<?= base_url() ?>/addTkl',
            data: 'id=' + <?= $content['id'] ?>,
            success: function(data) {
                $('.data-add').html(data);
            }
        });
    });

    $('#edit').on('show.bs.modal', function(e) {
        var rowid = $(e.relatedTarget).data('id');
        if (typeof rowid != 'undefined') {
            //menggunakan fungsi ajax untuk pengambilan data
            $.ajax({
                type: 'post',
                url: '<?= base_url() ?>/editTkl',
                data: 'id=' + rowid,
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