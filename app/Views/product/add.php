<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Brand</label>
    <div class="col-md-9 p-0">
        <select class="brand form-control" name="form[brand_id]" data-width="100%" data-placeholder="select a brand">
            <option></option>
            <?php foreach ($brand as $row) : ?>
                <option value="<?= $row['id'] ?>"><?= $row['brand'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Product name</label>
    <div class="col-md-9 p-0">
        <input type="text" class="form-control input-full" placeholder="Enter product name" name="form[name]">
    </div>
</div>
<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Barcode</label>
    <div class="col-md-9 p-0">
        <input type="text" class="form-control input-full" placeholder="Enter barcode" name="form[barcode]">
    </div>
</div>
<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Stock</label>
    <div class="col-md-9 p-0">
        <input type="text" class="form-control input-full" placeholder="Enter stock" name="form[stock]" min="0">
    </div>
</div>
<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Purchase price</label>
    <div class="col-md-9 p-0">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Rp</span>
            </div>
            <input type="text" class="form-control purchase" placeholder="Enter price" name="form[purchase_price]" aria-label="Price" aria-describedby="basic-addon1">
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
            <input type="text" class="form-control percent" placeholder="Enter percent" name="form[percent]" aria-label="Percent" aria-describedby="basic-addon1">
        </div>
    </div>
</div>
<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Selling price</label>
    <div class="col-md-9 p-0">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Rp</span>
            </div>
            <input type="text" class="form-control sell" placeholder="Enter price" name="form[price]" aria-label="Price" aria-describedby="basic-addon1">
        </div>
    </div>
</div>