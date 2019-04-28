<h1 class="text-center my-3">Registration</h1>
<form id="regform" enctype="multipart/form-data" method="post" action="<?= get_url('registration') ?>">
    <div class="form-group <?= isset($errors['username'])? 'has-error' : '' ?>">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?= $form_data['username'] ?>">
        <p class="box-mes"><?= isset($errors['username'])? $errors['username'] : '' ?></p> 
        <em>
            *min 5 characters max 40, only A-z, 0-9 and '-'
        </em>
    </div>
    <div class="form-group <?= isset($errors['email'])? 'has-error' : '' ?>">
        <label for="email">Your E-mail</label>
        <input type="email" name="email" id="email" value="<?= $form_data['email'] ?>">
        <p class="box-mes"><?= isset($errors['email'])? $errors['email'] : '' ?></p> 
        <em></em>
    </div>
    <div class="form-group <?= isset($errors['name'])? 'has-error' : '' ?>">
        <label for="name">Your name</label>
        <input type="text" name="name" id="name" value="<?= $form_data['name'] ?>">
        <p class="box-mes"><?= isset($errors['name'])? $errors['name'] : '' ?></p> 
        <em>*min 2 characters, only A-z</em>
    </div>
    <div class="form-group  <?= isset($errors['surname'])? 'has-error' : '' ?>">
        <label for="surname">Your surname</label>
        <input type="text" name="surname" id="surname" value="<?= $form_data['surname'] ?>">
        <p class="box-mes"><?= isset($errors['surname'])? $errors['surname'] : '' ?></p> 
        <em>*min 2 characters, only A-z</em>
    </div>
    <div class="form-group  <?= isset($errors['image'])? 'has-error' : '' ?>">
        <label for="profileimage">Profile image</label>
        <input class="image-file" type="file" accept="image/*,image/jpeg,image/png" name="profileimg" id="profileimage">
        <p class="box-mes"><?= isset($errors['image'])? $errors['image'] : '' ?></p> 
        <em>*only jpg, jpeg, png</em>
    </div>
    <div class="form-group <?= isset($errors['password'])? 'has-error' : '' ?>">
        <label for="password" >Create password</label>
        <input type="password" name="password" id="password" value="<?= $form_data['password'] ?>">
        <p class="box-mes"><?= isset($errors['password'])? $errors['password'] : '' ?></p> 
        <em>*min 8 characters</em>
    </div>
    <div class="form-group <?= isset($errors['repeatpassword'])? 'has-error' : '' ?>">
        <label for="repeatpassword">Repeat password</label>
        <input type="password" id="repeatpassword" name="repeatpassword" value="<?= $form_data['repeatpassword'] ?>">
        <p class="box-mes"><?= isset($errors['repeatpassword'])? $errors['repeatpassword'] : '' ?></p> 
        <em>*min 8 characters</em>
    </div>
    <div class="form-group">
        <div class="text-center">
            <button id="send_regform" class="btn" type="submit">submit</button>
        </div>
    </div>
</form>