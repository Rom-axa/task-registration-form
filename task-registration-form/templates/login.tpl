<h1 class="text-center my-3">Login</h1>
<form id="logform" method="post" action="<?= get_url('login') ?>">
    <div class="form-group <?= isset($errors['username'])? 'has-error' : '' ?>">
        <label for="logusername">Login</label>
        <input type="text" name="username" id="logusername" value="<?= $form_data['username'] ?>" placeholder="Login">
        <p class="box-mes"><?= isset($errors['username'])? $errors['username'] : '' ?></p> 
        <em>
            *min 5 characters max 40, only A-z, 0-9 and '-'
        </em>
    </div>
    <div class="form-group <?= isset($errors['password'])? 'has-error' : '' ?>">
        <label for="logpassword">Your Password</label>
        <input type="password" name="password" id="logpassword" value="<?= $form_data['password'] ?>" placeholder="********">
        <p class="box-mes"><?= isset($errors['password'])? $errors['password'] : '' ?></p> 
        <em></em>
    </div>
    
    <div>
        <label for="remember" class="pointer">Remember me</label>
        <input type="checkbox" id="remember" name="rememberMe" value="1">
        <em></em>
    </div>
    <div class="form-group">
        <div class="text-center">
            <button id="send_logform" class="btn" type="submit">Sign in</button>
        </div>
    </div>
</form>
