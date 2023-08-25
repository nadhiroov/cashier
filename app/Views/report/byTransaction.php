<?= $this->extend('layout/template'); ?>

<?= $this->section('css'); ?>
<link href="<?= base_url() ?>assets/css/datepicker.min.css" rel="stylesheet" />
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="page-inner">
    <h4 class="page-title"><?= esc($menu); ?></h4>
    <div class="page-category">
        <div class="col-md-6">
            <div class="form-group form-inline">
                <label for="inlineinput" class="col-md-1 col-form-label">Month</label>
                <div class="col-md-11 input-group">
                    <div class="input-group date" data-provide="datepicker">
                        <input type="text" id="datepickerInput" class="form-control" value="<?= date('m-Y') ?>">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Daily transaction</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="lineChartDaily"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Monthly transaction</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="lineChartMonthly"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Price report</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="lineChartPrice"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Stock report</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="lineChartStock"></canvas>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script src="<?= base_url() ?>assets/js/plugin/chart.js/chart.min.js"></script>
<script src="<?= base_url() ?>assets/js/plugin/datepicker/datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script>
    let myLineChartDaily
    let myLineChartMonthly
    let myLineChartPrice
    
    $(document).ready(function() {
        $('.date').datepicker({
            todayBtn: true,
            autoclose: true,
            format: "m-yyyy",
            viewMode: "months",
            minViewMode: "months",
            defaultViewDate: {
                year: new Date().getFullYear(),
                month: new Date().getMonth()
            }
        }).on('changeDate', function(e) {
            myLineChartDaily.destroy()
            myLineChartMonthly.destroy()
            myLineChartPrice.destroy()
            getSummary()
        });
        getSummary()
    });

    function getSummary() {
        // daily
        $.ajax({
            url: '<?= base_url() ?>/byTransactionDataDaily',
            data: {
                'monthYear': $('#datepickerInput').val()
            },
            method: "POST",
            dataType: "json",
            success: function(data) {
                // daily report
                let lineChartDaily = document.getElementById('lineChartDaily').getContext('2d')
                myLineChartDaily = new Chart(lineChartDaily, {
                    type: 'line',
                    data: {
                        labels: data.dt,
                        datasets: [{
                            label: "Items",
                            backgroundColor: '#fdaf4b',
                            borderColor: '#fdaf4b',
                            data: data.items,
                        }, {
                            label: "Transaction",
                            backgroundColor: '#177dff',
                            borderColor: '#177dff',
                            data: data.count,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Daily Stats'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false
                        },
                        responsive: true,
                        scales: {
                            xAxes: [{
                                stacked: true,
                            }],
                            yAxes: [{
                                stacked: true
                            }]
                        }
                    }
                });

                // daily price and discount
                let lineChartPrice = document.getElementById('lineChartPrice').getContext('2d')
                myLineChartPrice = new Chart(lineChartPrice, {
                    type: 'line',
                    data: {
                        labels: data.dt,
                        datasets: [{
                            label: "Sold total",
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
                            data: data.grand_total
                        }, {
                            label: "Discount total",
                            borderColor: "#fdaf4b",
                            pointBorderColor: "#FFF",
                            pointBackgroundColor: "#fdaf4b",
                            pointBorderWidth: 2,
                            pointHoverRadius: 4,
                            pointHoverBorderWidth: 1,
                            pointRadius: 4,
                            backgroundColor: 'transparent',
                            fill: true,
                            borderWidth: 2,
                            data: data.discount_total
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
            },
            error: function(err) {
                notif(err.status, err.title, err.message);
            },
        });

        // montly
        $.ajax({
            url: '<?= base_url() ?>/byTransactionMonthly',
            data: {
                'monthYear': $('#datepickerInput').val()
            },
            method: "POST",
            dataType: "json",
            success: function(data) {
                let lineChartMonthly = document.getElementById('lineChartMonthly').getContext('2d')
                myLineChartMonthly = new Chart(lineChartMonthly, {
                    type: 'line',
                    data: {
                        labels: data.dt,
                        datasets: [{
                            label: "Items",
                            backgroundColor: '#fdaf4b',
                            borderColor: '#fdaf4b',
                            data: data.items,
                        }, {
                            label: "Transaction",
                            backgroundColor: '#177dff',
                            borderColor: '#177dff',
                            data: data.count,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Monthly Stats'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false
                        },
                        responsive: true,
                        scales: {
                            xAxes: [{
                                stacked: true,
                            }],
                            yAxes: [{
                                stacked: true
                            }]
                        }
                    }
                });
            },
            error: function(err) {
                console.log(err)
                notif(err.status, err.title, err.message);
            },
        });
    }
</script>
<?= $this->endSection(); ?>