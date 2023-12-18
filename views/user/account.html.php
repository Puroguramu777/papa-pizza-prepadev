<h1 class="title title-page">Mon Compte</h1>
<div class="account_table">
    <ul>                                                                                    
    <li class="sub-title">Nom:</li> <div class="account_user"><?=  $user->lastname ?><a class="account-btn button-delete call-action" href="/account/update-user-lastname/<?= $user->id?>"><i class="bi bi-pencil"></i></a></div> 
    <li class="sub-title">Prénom:</li> <div class="account_user"><?=  $user->firstname ?><a class="account-btn button-delete call-action" href="/account/update-user-firstname/<?= $user->id?>"><i class="bi bi-pencil"></i></a></div> 
    <li class="sub-title">Email:</li> <div class="account_user"><?=  $user->email ?><a class="account-btn button-delete call-action" href="/account/update-user-email/<?= $user->id?>"><i class="bi bi-pencil"></i></a></div>  
    <li class="sub-title">Téléphone:</li> <div class="account_user"><?=  $user->phone ?><a class="account-btn button-delete call-action" href="/account/update-user-phone/<?= $user->id?>"><i class="bi bi-pencil"></i></a></div>  
    </ul>
    
</div>


