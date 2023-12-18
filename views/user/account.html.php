<h1 class="title title-page">Mon Compte</h1>
<div class="account_table">
    <ul>                                                                                    
        <div class="change"><li class="sub-title">Nom:</li> <div class="account_user"><?=  $user->lastname ?><a class="call-action account-btn" href="/account/update-user-lastname/<?= $user->id?>">Mettre a jour</a></div> </div>
        <div class="change"><li class="sub-title">Nom:</li> <div class="account_user"><?=  $user->firstname ?><a class="call-action account-btn" href="/account/update-user-firstname/<?= $user->id?>">Mettre a jour</a></div> </div> 
        <div class="change"><li class="sub-title">Nom:</li> <div class="account_user"><?=  $user->email ?><a class="call-action account-btn" href="/account/update-user-email/<?= $user->id?>">Mettre a jour</a></div> </div> 
        <div class="change"><li class="sub-title">Nom:</li> <div class="account_user"><?=  $user->phone ?><a class="call-action account-btn" href="/account/update-user-phone/<?= $user->id?>">Mettre a jour</a></div> </div> 
    </ul>
    
</div>


