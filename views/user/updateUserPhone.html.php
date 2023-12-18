<h1 class="title title-page">Mise a jour</h1>

<form action="/update-user-phone-form/<?= $user->id ?>" method="post">
    <input class="account_modif" name="phone" type="number">
    <button type="submit" class="call-action">Enregistrer</button>
</form>