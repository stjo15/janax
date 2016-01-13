<h4><?=$title?></h4>

<?php if (is_array($users)) : ?>

<ol class='sidebar-list'>
<?php foreach ($users as $id => $user) : ?>
<?php $id = (is_object($user)) ? $user->id : $id; ?>
<?php $user = (is_object($user)) ? get_object_vars($user) : $user; ?>
    <?php if(formatSeconds($user['bestlap']) != '--:--:--') : ?>
        <li><a href='<?=$this->url->create('users/id/'.$id)?>'><?=$user['acronym']?></a>: <i class="fa fa-flag fa-1x"></i> <?=formatSeconds($user['bestlap'])?></i></li>
    <?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
</ol>