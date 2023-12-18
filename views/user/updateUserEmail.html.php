<h1 class="title title-page">Mise a jour</h1>

<div class="account-update">
<h1 class="sub-title account-title">Email</h2>
<h3 class="account-actuel">Actuel : <?= $user->email?></h3>
<form action="/update-user-email-form/<?= $user->id ?>" method="post">
    <input class="account_modif" name="email" type="email">
    <button type="submit" class="call-action">Enregistrer</button>
</form>
</div>