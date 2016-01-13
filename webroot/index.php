<?php 
/**
 * This is a Anax pagecontroller.
 *
 */

// Get environment & autoloader and the $app-object.
require __DIR__.'/config_with_app.php'; 

// Read the config files for this theme
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->theme->configure(ANAX_APP_PATH . 'config/theme_me.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_janax.php');

// Add custom stylesheets for this app

// Add routers for the pages

$app->router->add('', function() use ($app) {
    
    $app->views->add('welcome/welcome', [], 'flash');
    
    $app->dispatcher->forward([
        'controller' => 'question',
        'action'     => 'list',
        'params'     => [null, 'timestamp DESC', 'question', 'The latest questions', 10],
    ], 'main');
    
    $app->dispatcher->forward([
        'controller' => 'question',
        'action'     => 'taglist',
        'params'     => ['questions DESC', 'The hottest tags', 5, 'featured-1'],
    ]);
    
    $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'userlist',
        'params'     => ['xp DESC', 'Most active users', 5, 'userlist', 'featured-2'],
    ]);
    
    $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'userlist',
        'params'     => ['bestlap ASC', 'Best laptimes', 5, 'laptimelist', 'featured-3'],
    ]);
    
    $app->theme->setTitle("Home");
});

$app->router->add('question', function() use ($app) {
    $app->theme->setTitle("Questions");
    
    $app->dispatcher->forward([
        'controller' => 'question',
        'action'     => 'list',
        'params'     => [null, 'timestamp DESC', 'question', 'All questions'],
    ]);
    
});

$app->router->add('news', function() use ($app) {
    $app->theme->setTitle("News");
    
    $app->dispatcher->forward([
        'controller' => 'news',
        'action'     => 'list',
        'params'     => [null, 'timestamp DESC', 'news', 'All news'],
    ]);
    
});

$app->router->add('answer', function() use ($app) {
    $app->theme->setTitle("Answers");
    
    $app->dispatcher->forward([
        'controller' => 'answer',
        'action'     => 'view',
        'params'     => [null, 'timestamp DESC', 'answer'],
    ]);
    
});

$app->router->add('about', function() use ($app) {
    $app->theme->setTitle("About Janax");
    
    $content = $app->fileContent->get('about.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    
    $byline  = $app->fileContent->get('byline.md');
    $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
    
    $app->views->add('me/page', [
        'content' => $content,
        'byline' => null,
        ], 'main');
});

$app->router->add('privacy', function() use ($app) {
    $app->theme->setTitle("Privacy Policy");
    
    $content = $app->fileContent->get('privacy.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    
    $byline  = $app->fileContent->get('byline.md');
    $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
    
    $app->views->add('me/page', [
        'content' => $content,
        'byline' => null,
        ], 'main');
});

$app->router->add('documentation', function() use ($app) {
    $app->theme->setTitle("Documentation");
    
    $content = $app->fileContent->get('documentation.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    
    $byline  = $app->fileContent->get('byline.md');
    $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
    
    $app->views->add('me/page', [
        'content' => $content,
        'byline' => null,
        ], 'main');
});

$app->router->add('how-to-use', function() use ($app) {
    $app->theme->setTitle("How To Use");
    
    $content = $app->fileContent->get('how-to-use.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    
    $byline  = $app->fileContent->get('byline.md');
    $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
    
    $app->views->add('me/page', [
        'content' => $content,
        'byline' => null,
        ], 'main');
});

/*
$app->router->add('source', function() use ($app) {
    $app->theme->addStylesheet('css/source.css');
    $app->theme->setTitle("Källkod");
    
    $source = new \Me\Source\CSource([
        'secure_dir' => '..', 
        'base_dir' => '..', 
        'add_ignore' => ['.htaccess'],
    ]);
    
    $app->views->add('me/source', [
        'content' => $source->View(),
    ]);
    
});
*/

$app->router->add('comment', function() use ($app) {
    
    $app->theme->setTitle("Comments");

    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'view',
        'params'     => [],
    ]);

});

$app->router->add('users', function() use ($app) {
    
    $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'check',
    ]);
     
});

$app->router->add('rss', function() use ($app) {
        
    $app->theme->setTitle("RSS-flöde");
    
    $app->dispatcher->forward([
        'controller' => 'rss',
        'action'     => 'list',
    ]);
     
});

$app->router->add('free-racing-game', function() use ($app) {
        
    $app->theme->setTitle("Janax Game");
    
    $app->views->add('game/racing', [
        // 'bestlap' => Bestlap from database,
        ], 'flash');
});

// Handle all routes.

$app->router->handle();

// Render the response using theme engine.

$app->theme->render();
