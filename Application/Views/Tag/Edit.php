<div class="card my-4">
    <div class="card-body">
        <h1>Edit Tag</h1>

        <div class="row">
            <div class="col-lg-12">
                <?php echo $this->Form->Start('Tag');?>
                <?php echo $this->Form->Hidden('Id');?>
                <div class="form-group">
                    <label>Display name</label>
                    <?php echo $this->Form->Input('DisplayName', array('attributes' => array('class' => 'form-control')));?>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <?php echo $this->Form->Bool('IsActive');?>
                            Is Active
                        </label>
                    </div>
                </div>
                <?php echo $this->Form->Submit('Save', array('attributes' => array('class' => 'btn btn-md btn-success')));?>
                <?php echo $this->Form->End();?>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-12">
                <a href="/tag" class="btn btn-outline-success btn-md">Back</a>
            </div>
        </div>
    </div>
</div>