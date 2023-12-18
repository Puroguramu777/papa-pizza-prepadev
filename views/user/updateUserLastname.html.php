<h1 class="title title-page">Mise a jour</h1>

<form action="/update-user-lastname-form/<?= $user->id ?>" method="post">
    <input class="account_modif" name="lastname" type="text">
    <button type="submit" class="call-action">Enregistrer</button>
</form>