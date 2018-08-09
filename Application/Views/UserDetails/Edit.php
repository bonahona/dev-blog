<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Edit user</h1>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <?php echo $this->Form->Start('LocalUserDetails');?>
                <?php echo $this->Form->Hidden('Id');?>
                <div class="form-group">
                    <label>Name</label>
                    <?php echo $this->Form->Input('Name', array('attributes' => array('class' => 'form-control')));?>
                </div>
                <div class="form-group">
                    <label>First Name</label>
                    <?php echo $this->Form->Input('FirstName', array('attributes' => array('class' => 'form-control')));?>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <?php echo $this->Form->Input('LastName', array('attributes' => array('class' => 'form-control')));?>
                </div>
                <div class="form-group">
                    <label>FacebookId</label>
                    <?php echo $this->Form->Input('FacebookId', array('attributes' => array('class' => 'form-control')));?>
                </div>
                <?php echo $this->Form->Submit('Save', array('attributes' => array('class' => 'btn btn-md btn-success')));?>
                <?php echo $this->Form->End();?>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-12">
                <a href="/users" class="btn btn-outline-success btn-md">Back</a>
            </div>
        </div>
    </div>
</div>