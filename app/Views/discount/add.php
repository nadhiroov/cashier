<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Category</label>
    <div class="col-md-9 p-0">
        <select class="form-control category" name="form[category_id]" data-width="100%">
            <option></option>
            <?php foreach ($product as $row) : ?>
                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Discount</label>
    <div class="col-md-9 p-0">
        <input type="text" class="form-control input-full" placeholder="Enter discount by percentage" name="form[discount]">
    </div>
</div>
<div class="form-group">
    <label>Input Date Picker</label>
    <div class="input-group">
        <!-- <input type="text" class="form-control" id="datepicker" name="datepicker"> -->
        <input type="text" data-toggle="datepicker" class="form-control docs-date" name="date" placeholder="Pick a date" autocomplete="off">
        <div class="input-group-append">
            <span class="input-group-text">
                <i class="fa fa-calendar-check"></i>
            </span>
        </div>
    </div>
</div>