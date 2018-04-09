<div class="row">
    <div class="col-lg-12">
        <?php echo $this->Form->Start('PostMetaData');?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="#" class="btn btn-primary">Deactivate</a>
                    <a href="#" class="btn btn-primary">Move up</a>
                    <a href="#" class="btn btn-primary">Move down</a>
                </div>
                <div class="panel-body">
                    <?php echo $this->Form->Hidden('Id');?>
                    <div class="form-group">
                        <?php echo $this->Form->Area('Content', array('attributes' => array('class' => 'form-control summernote', 'rows' => '30')));?>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="#" class="btn btn-success btn-md">Save</a>
                            <a href="#" class="btn btn-danger btn-md">Revert</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php echo $this->Form->End();?>
    </div>
</div>