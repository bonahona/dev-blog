<?php echo $this->Form->Start('User', array('attributes' => array('class' => 'form-signin')));?>
    <h2 class="form-signin-heading">Please sign in</h2>
    <label for="Username" class="sr-only">Email address</label>
    <?php echo $this->Form->Input('Username', array('attributes' => array('class' => 'form-control', 'placeholder' => 'Username', 'required' => 'true', 'autofocus' => 'true')));?>
    <label for="Password" class="sr-only">Password</label>
    <?php echo $this->Form->Password('Password', array('attributes' => array('class' => 'form-control', 'placeholder' => 'Password', 'required' => 'true')));?>
    <?php echo $this->Form->Submit('Sign in', array('attributes' => array('class' => 'btn btn-lg btn-primary btn-block')));?>
    <?php echo $this->Form->ValidationErrorFor('Password');?>

    <div class="row">
        <div class="col-lg-4">
            <a href="/">Back</a>
        </div>
    </div>
<?php echo $this->Form->End();?>

