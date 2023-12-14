<h1 class="title title-page">Mon Compte</h1>
<div class="account_table">
    <ul>                                                                                    
        <li class="sub-title">Nom:</li> <div class="account_user"><?=  $user->firstname ?></div> 
        <li class="sub-title">Prénom:</li> <div class="account_user"><?= $user->lastname ?></div> 
        <li class="sub-title">Email:</li> <div class="account_user"><?= $user->email ?></div> 
        <li class="sub-title">Téléphone:</li> <div class="account_user"><?= $user->phone ?></div> 
    </ul>
    <a class="call-action" href="/account/update-user/<?= $user->id?>">Mettre a jour</a>
</div>



