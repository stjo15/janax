<?php $controller = isset($controller) ? $controller : 'news'; ?>
<div class='news-article'>

<div itemscope itemtype="http://schema.org/NewsArticle">

<?php if (is_array($news)) : ?>
<?php foreach ($news as $id => $article) : ?>
<?php $id = (is_object($article)) ? $article->id : $id; ?>
<?php $article = (is_object($article)) ? get_object_vars($article) : $article; ?>

<?php
$content = $this->textFilter->doFilter($article['content'], 'shortcode, markdown');
?>

<!-- Headline -->
<meta itemscope itemprop="mainEntityOfPage"  itemType="https://schema.org/WebPage" itemid="https://google.com/article"/>
<h2 class='article-header' itemprop="headline">
<?php if(isset($_SESSION['user']) && $_SESSION['user']->acronym == $article['acronym']) : ?>
<a href='<?=$this->url->create($controller.'/edit/'.$id.'/'.$article['slug'])?>'>#<?=$id?></a>

<?php endif; ?>

<?=$article['title']?>
</h2>

<!-- Author -->
<p class='tip' itemprop="author" itemscope itemtype="https://schema.org/Person">
By <span itemprop="name"><?=$article['author']?></span>

<!-- Publish date -->

<span><?=$article['timestamp']?></span>
</p>

<!-- Image -->
<?php 
$image = $article['image'] != '' ? $this->url->create('img/news/'.$article['image']) : $this->url->create('img/news/janax-no-image.png');
$imagewidth = $article['imagewidth'];
$imageheight = $article['imageheight'];
?>
<div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
    <img src='<?=$image?>' alt='Janax news image'>
    <meta itemprop="url" content="<?=$image?>">
    <meta itemprop="width" content="<?=$imagewidth?>">
    <meta itemprop="height" content="<?=$imageheight?>">
</div>

<!-- Content -->
<div class='article-content'>
<?=$content?>
</div>

<!-- Tags -->
<div class='article-footer'>
<p>
<?php 
$tagslugs = explode(",", $article['tagslug']);
$tags = explode(",", $article['tag']);
for($i = 0; $i < count($tags); $i++) {
    echo "<span class='tag-label'><a href='".$this->url->create($controller.'/list/'.$tagslugs[$i])."'>" . $tags[$i] . " </a></span>";
}
?>
</p>

<!-- Publisher -->
<?php $logo = $this->url->create('img/logo-small.png'); ?>
<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
    
    <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
    <p class='tip'>Published by: 
      <img src='<?=$logo?>' alt='Janax Forum Framework'>
      <meta itemprop="url" content="<?=$logo?>">
      <meta itemprop="width" content="155">
      <meta itemprop="height" content="17">
    </p>
    </div>
    <meta itemprop="name" content="Janax Forum Framework">
</div>
</div>

<meta itemprop="datePublished" content="<?=$article['timestamp']?>"/>
<meta itemprop="dateModified" content="<?=$article['updated']?>"/>

<?php endforeach; ?>

<?php endif; ?>
<?php if (is_string($news)) : ?>
<p class='comment'><?=$news?></p>
<?php endif; ?>

</div>
</div> 