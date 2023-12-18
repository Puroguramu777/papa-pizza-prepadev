<h1 class="title title-page">Mise a jour</h1>

<div class="account-update">
<h1 class="sub-title account-title">Nom</h2>
<h3 class="account-actuel">Actuel : <?= $user->lastname?></h3>
<form action="/update-user-lastname-form/<?= $user->id ?>" method="post">
    <input class="account_modif" name="lastname" type="text">
    <button type="submit" class="call-action">Enregistrer</button>
</form>
</div>
