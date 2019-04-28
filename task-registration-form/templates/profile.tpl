<h1 class="text-center my-3 display-1"><?= $USER['username']. ' id# ' .$USER['id'] ?></h1>
<div class="d-flex justify-content-between">
    <div>
        <h3 class="my-1"><span class="bold">Name:</span> <?= $USER['name'] ?></h3>
        <h3 class="my-1"><span class="bold">Surname:</span> <?= $USER['surname'] ?></h3>
        <h3 class="my-1"><span class="bold">E-mail:</span> <?= $USER['email'] ?></h3>
    </div>
    <div class="mw-50">
        <img class="image-cover" src="<?= '/' .DOMAIN. '/upload/' .$USER['image'] ?>" alt="main photo">
    </div>
</div>
