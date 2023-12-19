<div class="admin-container">
    <h1 class="title">Les Pizzas</h1>
    <?php

    use Core\Session\Session;



    if ($form_result && $form_result->hasErrors()) : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $form_result->getErrors()[0]->getMessage() ?>
        </div>
    <?php endif ?>

    <div class="admin-box-add">
        <a class="call-action" href="/admin/pizza/add">Ajouter une pizza</a>
    </div>

    <table>
        <thead>
            <tr>
                <th class="footer-description">Nom</th>
                <th class="footer-description">Prénom</th>
                <th class="footer-description">Email</th>
                <th class="footer-description">Téléphone</th>
                <th class="footer-description">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pizzas as $pizza) : ?>
                <tr>
                    <td class="footer-description"><?= $pizza->name ?></td>
                    <td class="footer-description">
                        <img class="admin-img-pizza" src="/assets/images/pizza/<?= $pizza->image_path ?>" alt="<?= $pizza->name ?>">
                    </td>

                    <td class="footer-description">
                        <?php foreach ($pizza->prices as $price) : ?>

                            <p> <?= $price->size->label ?> : <?= number_format($price->price, 2, ',', ' ') ?>€</p>

                        <?php endforeach ?>
                    </td>
                    <td class="footer-description">
                        <?php foreach ($pizza->ingredients as $ingredient) : ?>

                            <p> <?= $ingredient->label ?></p>

                        <?php endforeach ?>
                    </td>
                    <td class="footer-description">

                        <a onclick="return confirm('Etes vous certain de vouloir supprimer cette pizza ?') " class="button-delete call-action" href="/admin/pizza/delete/<?= $pizza->id ?>"><i class="bi bi-trash"></i></a>
                        <a class="button-delete call-action" href="/admin/pizza/updateview/<?= $pizza->id ?>"><i class="bi bi-pencil"></i></a>

                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>