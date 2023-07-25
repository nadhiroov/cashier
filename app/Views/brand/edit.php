<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Category</label>
    <div class="col-md-9 p-0">
        <select class="form-control category" <?= isset($fromCategory) ? 'disabled' : ''; ?> name="form[category_id]" data-width="100%">
            <?php foreach ($category as $row) : ?>
                <option <?= $content['category_id'] == $row['id'] ? 'selected' : ''; ?> value="<?= $row['id'] ?>"><?= $row['category'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Brand</label>
    <div class="col-md-9 p-0">
        <input type="text" class="form-control input-full" placeholder="Enter Input" name="form[brand]" value="<?= $content['brand']; ?>">
    </div>
</div>
<input type="hidden" name="form[id]" value="<?= $content['id']; ?>">