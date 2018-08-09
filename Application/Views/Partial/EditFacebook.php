<form name="opengraphdata" id="opengraphdata">
    <input type="hidden" name="Id" value="<?php echo $Post->Id;?>"/>
    <div class="form-group">
        <label>Opengraph Title</label>
        <input type="text" name="OgTitle" value="<?php echo $Post->OgTitle;?>" class="form-control"/>
        <p class="small">(Clear title, not brand names)</p>
    </div>
    <div class="form-group">
        <label>Opengraph Description</label>
        <input type="text" name="OgDescription" value="<?php echo $Post->OgDescription;?>" class="form-control"/>
        <p class="small">(Descriptive and at least two sentences long)</p>
    </div>
    <div class="form-group">
        <label>Opengraph Image</label>
        <input type="text" name="OgImageUrl" value="<?php echo $Post->OgImageUrl;?>" class="form-control"/>
        <p class="small">(At least 1200 x 630 pixels)</p>
    </div>
    <button class="btn btn-success btn-md submit" role="button">Save</button>
</form>
