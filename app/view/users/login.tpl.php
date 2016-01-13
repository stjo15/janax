
<h2><?=$title?></h2>
<?=$form?>

<p><a href='<?=$url = $this->url->create('users/recover');?>'>Did you forget your password?</a></p>
<p><a href='<?=$url = $this->url->create('users/add');?>'>Register new user</a></p>