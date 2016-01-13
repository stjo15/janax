<?php if(isset($_SESSION['user'])): ?>
    <?php if(($_SESSION['user']->acronym == $user->acronym) || ($_SESSION['user']->acronym == 'admin')): ?>
    <ul> 
        <li>Username: <?=$user->acronym?></li>
        <li>User Id: <?=$user->id?></li>
        <li>Created on: <?=$user->created?></li>
        <li>Updated on: <?=$user->updated?></li>
        <li>Deletion time: <?=$user->deleted?></li>
        <li>Active since: <?=$user->active?></li>
    </ul> 
    <?php endif; ?>
<?php endif; ?>
