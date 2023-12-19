<?php

use App\AppRepoManager; ?>
<form  action="/admin/pizza/name/<?= $pizza->id ?>" method="post">
    <input class="account_modif" name="pizzaname" type="text">
    <button type="submit" class="call-action">Enregistrer</button>
<div class="container-pizza-detail">
    
    <div class="box-image-detail">
        <img class="image-detail" src="/assets/images/pizza/<?= $pizza->image_path ?>" alt="image de<?= $pizza->name ?>">
        <div class="allergene">

            <?php if (in_array(true, array_column($pizza->ingredients, 'is_allergic'))) : ?>
                <h3 class="sub-title-allergene">Allergène :</h3>
                <?php foreach ($pizza->ingredients as $ingredient) : ?>
                    <?php if ($ingredient->is_allergic) : ?>
                        <div>
                            <span class="badge text-bg-danger"><?= $ingredient->label ?></span>
                        </div>
                    <?php endif ?>
                <?php endforeach ?>
            <?php endif ?>
        </div>
    </div>
    <div class="info-pizza-detail">
        <h3 class="sub-title sub-title-detail">Ingrédients:</h3>
        <div class="box-ingredient-detail">
            <?php foreach ($pizza->ingredients as $ingredient) : ?>
                <p class="detail-description">|<?= $ingredient->label ?>|</p>
            <?php endforeach ?>
        </div>
        <h3 class="sub-title sub-title-detail">Nos prix:</h3>
        <table>
            <thead>
                <tr>
                    <th class="footer-description">Taille</th>
                    <th class="footer-description">Prix</th>
                    <th class="footer-description"><i class="bi bi-basket3"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pizza->prices as $price) : ?>
                    <tr>
                        <td class="footer-description"><?= $price->size->label ?></td>
                        <td class="footer-description"><?= number_format($price->price, 2, ',', ' ') ?>€</td>
                        <td class="footer-description">
                            <form action="">
                                <i class="bi bi-plus-circle"></i>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <div class="box-input list-size">
            
            <?php foreach (AppRepoManager::getRm()->getSizeRepository()->getAllSize() as $size) : ?>
                <div class="list-size-input">

                    

                </div>
            <?php endforeach ?>
        </div>

         
    </div>
</div>