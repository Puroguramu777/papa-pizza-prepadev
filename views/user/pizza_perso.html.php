<?php

use App\AppRepoManager;
use App\Model\Ingredient;
use Core\Session\Session; ?>

<!DOCTYPE html>
<html lang="fr">

<main class="container-form">
    <h1 class="title">Pizza Personalisée</h1>
    <a class="pp title-page call-action" href="/pizzas/mes-pizzas/<?= $user_id ?>">Voir mes pizzas</a> 
    <!-- on va afficher les erreurs s'il y en a -->
    <?php if ($form_result && $form_result->hasErrors()) : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $form_result->getErrors()[0]->getMessage() ?>
        </div>
    <?php endif ?>


    <form class="auth-form" action="/add-pizza-form" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?= Session::get(Session::USER)->id ?>">
        <div class="box-auth-input">
            <label class="detail-description">Nom de la pizza</label>
            <input type="text" class="form-control" name="name">
        </div>
        <div class="box-auth-input">
            <label class="detail-description">Charger une image (Optionel)</label>
            <input type="file" class="form-control" name="image_path">
        </div>
        <div class="box-auth-input list-ingredient">
            <?php foreach (AppRepoManager::getRm()->getIngredientRepository()->getIngredientActive() as $ingredient) : ?>

                <div class="form-check form-switch list-ingredient-input">
                    <input name="ingredients[]" value="<?= $ingredient->id ?>" class="form-check-input " type="checkbox" role="switch">
                    <label class="form-check-label footer-description"><?= $ingredient->label ?></label>
                </div>


            <?php endforeach ?>
            

        </div>
        <button type="submit" class="call-action">Créer la pizza</button>

    </form>
</main>
</div>

</body>

</html>