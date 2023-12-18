<h1 class="title title-page">Mise a jour</h1>

<form action="/update-user-email-form/<?= $user->id ?>" method="post">
    <input class="account_modif" name="email" type="email">
    <button type="submit" class="call-action">Enregistrer</button>
</form>