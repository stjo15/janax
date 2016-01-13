<?php $controller = isset($controller) ? $controller : 'comment'; ?>
<div class='comments'>
<h3>Comments
<?php
$xmlfile = ANAX_APP_PATH . 'rss/' . $pagekey . "_rss.xml";
if(file_exists($xmlfile)) {
    echo "<a href=".$this->url->create('rss/view/'.$pagekey)." title='RSS'><i class='fa fa-rss rss'></i></a>";
}
?>
</h3>

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
<a href='<?=$this->url->create($controller .'/edit/'.$id.'/'.$redirect)?>'>#<?=$id?></a>
<?php else : ?>
#<?=$id?> 
<?php endif; ?>
</h4>
<div class='comment-content'>
<?=$content?>
</div>
<div class='comment-footer'>
<p>Written by <a href='<?=$this->url->create('users/id/'.$comment['userid'])?>'><?=$comment['acronym']?></a> 
<?php $elapsedsec = (time()-strtotime($comment['timestamp'])); ?>
<?php if (($elapsedsec) < 60): ?>
<?=round($elapsedsec)?> s ago.
<?php elseif (($elapsedsec/60) < 60): ?>
<?=round($elapsedsec/60)?> min ago.
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