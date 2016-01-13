<?php $controller = isset($controller) ? $controller : 'answer'; ?>
<div class='answers'>
<h3>Answers
<?php
$xmlfile = ANAX_APP_PATH . 'rss/' . $pagekey . "_rss.xml";
if(file_exists($xmlfile)) {
    echo "<a href=".$this->url->create('rss/view/'.$pagekey)." title='RSS'><i class='fa fa-rss rss'></i></a>";
}
?>
</h3>

<?php if (is_array($answers)) : ?>

<?php foreach ($answers as $id => $answer) : ?>
<?php $id = (is_object($answer)) ? $answer->id : $id; ?>
<?php $answer = (is_object($answer)) ? get_object_vars($answer) : $answer; ?>

<?php
$content = $this->textFilter->doFilter($answer['content'], 'shortcode, markdown');
?>

<div class='comment'>
<h4>
<?php if(isset($_SESSION['user']) && $_SESSION['user']->acronym == $answer['acronym']) : ?>
<a href='<?=$this->url->create($controller .'/edit/'.$id.'/'.$redirect)?>'>#<?=$id?></a>
<?php else : ?>
#<?=$id?> 
<?php endif; ?>
</h4>
<div class='comment-content'>
<?=$content?>
</div>
<div class='comment-footer'>
<p>Written by <a href='<?=$this->url->create('users/id/'.$answer['userid'])?>'><?=$answer['acronym']?></a>  
<?php $elapsedsec = (time()-strtotime($answer['timestamp'])); ?>
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
<img class='gravatar' src='<?=$answer['gravatar']?>?s=40' alt='gravatar'> 
<?php if (!empty($answer['web'])) : ?>
<?php $prefix = preg_match('/^[www]/', $answer['web']) ? 'http://' : '';?>
<a href='<?=$prefix.$answer['web']?>' target='_blank'>Website</a>
<?php endif; ?>
</div>
</div>

<?php endforeach; ?>

<?php endif; ?>
<?php if (is_string($answers)) : ?>
<p class='comment'><?=$answers?></p>
<?php endif; ?>
</div> 