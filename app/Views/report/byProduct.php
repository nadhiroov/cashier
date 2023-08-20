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
<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script src="<?= base_url() ?>assets/js/plugin/datatables/datatables.min.js"></script>
<script src="<?= base_url() ?>assets/js/moment.min.js"></script>
<script src="<?= base_url() ?>assets/js/daterangepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/plugin/chart.js/chart.min.js"></script>
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
                        return `<a href="detailByProduct/${data}" class="btn btn-sm btn-round btn-primary"><i class="fas fa-external-link-alt"></i></a>`;
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

    $('#detail').on('shown.bs.modal', function(e) {
        $.ajax({
            type: 'get',
            url: `<?= base_url() ?>/detailiByProduct/`,
            success: function(data) {
                $('.detail-body').html(data);
                let lineChart = document.getElementById('lineChart').getContext('2d')
                let myLineChart = new Chart(lineChart, {
                    type: 'line',
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                        datasets: [{
                            label: "Active Users",
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
                            data: [542, 480, 430, 550, 530, 453, 380, 434, 568, 610, 700, 900]
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
            }
        });
    });
</script>
<?= $this->endSection(); ?>