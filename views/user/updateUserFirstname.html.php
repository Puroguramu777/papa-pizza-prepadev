<h1 class="title title-page">Mise a jour</h1>

<div class="account-update">
<h1 class="sub-title account-title">Prénom</h2>
<h3 class="account-actuel">Actuel : <?= $user->firstname?></h3>
<form action="/update-user-firstname-form/<?= $user->id ?>" method="post">
    <input class="account_modif" name="firstname" type="text">
    <button type="submit" class="call-action">Enregistrer</button>
</form>
</div>