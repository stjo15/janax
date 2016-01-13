<?php $controller = isset($controller) ? $controller : 'question'; ?>
<div class='questions'>
<h2><?=$questions[0]->title?></h2>

<?php if (is_array($questions)) : ?>
<?php foreach ($questions as $id => $question) : ?>
<?php $id = (is_object($question)) ? $question->id : $id; ?>
<?php $question = (is_object($question)) ? get_object_vars($question) : $question; ?>

<?php
$content = $this->textFilter->doFilter($question['content'], 'shortcode, markdown');
?>

<div class='comment'>


<div class='comment-content'>
<?=$content?>

<p>
<?php if(isset($_SESSION['user']) && $_SESSION['user']->acronym == $question['acronym']) : ?>
<a href='<?=$this->url->create($controller .'/edit/'.$id)?>'>Edit question</a>
<?php endif; ?>
</p>

<?php 
$tagslugs = explode(",", $question['tagslug']);
$tags = explode(",", $question['tag']);
for($i = 0; $i < count($tags); $i++) {
    echo "<span class='tag-label'><a href='".$this->url->create('question/list/'.$tagslugs[$i])."'>" . $tags[$i] . " </a></span>";
}
?>

</div>
<div class='comment-footer'>
<p><?= "Answers: " . $question['answers'] . " Comments: " . $question['comments']; ?></p>
<p>Written by <a href='<?=$this->url->create('users/id/'.$question['userid'])?>'><?=$question['acronym']?></a> 
<?php $elapsedsec = (time()-strtotime($question['timestamp'])); ?>
<?php if (($elapsedsec) < 60): ?>
<?=round($elapsedsec)?> s ago.
<?php elseif (($elapsedsec/60) < 60): ?>
<?=round($elapsedsec/60)?> minutes ago.
<?php elseif (($elapsedsec/(60*60)) < 24): ?>
<?=round($elapsedsec/(60*60))?> h ago.
<?php elseif (($elapsedsec/(60*60*24)) < 7): ?>
<?=round($elapsedsec/(60*60*24))?> dygn sedan.
<?php elseif (($elapsedsec/(60*60*24)) < 30) : ?>
<?=round($elapsedsec/(60*60*24*7))?> weeks ago.
<?php else : ?>
<?=round($elapsedsec/(60*60*24*30))?> months ago.
<?php endif; ?></p>
<img class='gravatar' src='<?=$question['gravatar']?>?s=40' alt='gravatar'>
<?php if (!empty($question['web'])) : ?>
<?php $prefix = preg_match('/^[www]/', $question['web']) ? 'http://' : '';?>
<a href='<?=$prefix.$question['web']?>' target='_blank'>Website</a>
<?php endif; ?>
</div>
</div>

<?php endforeach; ?>

<?php endif; ?>
<?php if (is_string($questions)) : ?>
<p class='comment'><?=$questions?></p>

<?php endif; ?>


</div> 