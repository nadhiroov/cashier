<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Price</label>
    <div class="col-md-9 p-0">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Rp</span>
            </div>
            <input type="text" class="form-control" placeholder="Enter price" name="form[price]" aria-label="Price" aria-describedby="basic-addon1" value="<?= $content['price']; ?>">
        </div>
    </div>
</div>
<input type="hidden" name="form[id]" value="<?= $content['id']; ?>">