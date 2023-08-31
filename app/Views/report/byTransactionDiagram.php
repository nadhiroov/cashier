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
                    <div class="card-title">Top 10 product sold per month</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Top 10 product sold per year</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="pieChartAnnual"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script src="<?= base_url() ?>assets/js/plugin/chart.js/chart.min.js"></script>
<script src="<?= base_url() ?>assets/js/plugin/datepicker/datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script>
    let myPieChart
    let myPieChartAnnual
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
            myPieChart.destroy()
            myPieChartAnnual.destroy()
            getSummary()
        });
        getSummary()
    });

    function formatCurrency(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(number);
    }

    function getSummary() {
        // monthly
        $.ajax({
            url: '<?= base_url() ?>/byTransactionDiagramDataMonthly',
            data: {
                'monthYear': $('#datepickerInput').val()
            },
            method: "POST",
            dataType: "json",
            success: function(data) {
                const dynamicBackgroundColorsMonthly = data.count.map(() => {
                    const randomColor = "#" + Math.floor(Math.random() * 16777215).toString(16);
                    return randomColor;
                });

                // monthly report
                let pieChart = document.getElementById('pieChart').getContext('2d')
                myPieChart = new Chart(pieChart, {
                    type: 'pie',
                    data: {
                        datasets: [{
                            data: data.count,
                            backgroundColor: dynamicBackgroundColorsMonthly,
                            borderWidth: 0
                        }],
                        labels: data.name
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            position: 'bottom',
                            labels: {
                                fontColor: 'rgb(154, 154, 154)',
                                fontSize: 11,
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        pieceLabel: {
                            render: 'percentage',
                            fontColor: 'white',
                            fontSize: 14,
                        },
                        layout: {
                            padding: {
                                left: 20,
                                right: 20,
                                top: 20,
                                bottom: 20
                            }
                        }
                    }
                })
            },
            error: function(err) {
                notif(err.status, err.title, err.message);
            },
        });

        // annual
        $.ajax({
            url: '<?= base_url() ?>/byTransactionDiagramDataAnnual',
            data: {
                'monthYear': $('#datepickerInput').val()
            },
            method: "POST",
            dataType: "json",
            success: function(data) {
                const dynamicBackgroundColors = data.count.map(() => {
                    const randomColor = "#" + Math.floor(Math.random() * 16777215).toString(16);
                    return randomColor;
                });
                const dynamicBackgroundColorsAnnual = data.count.map(() => {
                    const randomColor = "#" + Math.floor(Math.random() * 16777215).toString(16);
                    return randomColor;
                });
                // daily report
                let pieChartAnnual = document.getElementById('pieChartAnnual').getContext('2d')
                myPieChartAnnual = new Chart(pieChartAnnual, {
                    type: 'pie',
                    data: {
                        datasets: [{
                            data: data.count,
                            backgroundColor: dynamicBackgroundColorsAnnual,
                            borderWidth: 0
                        }],
                        labels: data.name
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            position: 'bottom',
                            labels: {
                                fontColor: 'rgb(154, 154, 154)',
                                fontSize: 11,
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        pieceLabel: {
                            render: 'percentage',
                            fontColor: 'white',
                            fontSize: 14,
                        },
                        layout: {
                            padding: {
                                left: 20,
                                right: 20,
                                top: 20,
                                bottom: 20
                            }
                        }
                    }
                })
            },
            error: function(err) {
                notif(err.status, err.title, err.message);
            },
        });
    }
</script>
<?= $this->endSection(); ?>