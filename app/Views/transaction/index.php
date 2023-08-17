<?= $this->extend('layout/template'); ?>
<?= $this->section('css'); ?>
<link href="<?= base_url() ?>/assets/css/select2.min.css" rel="stylesheet" />
<style>
    .white-text {
        color: white;
    }

    #daftar-autocomplete {
        list-style: none;
        margin: 0;
        padding: 0;
        width: 100%;
    }

    #daftar-autocomplete li {
        padding: 5px 10px 5px 10px;
        background: #FAFAFA;
        border-bottom: #ddd 1px solid;
    }

    #daftar-autocomplete li:hover,
    #daftar-autocomplete li.autocomplete_active {
        background: #2a84ae;
        color: #fff;
        cursor: pointer;
    }

    #hasil_pencarian {
        padding: 0px;
        display: none;
        position: absolute;
        max-height: 350px;
        overflow: auto;
        border: 1px solid #ddd;
        z-index: 1;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="page-inner">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Nota</h4>
                </div>
                <div class="card-body">
                    <div class="form-group form-inline">
                        <label class="col-md-3 col-form-label">Number</label>
                        <div class="col-md-9 p-0">
                            <label class="col-form-label " id="notaNumber"><?= $notaNumber ?></label>
                        </div>
                    </div>
                    <div class="form-group form-inline">
                        <label class="col-md-3 col-form-label">Date</label>
                        <div class="col-md-9 p-0">
                            <label class="col-form-label"><?= date('Y-m-d H:i'); ?></label>
                        </div>
                    </div>
                    <div class="form-group form-inline">
                        <label class="col-md-3 col-form-label">Staff</label>
                        <div class="col-md-9 p-0">
                            <label class="col-form-label" id="cashierStaff"><?= session()->get('fullname'); ?></label>
                            <input type="hidden" value="<?= session()->get('id'); ?>" id="cashierId">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Member Info</h4>
                </div>
                <div class="card-body">
                    <div class="form-group form-inline">
                        <label class="col-md-3 col-form-label">Name</label>
                        <div class="col-md-9 p-0">
                            <select class="form-control member" id="dropdownMember" name="form[member]" data-width="100%">
                                <option value="0">General</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-inline">
                        <label class="col-md-3 col-form-label">Phone</label>
                        <div class="col-md-9 p-0">
                            <label class="col-form-label" id="member-phone">-</label>
                        </div>
                    </div>
                    <div class="form-group form-inline">
                        <label class="col-md-3 col-form-label">Point</label>
                        <div class="col-md-9 p-0">
                            <label class="col-form-label" id="member-point">-</label>
                            <input type="hidden" name="memberPoint" id="memberPoint">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Transaction</h4>

                </div>
                <div class="card-body">
                    <table class="table mt-3" id="transactionTable">
                        <thead>
                            <tr>
                                <th style='width:35px;'>#</th>
                                <th>Code</th>
                                <th>Item</th>
                                <th>Price</th>
                                <th style='width:110px;'>Qty</th>
                                <th>Sub total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="card-sub bg-info">
                        <button class="btn btn-primary" id="btn-newLine">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span>
                            New Line (F7)
                        </button>

                        <div class="float-right">
                            <h2 class="white-text">Total : <span id="TotalBayar" class="white-text">Rp. 0</span></h2>
                            <input type="hidden" id="grandTotal">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-3">
                            <p><i class="fas fa-keyboard"></i> <b>Shortcut Keyboard : </b></p>
                            <div class="row">
                                <div class="col-sm-12">F7 = New line</div>
                                <div class="col-sm-12">F8 = Pay</div>
                                <div class="col-sm-12">F10 = Save transaction</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-check form-inline">
                                    <label class="col-sm-5 control-label">Pay with point</label>
                                    <div class="col-sm-7">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" id="withPoint" name="withPoint">
                                            <span class="form-check-sign"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group form-inline">
                                    <label class="col-sm-5 control-label"></label>
                                    <div class="col-sm-7">
                                        <input style="display: none;" type="text" name="payPoint" id="payPoint" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-inline">
                                <label class="col-sm-5 control-label">Total discount</label>
                                <div class="col-sm-7">
                                    <input type="text" id="discountTotal" class="form-control" disabled>
                                    <input type="hidden" id="discountTotalUnformated" class="form-control" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-horizontal">
                                <div class="form-group form-inline">
                                    <label class="col-sm-5 control-label">Pay (F8)</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="cash" id="money" class="form-control" onkeypress="return check_int(event)">
                                    </div>
                                </div>
                                <div class="form-group form-inline">
                                    <label class="col-sm-5 control-label">Change</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="moneyChange" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="form-group form-inline">
                                    <label class="col-sm-5 control-label"></label>
                                    <div class="col-sm-7">
                                        <button type="button" class="btn btn-primary btn-block" id="saveTransaction">
                                            <i class="fas fa-save"></i> Save (F10)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script src="<?= base_url() ?>/assets/js/plugin/sweetalert/sweetalert.min.js"></script>
