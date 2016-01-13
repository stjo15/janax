<?php $controller = isset($controller) ? $controller : 'news'; ?>
<div class='news'>
<?php
$xmlfile = ANAX_APP_PATH . 'rss/' . $tagslug . "_rss.xml";
if(file_exists($xmlfile)) {
    echo "<a href=".$this->url->create('rss/view/'.$tagslug)." title='RSS'><i class='fa fa-rss rss'></i></a>";
}
?>

<h2><?=$title?></h2>

<?php if (is_array($news)) : ?>
<?php foreach ($news as $id => $article) : ?>
<?php $id = (is_object($article)) ? $article->id : $id; ?>
<?php $article = (is_object($article)) ? get_object_vars($article) : $article; ?>

<div class='comment'>

<h2>
<?php if(isset($_SESSION['user']) && $_SESSION['user']->acronym == $article['acronym']) : ?>

<?php endif; ?>

<?php
$image = $article['image'] != '' ? $this->url->create('img/news/thumbs/thumb-'.$article['image']) : $this->url->create('img/news/thumbs/thumb-janax-no-image.png');
?>
<div class='news-thumb'>
<img src='<?=$image?>' alt='Janax news image'>
</div>

<?=$article['title']?>
</h2>

<?php
// End the excerpt with a complete word.
    $lastSpacePosition = strrpos($article['excerpt'], ' ');
    $excerpt = substr($article['excerpt'], 0, $lastSpacePosition) . ' ... <a href='.$this->url->create($controller .'/view/'.$article['id'].'/'.$article['slug']).'>Read more »</a>';
?>
<p><?= $excerpt; ?></p>

<?php 
$tagslugs = explode(",", $article['tagslug']);
$tags = explode(",", $article['tag']);
for($i = 0; $i < count($tags); $i++) {
    echo "<span class='tag-label'><a href='".$this->url->create('news/list/'.$tagslugs[$i])."'>" . $tags[$i] . " </a></span>";
}
?>

<div class='comment-footer'>
<p><?= "Comments: " . $article['comments']; ?></p>
<p>By <?=$article['author'];?> <?=$article['timestamp'];?></p>
</div>
</div>

<?php endforeach; ?>

<?php endif; ?>
<?php if (is_string($news)) : ?>
<p class='comment'><?=$news?></p>
<?php endif; ?>
</div> 