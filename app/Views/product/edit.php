<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Brand</label>
    <div class="col-md-9 p-0">
        <select class="form-control brand" id="exampleFormControlSelect1" name="form[brand_id]" data-width="100%" data-placeholder="select a brand">
            <option></option>
            <?php foreach ($brand as $row) : ?>
                <option value="<?= $row['id'] ?>" <?= $content['brand_id'] == $row['id'] ? 'selected' : ''; ?>><?= $row['brand'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Product name</label>
    <div class="col-md-9 p-0">
        <input type="text" class="form-control input-full" placeholder="Enter product name" name="form[name]" value="<?= $content['name']; ?>">
    </div>
</div>
<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Barcode</label>
    <div class="col-md-9 p-0">
        <input type="text" class="form-control input-full" placeholder="Enter barcode" name="form[barcode]" value="<?= $content['barcode']; ?>">
    </div>
</div>
<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Stock</label>
    <div class="col-md-9 p-0">
        <input type="text" class="form-control input-full" placeholder="Enter stock" name="form[stock]" min="0" value="<?= $content['stock']; ?>">
    </div>
</div>
<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Purcase price</label>
    <div class="col-md-9 p-0">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Rp</span>
            </div>
            <input type="text" class="form-control purchase" placeholder="Enter price" name="form[purchase_price]" value="<?= $content['purchase_price']; ?>" aria-label="Price" aria-describedby="basic-addon1">
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
            <input type="text" class="form-control percent" placeholder="Enter percent" name="form[percent]" value="<?= $content['percent']; ?>" aria-label="Percent" aria-describedby="basic-addon1">
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
            <input type="text" class="form-control sell" placeholder="Enter price" name="form[price]" value="<?= $content['price']; ?>" aria-label="Price" aria-describedby="basic-addon1">
        </div>
    </div>
</div>
<input type="hidden" name="form[id]" value="<?= $content['id']; ?>">