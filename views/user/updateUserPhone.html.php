<h1 class="title title-page">Mise a jour</h1>

<div class="account-update">
    <h1 class="sub-title account-title">Téléphone</h2>
    <h3 class="account-actuel">Actuel : <?= $user->phone?></h3>
<form action="/update-user-phone-form/<?= $user->id ?>" method="post">
    <input class="account_modif" name="phone" type="number">
    <button type="submit" class="call-action">Enregistrer</button>
</form>
</div>