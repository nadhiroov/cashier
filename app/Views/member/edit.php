<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Name</label>
    <div class="col-md-9 p-0">
        <input type="text" class="form-control input-full" placeholder="Enter Input" name="form[name]" value="<?= $content['name']; ?>">
    </div>
</div>
<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Phone</label>
    <div class="col-md-9 p-0">
        <input type="text" class="form-control input-full" placeholder="Enter Input" name="form[phone]" value="<?= $content['phone']; ?>">
    </div>
</div>
<div class="form-group form-inline">
    <label for="inlineinput" class="col-md-3 col-form-label">Address</label>
    <div class="col-md-9 p-0">
        <textarea class="form-control" id="comment" rows="3" cols="40" name="form[address]"><?= $content['address']; ?></textarea>
    </div>
</div>
<input type="hidden" value="<?= $content['id']; ?>" name="form[id]">