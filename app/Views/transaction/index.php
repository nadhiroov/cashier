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
                            <label class="col-form-label"><?= strtoupper(uniqid()); ?></label>
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
                            <label class="col-form-label"><?= session()->get('fullname'); ?></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Member Info</h4>
                    <div class="card-head-row card-tools-still-right">
                        <button class="btn btn-primary btn-round ml-auto btn-xs" data-toggle="modal" data-target="#addnew">
                            Add new
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group form-inline">
                        <label class="col-md-3 col-form-label">Name</label>
                        <div class="col-md-9 p-0">
                            <select class="form-control member" name="form[member]" data-width="100%">
                                <option>General</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-inline">
                        <label class="col-md-3 col-form-label">Phone</label>
                        <div class="col-md-9 p-0">
                            <label class="col-form-label" id="member-phone">-</label>
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
                            <h2 class="white-text">Total : <span id='TotalBayar' class="white-text">Rp. 0</span></h2>
                            <input type="hidden" id='TotalBayarHidden'>
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
                    $('.member').append(`<option value="${option.id}|${option.phone}">${option.name}</option>`);
                });
            }
        });
    }

    $('.member').change(function() {
        let selectedValue = $(this).val()
        if (selectedValue == 'General') {
            $('#member-phone').html('-');
        } else {
            let values = selectedValue.split('|')
            let phone = parseFloat(values[1])
            $('#member-phone').html(phone);
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
        Baris += "<input type='text' class='form-control' name='kode_barang[]' id='pencarian_kode' placeholder='Code / item name'>";
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
        Baris += "<td><button class='btn btn-icon btn-round btn-danger' id='deleteLine'><i class='fas fa-trash-alt'></i></button></td>";
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
                    BarisBaru();

                    $('html, body').animate({
                        scrollTop: $(document).height()
                    }, 0);
                } else {
                    $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(5) input').focus();
                }
            } else if (charCode == 27) { // escape
                let Indexnya = $(this).parent().parent().parent().parent().index();
                var IndexIni = $(this).parent().parent().index() + 1;
                console.info(Indexnya)
                console.info(IndexIni)
                // $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(2)').find('div#hasil_pencarian').hide();
            } else {
                AutoCompleteGue($(this).width(), $(this).val(), $(this).parent().parent().index());
            }
        } else {
            $('#transactionTable tbody tr:eq(' + $(this).parent().parent().index() + ') td:nth-child(2)').find('div#hasil_pencarian').hide();
        }
        HitungTotalBayar();
    });

    function AutoCompleteGue(Lebar, KataKunci, Indexnya) {
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
    }

    $(document).on('click', '#daftar-autocomplete li', function() {
        $(this).parent().parent().parent().find('input').val($(this).find('span#kodenya').html());

        let Indexnya = $(this).parent().parent().parent().parent().index();
        let NamaBarang = $(this).find('span#barangnya').html();
        let Harganya = $(this).find('span#harganya').html();

        $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(2)').find('div#hasil_pencarian').hide();
        $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(3)').html(NamaBarang);
        $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(4) input').val(Harganya);
        $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(4) span').html(to_rupiah(Harganya));
        $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(5) input').removeAttr('disabled').val(1);
        $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(6) input').val(Harganya);
        $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(6) span').html(to_rupiah(Harganya));

        let IndexIni = Indexnya + 1;
        let TotalIndex = $('#transactionTable tbody tr').length;

        if (IndexIni == TotalIndex) {
            newLine();
        } else {
            $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(5) input').focus();
        }
        HitungTotalBayar();
    });

    $(document).on('keyup', '#jumlah_beli', function() {
        var Indexnya = $(this).parent().parent().index();
        var Harga = $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(4) input').val();
        var JumlahBeli = $(this).val();
        var KodeBarang = $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(2) input').val();

        $.ajax({
            url: "<?= base_url('productStock'); ?>",
            type: "POST",
            cache: false,
            data: "barcode=" + encodeURI(KodeBarang) + "&stok=" + JumlahBeli,
            dataType: 'json',
            success: function(data) {
                if (data.status == 1) {
                    var SubTotal = parseInt(Harga) * parseInt(JumlahBeli);
                    if (SubTotal > 0) {
                        var SubTotalVal = SubTotal;
                        SubTotal = to_rupiah(SubTotal);
                    } else {
                        SubTotal = '';
                        var SubTotalVal = 0;
                    }

                    $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(6) input').val(SubTotalVal);
                    $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(6) span').html(SubTotal);
                    HitungTotalBayar();
                }
                if (data.status == 0) {
                    swalert('error', 'Error', data.message)
                    // $('.modal-dialog').removeClass('modal-lg');
                    // $('.modal-dialog').addClass('modal-sm');
                    // $('#ModalHeader').html('Oops !');
                    // $('#ModalContent').html(data.pesan);
                    // $('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok, Saya Mengerti</button>");
                    // $('#ModalGue').modal('show');

                    $('#transactionTable tbody tr:eq(' + Indexnya + ') td:nth-child(5) input').val('1');
                }
            }
        });
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
        var Total = 0;
        $('#transactionTable tbody tr').each(function() {
            if ($(this).find('td:nth-child(6) input').val() > 0) {
                var SubTotal = $(this).find('td:nth-child(6) input').val();
                Total = parseInt(Total) + parseInt(SubTotal);
            }
        });

        $('#TotalBayar').html(to_rupiah(Total));
        $('#TotalBayarHidden').val(Total);

        $('#UangCash').val('');
        $('#UangKembali').val('');
    }
</script>

<?= $this->endSection(); ?>