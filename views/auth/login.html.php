<!DOCTYPE html>
<html lang="fr">

<main class="container-form">
    <h1 class="title">Me connecter</h1>
    <!-- on va afficher les erreurs s'il y en a -->
    <?php if ($form_result && $form_result->hasErrors()) : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $form_result->getErrors()[0]->getMessage() ?>
        </div>
    <?php endif ?>


    <form class="auth-form" action="/login" method="POST">
        <div class="box-auth-input">
            <label class="detail-description">Adresse email</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="box-auth-input">
            <label class="detail-description">Mot de passe</label>
            <input type="password" class="form-control" name="password">
        </div>
        <button type="submit" class="call-action">Se connecter</button>
        
    </form>
    <p class="header-description">Je n'ai pas de compte, <a class="auth-link" href="/inscription">S'inscrire</a></p>
    
</main>
</div>

</body>

</html>