<script src="<?= base_url() ?>/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="<?= base_url() ?>/assets/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        newLine()
        $('.member').select2()
        getMember()
    });

    function getMember() {
        $.ajax({
            url: '<?= base_url('memberGet'); ?>', // Replace this with your server endpoint
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('.member option:not(:first)').remove();
                data.forEach(function(option) {
                    $('.member').append(`<option value="${option.id}|${option.phone}|${option.point}">${option.name}</option>`);
                });
            }
        });
    }

    $("#withPoint").change(function() {
        let member = $('#dropdownMember').find(":selected").val()
        if (member == 0) {
            swalert('error', 'Error', 'Please select member first')
            $("#withPoint").prop("checked", false)
        } else {
            if (this.checked) {
                $('#payPoint').css({
                    'display': 'block'
                })
                HitungTotalKembalian()
            } else {
                $('#payPoint').css({
                    'display': 'none'
                })
                $('#payPoint').val('-')
            }
        }
    });

    $('.member').change(function() {
        let selectedValue = $(this).val()
        if (selectedValue == 'General') {
            $('#member-phone').html('-')
            $('#member-point').html('-')
        } else {
            let values = selectedValue.split('|')
            $('#member-phone').html(parseFloat(values[1]))
            $('#member-point').html(parseFloat(values[2]))
            $('#memberPoint').val(parseFloat(values[2]))
        }
    })

    $('#btn-newLine').click(function() {
        newLine();
    });

    function newLine() {
        let Nomor = $('#transactionTable tbody tr').length + 1
        let Baris = "<tr>"
        Baris += "<td>" + Nomor + "</td>";
        Baris += "<td>";
        Baris += "<input type='text' class='form-control' name='barcode[]' id='pencarian_kode' placeholder='Barcode / item name'>";
        Baris += "<div id='hasil_pencarian'></div>";
        Baris += "</td>";
        Baris += "<td></td>";
        Baris += "<td>";
        Baris += "<input type='hidden' name='harga_satuan[]'>";
        Baris += "<span></span>";
        Baris += "</td>";
        Baris += "<td><input type='text' class='form-control' id='jumlah_beli' name='jumlah_beli[]' onkeypress='return check_int(event)' disabled></td>";
        Baris += "<td>";
        Baris += "<input type='hidden' name='sub_total[]'>";
        Baris += "<span></span>";
        Baris += "</td>";
        Baris += "<td><button class='btn btn-icon btn-round btn-danger' id='deleteLine'><i class='fas fa-trash-alt'></i></button>";
        Baris += "<input type='hidden' name='discount[]' id='discount_item'>";
        Baris += "<input type='hidden' name='discount_[]' id='discount_total'></td>";
        Baris += "</tr>";

        $('#transactionTable tbody').append(Baris);

        $('#transactionTable tbody tr').each(function() {
            $(this).find('td:nth-child(2) input').focus();
        });
    }

    $(document).on('click', '#deleteLine', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();

        var Nomor = 1;
        $('#transactionTable tbody tr').each(function() {
            $(this).find('td:nth-child(1)').html(Nomor);
            Nomor++;
        });

        HitungTotalBayar();
    });

    $(document).on('keyup', '#pencarian_kode', function(e) {
        if ($(this).val() !== '') {
            let charCode = e.which || e.keyCode;
            if (charCode == 40) { // down arrow
                if ($('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').length > 0) {
                    var Selanjutnya = $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').next();
                    $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').removeClass('autocomplete_active');

                    Selanjutnya.addClass('autocomplete_active');
                } else {
                    $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian li:first').addClass('autocomplete_active');
                }
            } else if (charCode == 38) { // up arrow
                if ($('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').length > 0) {
                    var Sebelumnya = $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').prev();
                    $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').removeClass('autocomplete_active');

                    Sebelumnya.addClass('autocomplete_active');
                } else {
                    $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian li:first').addClass('autocomplete_active');
                }
            } else if (charCode == 13) { // enter
                return false;
                var Field = $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)');
                var Kodenya = Field.find('div#hasil_pencarian li.autocomplete_active span#kodenya').html();
                var Barangnya = Field.find('div#hasil_pencarian li.autocomplete_active span#barangnya').html();
                var Harganya = Field.find('div#hasil_pencarian li.autocomplete_active span#harganya').html();

                Field.find('div#hasil_pencarian').hide();
                Field.find('input').val(Kodenya);

                $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(3)').html(Barangnya);
                $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(4) input').val(Harganya);
                $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(4) span').html(to_rupiah(Harganya));
                $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(5) input').removeAttr('disabled').val(1);
                $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(6) input').val(Harganya);
                $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(6) span').html(to_rupiah(Harganya));

                var IndexIni = $(this).parent().parent().index() + 1;
                var TotalIndex = $('#transactionTable tbody tr').length;
                if (IndexIni == TotalIndex) {
                    newLine();

                    /* $('html, body').animate({
                        scrollTop: $(document).height()
                    }, 0); */
                } else {
                    $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(5) input').focus();
                }
            } else if (charCode == 27) { // escape
                $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian').hide();
            } else {
                AutoCompleteGue($(this).width(), $(this).val(), $(this).parent().parent().index());
            }
        } else {
            $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian').hide();
        }
        HitungTotalBayar();
    });

    function debounce(func, delay) {
        let timer;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function() {
                func.apply(context, args);
            }, delay);
        };
    }

    const AutoCompleteGue = debounce(function(Lebar, KataKunci, Indexnya) {
        $('div#hasil_pencarian').hide();
        var Lebar = Lebar + 25;

        var Registered = '';
        $('#transactionTable tbody tr').each(function() {
            if (Indexnya !== $(this).index()) {
                if ($(this).find('td:nth-child(2) input').val() !== '') {
                    Registered += $(this).find('td:nth-child(2) input').val() + ',';
                }
            }
        });

        if (Registered !== '') {
            Registered = Registered.replace(/,\s*$/, "");
        }

        $.ajax({
            url: "<?= base_url('productFind'); ?>",
            type: "POST",
            cache: false,
            data: 'keyword=' + KataKunci + '&registered=' + Registered,
            dataType: 'json',
            success: function(json) {
                if (json.status == 1) {
                    $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(2)').find('div#hasil_pencarian').css({
                        'width': Lebar + 'px'
                    });
                    $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(2)').find('div#hasil_pencarian').show('fast');
                    $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(2)').find('div#hasil_pencarian').html(json.data);
                }
                if (json.status == 0) {
                    $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(3)').html('');
                    $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(4) input').val('');
                    $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(4) span').html('');
                    $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(5) input').prop('disabled', true).val('');
                    $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(6) input').val(0);
                    $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(6) span').html('');
                }
            }
        });

        HitungTotalBayar();
    }, 500);

    $(document).on('click', '#daftar-autocomplete li', function() {
        $(this).parent().parent().parent().find('input').val($(this).find('span#kodenya').html());

        let Indexnya = $(this).parent().parent().parent().parent().index();
        let NamaBarang = $(this).find('span#barangnya').html();
        let Harganya = $(this).find('span#harganya').html();
        let Discountnya = $(this).find('span#discountnya').html();

        $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(2)').find('div#hasil_pencarian').hide();
        $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(3)').html(NamaBarang);
        $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(4) input').val(Harganya);
        $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(4) span').html(to_rupiah(Harganya));
        $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(5) input').removeAttr('disabled').val(1);
        $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(6) input').val(Harganya);
        $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(6) span').html(to_rupiah(Harganya));
        $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(7) input#discount_item').val(Discountnya);
        $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(7) input#discount_total').val(Discountnya);

        let IndexIni = Indexnya + 1;
        let TotalIndex = $('#transactionTable tbody tr').length;

        if (IndexIni == TotalIndex) {
            newLine();
            /* $('html, body').animate({
                scrollTop: $(document).height()
            }, 0); */
        } else {
            $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(5) input').focus();
        }
        HitungTotalBayar();
    });

    $(document).on('keyup', '#jumlah_beli', function() {
        let Indexnya = $(this).parent().parent().index()
        let Harga = $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(4) input').val()
        let JumlahBeli = $(this).val()
        let KodeBarang = $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(2) input').val()
        let Discont = $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(7) input#discount_item').val()

        $.ajax({
            url: "<?= base_url('productStock') ?>",
            type: "POST",
            cache: false,
            data: "barcode=" + encodeURI(KodeBarang) + "&stok=" + JumlahBeli,
            dataType: 'json',
            success: function(data) {
                if (data.status == 1) {
                    let SubTotal = parseInt(Harga) * parseInt(JumlahBeli)
                    let subDiscount = parseInt(Discont) * parseInt(JumlahBeli)
                    if (SubTotal > 0) {
                        var SubTotalVal = SubTotal
                        SubTotal = to_rupiah(SubTotal)

                        var SubTotalDiscount = subDiscount
                    } else {
                        SubTotal = ''
                        var SubTotalVal = 0

                        SubTotalDiscount = ''
                        var discountTotal = 0
                    }

                    $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(6) input').val(SubTotalVal)
                    $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(6) span').html(SubTotal)
                    $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(7) input#discount_total').val(SubTotalDiscount)
                    HitungTotalBayar()
                }
                if (data.status == 0) {
                    swalert('error', 'Error', data.message)
                    $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(5) input').val('1')
                }
            }
        });
    });

    $(document).on('keyup', '#money', function() {
        HitungTotalKembalian();
    });

    $("#money").on("input", function() {
        let inputValue = $("#money").val()
        inputValue = inputValue.replace(/,/g, '')
        let numberValue = parseFloat(inputValue)
        if (!isNaN(numberValue)) {
            let formattedValue = numberValue.toLocaleString()
            $("#money").val(formattedValue)
        }
    });

    function to_rupiah(angka) {
        var rev = parseInt(angka, 10).toString().split('').reverse().join('');
        var rev2 = '';
        for (var i = 0; i < rev.length; i++) {
            rev2 += rev[i];
            if ((i + 1) % 3 === 0 && i !== (rev.length - 1)) {
                rev2 += '.';
            }
        }
        return 'Rp. ' + rev2.split('').reverse().join('');
    }

    function HitungTotalBayar() {
        let Total = 0
        let Totaldiscount = 0
        $('#transactionTable tbody tr').each(function() {
            if ($(this).find('td:nth-child(6) input').val() > 0) {
                let SubTotal = $(this).find('td:nth-child(6) input').val()
                let Discount = $(this).find('td:nth-child(7) input#discount_total').val()
                Totaldiscount = parseInt(Totaldiscount) + parseInt(Discount)
                Total = parseInt(Total) + parseInt(SubTotal) - parseInt(Discount)
            }
        });

        $('#TotalBayar').html(to_rupiah(Total))
        $('#grandTotal').val(Total)
        $('#discountTotal').val(to_rupiah(Totaldiscount))
        $('#discountTotalUnformated').val(Totaldiscount)

        $('#money').val('')
        $('#moneyChange').val('')
    }

    function payWithPoint(cash) {
        let member = $('#dropdownMember').find(":selected").val().split('|')
        let point = parseFloat(member[2])
        return parseInt(cash) + parseInt(point)
    }

    function HitungTotalKembalian() {
        let Cash = $('#money').val().replace(/,/g, '')
        let TotalBayar = $('#grandTotal').val()
        let withPoint = $('#withPoint').is(":checked")

        if (withPoint) {
            // Cash = payWithPoint(Cash)
            let point = $('#memberPoint').val()
            let kurang = parseInt(TotalBayar) - parseInt(Cash)
            let needPoint = parseInt(kurang) - parseInt(point)
            let pointCash = parseInt(point) + parseInt(Cash)
            if (kurang <= 0) {
                $('#payPoint').val('0')
            } else if (pointCash >= TotalBayar) {
                $('#payPoint').val(kurang)
            } else {
                $('#payPoint').val('Less point')
            }
        }

        if (parseInt(Cash) >= parseInt(TotalBayar)) {
            let Selisih = parseInt(Cash) - parseInt(TotalBayar);
            if (parseInt($('#money').val().replace(/,/g, '')) == 0) {
                $('#moneyChange').val('Rp. 0');
            } else {
                $('#moneyChange').val(to_rupiah(Selisih));
            }
        } else {
            $('#moneyChange').val('Rp. 0');
        }
    }

    function check_int(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        return (charCode >= 48 && charCode <= 57 || charCode == 8);
    }

    $(document).on('keydown', 'body', function(e) {
        let charCode = (e.which) ? e.which : event.keyCode;

        if (charCode == 118) //F6
        {
            newLine();
            return false;
        }

        if (charCode == 27) //esc
        {
            $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian').hide();
        }

        if (charCode == 119) //F8
        {
            $('#money').focus();
            return false;
        }

        if (charCode == 121) //F10
        {
            $("#saveTransaction").trigger("click");
            return false;
        }
    });

    $(document).on('click', '#saveTransaction', function(e) {
        e.preventDefault()
        swal({
            title: "Save this transaction?",
            text: "You won't be able to revert this!",
            type: "warning",
            buttons: {
                cancel: {
                    visible: true,
                    text: "Cancel",
                    className: "btn btn-danger",
                },
                print: {
                    text: "Yes, without nota!",
                    className: "btn btn-warning",
                    value: "without nota",
                },
                confirm: {
                    text: "Yes, with nota!",
                    className: "btn btn-success",
                },
            },
        }).then((willDelete) => {
            if (willDelete) {
                let FormData = "notaNumber=" + encodeURI($('#notaNumber').text())
                FormData += '&' + $('#transactionTable tbody input').serialize()
                FormData += "&money=" + $('#money').val().replace(/,/g, '')
                FormData += "&grandTotal=" + $('#grandTotal').val()
                FormData += "&withPoint=" + $('#withPoint').is(":checked")
                FormData += "&member=" + $('#dropdownMember').find(":selected").val()
                FormData += "&cashier=" + $('#cashierStaff').text()
                FormData += "&cashierId=" + $('#cashierId').val()
                FormData += "&totalDiscount=" + $('#discountTotalUnformated').val()
                FormData += "&nota=" + willDelete
                $.ajax({
                    url: '<?= base_url('transactionSave'); ?>',
                    type: "post",
                    cache: false,
                    dataType: "json",
                    data: FormData,
                    beforeSend: function() {
                        // $('#absen-btn').html('<i class="fa fa-spin fa-spinner"></i>Loading...');
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            swalert(data.status, data.title, data.message)
                            setTimeout(() => {
                                location.reload()
                            }, 1000);
                        } else {
                            swalert(data.status, data.title, data.message)
                        }
                    },
                    error: function(err) {
                        notif(err.status, err.title, err.message);
                    },
                });
            } else {
                swal.close();
            }
        });
    });
</script>

<?= $this->endSection(); ?>