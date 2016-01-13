<?php 
    $deletetype = null;
    $deletetext = null;
    $restorelink = null;
    if (isset($user->deleted)) {
        $deletetype = 'delete';
        $deletetext = 'Delete account permanently';
        $restorelink = 'restore';
        $restoretext = 'Restore account';
    } else {
        $deletetype = 'soft-delete';
        $deletetext = 'Delete account';
    }
    $active = null;
    $activetext = null;
    if (isset($user->active)) {
        $active = 'inactivate';
        $activetext = 'Inactivate account';
    } else {
        $active = 'activate';
        $activetext = 'Activate account';
    }
?>
<?php if(isset($_SESSION['user'])): ?>
    <?php if(($_SESSION['user']->acronym == $user->acronym) || ($_SESSION['user']->acronym == 'admin')): ?>
    <h2 id='option-title'><?=$title?></h2>
    <ul id='option-list'> 
        <li><a href='<?=$this->url->create('users/update/' . $user->id)?>'>Edit information</a></li>
        <li><a href='<?=$this->url->create('users/' . $active . '/' . $user->id)?>'><?=$activetext?></a></li>
        <li class='warning'><a href='<?=$this->url->create('users/' . $deletetype . '/' . $user->id)?>'><?=$deletetext?></a></li>
        <?php if(isset($restorelink)): ?>
            <li><a href='<?=$this->url->create('users/' . $restorelink . '/' . $user->id)?>'><?=$restoretext?></a></li>
        <?php endif; ?>
    </ul>
    <?php endif; ?>
<?php endif; ?>
