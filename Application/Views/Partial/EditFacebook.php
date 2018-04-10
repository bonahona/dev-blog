<form name="facebookdata" id="facebookdata">
    <input type="hidden" name="Id" value="<?php echo $Post->Id;?>"/>
    <div class="form-group">
        <label>Opengraph Title</label>
        <input type="text" name="OgTitle" value="<?php echo $Post->OgTitle;?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Opengraph Description</label>
        <input type="text" name="OgDescription" value="<?php echo $Post->OgDescription;?>" class="form-control"/>
    </div>
    <button class="btn btn-success btn-md submit" role="button">Save</button>
</form>
