<?php $controller = isset($controller) ? $controller : 'question'; ?>
<div class='comments'>
<h3><?=$title?></h3>

<?php if (is_array($comments)) : ?>

<?php foreach ($comments as $id => $comment) : ?>
<?php $id = (is_object($comment)) ? $comment->id : $id; ?>
<?php $comment = (is_object($comment)) ? get_object_vars($comment) : $comment; ?>

<?php
$content = $this->textFilter->doFilter($comment['content'], 'shortcode, markdown');
?>

<div class='comment'>
<h4>
<?php if(isset($_SESSION['user']) && $_SESSION['user']->acronym == $comment['acronym']) : ?>
#<?=$id?> 
<?php else : ?>
#<?=$id?> 
<?php endif; ?>
<?php $id = ($controller == 'answer') ? $comment['pagekey'] : $id ?>
<a href='<?=$this->url->create('question/view/'.$id.'/'.$redirect)?>'><?=$comment['title']?></a>
</h4>

<div class='comment-content'>
<?=$content?>
</div>

<?php if($controller == 'question') : ?>
<?php 
$tagslugs = explode(",", $comment['tagslug']);
$tags = explode(",", $comment['tag']);
for($i = 0; $i < count($tags); $i++) {
    echo "<span class='tag-label'><a href='".$this->url->create('question/list/'.$tagslugs[$i])."'>" . $tags[$i] . " </a></span>";
}
?>
<?php endif; ?>

<div class='comment-footer'>
<p>Written by <a href='<?=$this->url->create('users/id/'.$comment['userid'])?>'><?=$comment['acronym']?></a> 
<?php $elapsedsec = (time()-strtotime($comment['timestamp'])); ?>
<?php if (($elapsedsec) < 60): ?>
<?=round($elapsedsec)?> s ago.
<?php elseif (($elapsedsec/60) < 60): ?>
<?=round($elapsedsec/60)?> minutes ago.
<?php elseif (($elapsedsec/(60*60)) < 24): ?>
<?=round($elapsedsec/(60*60))?> h ago.
<?php elseif (($elapsedsec/(60*60*24)) < 7): ?>
<?=round($elapsedsec/(60*60*24))?> days ago.
<?php elseif (($elapsedsec/(60*60*24)) < 30) : ?>
<?=round($elapsedsec/(60*60*24*7))?> weeks ago.
<?php else : ?>
<?=round($elapsedsec/(60*60*24*30))?> months ago.
<?php endif; ?></p>
<img class='gravatar' src='<?=$comment['gravatar']?>?s=40' alt='gravatar'> 
<?php if (!empty($comment['web'])) : ?>
<?php $prefix = preg_match('/^[www]/', $comment['web']) ? 'http://' : '';?>
<a href='<?=$prefix.$comment['web']?>' target='_blank'>Website</a>
<?php endif; ?>
</div>
</div>

<?php endforeach; ?>

<?php endif; ?>
<?php if (is_string($comments)) : ?>
<p class='comment'><?=$comments?></p>
<?php endif; ?>
</div> 