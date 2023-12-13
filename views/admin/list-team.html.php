<div class="admin-container">
    <h1 class="title">L'équipe</h1>
    <?php

    use Core\Session\Session;



    if ($form_result && $form_result->hasErrors()) : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $form_result->getErrors()[0]->getMessage() ?>
        </div>
    <?php endif ?>

    <div class="admin-box-add">
        <a class="call-action" href="/admin/pizza/add">Ajouter un membre</a>
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
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td class="footer-description"><?= $user->lastname ?></td>
                    <td class="footer-description"><?= $user->firstname ?></td>
                    <td class="footer-description"><?= $user->email ?></td>
                    <td class="footer-description"><?= $user->phone ?></td>
                    <td class="footer-description">

                        <?php
                        $session_id = Session::get(Session::USER)->id;
                        if ($session_id != $user->id) : ?>

                            <a onclick="return confirm('Etes vous certain de vouloir supprimer cet employé ?') " class="button-delete call-action" href="/admin/team/delete/<?= $user->id ?>"><i class="bi bi-trash"></i></a>
                        <?php else : ?>
                            <span class="badge text-bg-success">Connecté</span>
                        <?php endif ?>

                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>