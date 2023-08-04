<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Product</label>
    <div class="col-md-9 p-0">
        <select class="form-control js-example-basic-single" name="form[product_id]" data-width="100%">
            <option></option>
            <?php foreach ($product as $row) : ?>
                <option value="<?= $row['id'] ?>" <?= $content['product_id'] == $row['id'] ? 'selected' : ''; ?>><?= $row['name'] . ' - Rp. ' . $row['price'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Discount</label>
    <div class="col-md-9 p-0">
        <input type="text" class="form-control input-full" placeholder="Enter discount by percentage" name="form[discount]" value="<?= $content['discount']; ?>">
    </div>
</div>
<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Date</label>
    <div class="col-md-9 p-0 input-group">
        <input type="text" class=" form-control daterange" placeholder="Pick a date" name="form[date]">
        <div class="input-group-append">
            <span class="input-group-text">
                <i class="fa fa-calendar-check"></i>
            </span>
        </div>
    </div>
</div>
<input type="hidden" name="form[id]" value="<?= $content['id']; ?>">