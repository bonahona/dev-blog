<?php /** @var $this Controller */?>

<?php echo $this->Form->Start('User', array('attributes' => array('class' => 'form-signin')));?>
    <h2 class="form-signin-heading">Please sign in</h2>
    <label for="Username" class="sr-only">Email address</label>
    <?php echo $this->Form->Input('Username', array('attributes' => array('class' => 'form-control', 'placeholder' => 'Username', 'required' => 'true', 'autofocus' => 'true')));?>
    <label for="Password" class="sr-only">Password</label>
    <?php echo $this->Form->Password('Password', array('attributes' => array('class' => 'form-control', 'placeholder' => 'Password', 'required' => 'true')));?>
    <?php echo $this->Form->Submit('Sign in', array('attributes' => array('class' => 'btn btn-lg btn-primary btn-block')));?>
    <div class="row">
        <div class="col-lg-12">
            <?php var_dump($this->ModelValidation->GetModelError('User'));?>
            <?php foreach($this->ModelValidation->GetModelError('User') as $error):?>
                <div class="alert alert-danger">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span><?php echo $error;?></span>
                </div>
            <?php endforeach;?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <a href="/">Back</a>
        </div>
    </div>
<?php echo $this->Form->End();?>

