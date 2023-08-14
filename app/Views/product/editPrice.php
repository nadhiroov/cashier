<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Purcase price</label>
    <div class="col-md-9 p-0">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Rp</span>
            </div>
            <input type="text" class="form-control" placeholder="Enter price" name="form[purchase_price]" aria-label="Price" aria-describedby="basic-addon1">
        </div>
    </div>
</div>
<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Percent</label>
    <div class="col-md-9 p-0">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">%</span>
            </div>
            <input type="text" class="form-control" placeholder="Enter percent" name="form[percent]" aria-label="Percent" aria-describedby="basic-addon1">
        </div>
    </div>
</div>
<input type="hidden" name="form[id]" value="<?= $content['id']; ?>">