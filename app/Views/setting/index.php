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
                <a href="<?= base_url() ?>/procduct"><?= $menu; ?></a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">Point</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group form-inline">
                        <label for="inlineinput" class="col-md-3 col-form-label">Nominal</label>
                        <div class="col-md-9 p-0">
                            <label for="inlineinput" class="col-md-3 col-form-label"><?= $point['nominal'] ?? '-'; ?></label>
                        </div>
                    </div>
                    <div class="form-group form-inline">
                        <label for="inlineinput" class="col-md-3 col-form-label">Point</label>
                        <div class="col-md-9 p-0">
                            <label for="inlineinput" class="col-md-3 col-form-label"><?= $point['point'] ?? '-'; ?></label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-lg-3 col-md-3 col-sm-12">

                            </div>
                            <div class="col-lg-4 col-md-9 col-sm-12">
                                <a href="#editPoint" data-toggle="modal" class="btn btn-success">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">Detail info</div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table mt-3">
                        <tbody>
                            <tr>
                                <td>Name</td>
                                <td><?= @$content['name']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal edit point-->
<div class="modal fade" id="editPoint" tabindex="-1" role="dialog" aria-labelledby="addnewLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addnewLabel">Edit point</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="formEditPoint" action="<?= base_url() ?>/pointSave" method="POST">
                <div class="modal-body">
                    <div class="form-group form-inline">
                        <label for="inlineinput" class="col-md-3 col-form-label">Nominal</label>
                        <div class="col-md-9 p-0">
                            <input type="text" class="form-control input-full" placeholder="Enter Input" name="form[nominal]" value="<?= $point['nominal'] ?? ''; ?>">
                        </div>
                    </div>
                    <div class="form-group form-inline">
                        <label for="inlineinput" class="col-md-3 col-form-label">Point</label>
                        <div class="col-md-9 p-0">
                            <input type="text" class="form-control input-full" placeholder="Enter Input" name="form[point]" value="<?= $point['point'] ?? ''; ?>">
                        </div>
                    </div>
                    <?php if (isset($point['id'])) echo "<input type='hidden' class='form-control input-full' placeholder='Enter Input' name='form[id]' value='$point[id]'"; ?>
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
<script src="<?= base_url() ?>/assets/js/plugin/datatables/datatables.min.js"></script>
<script src="<?= base_url() ?>/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="<?= base_url() ?>/assets/js/plugin/sweetalert/sweetalert.min.js"></script>
<script>
    $('.formEditPoint').submit(function(e) {
        e.preventDefault();
        saveData(this);
        $("#editPoint").modal("hide");
        setTimeout(() => {
            location.reload()
        }, 1.5);
    });

    $('#edit').on('show.bs.modal', function(e) {
        let rowid = $(e.relatedTarget).data('id');
        console.log(rowid)
        if (typeof rowid != 'undefined') {
            $.ajax({
                type: 'get',
                url: `<?= base_url() ?>/memberEdit/${rowid}`,
                success: function(data) {
                    $('.edited-body').html(data);
                }
            });
        }
    });
</script>
<?= $this->endSection(); ?>