<div class="form-check">
    <label class="form-check-label">
        <input class="form-check-input checkPrice" type="checkbox" name="form[checkPrice]" value="1" checked>
        <span class="form-check-sign">Change price</span>
    </label>
</div>
<div class="form-check">
    <label class="form-check-label">
        <input class="form-check-input checkStock" type="checkbox" name="form[checkStock]" value="1" checked>
        <span class="form-check-sign">Change stock</span>
    </label>
</div>
<div class="priceForm">
    <div class="form-group form-inline">
        <label for="inlineinput" class="col-md-3 col-form-label">Purcase price</label>
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
</div>

<div class="stockForm">
    <div class="form-group form-inline">
        <label for="inlineinput" class="col-md-3 col-form-label">Incoming product</label>
        <div class="col-md-9 p-0">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Enter incoming product" name="form[stock]" aria-label="Price" aria-describedby="basic-addon1">
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="form[id]" value="<?= $content['id']; ?>">