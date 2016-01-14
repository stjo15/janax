<?php

namespace Anax\News;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class NewsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    private $errormessage;
    
    /**
    * Initialize the controller.
    *
    * @return void
    */
    public function initialize()
    {
        $this->news = new \Anax\News\News();
        $this->news->setDI($this->di);
        
        $this->users = new \Anax\Users\User();
        $this->users->setDI($this->di);
    }
    
    /**
     * Add an article with CForm.
     *
     * @return void
     */
    public function addAction($redirect='news')
    {
        $undourl = '<p><a href="'.$this->di->get('url')->create($redirect).'">Cancel</p>';
        
        $formundo = new \Anax\HTMLForm\CFormCommentUndo($redirect);
        $formundo->setDI($this->di);
        $formundo->check();
        $undourl = $formundo->getHTML();
        
        
        $url = $this->url->create('users/login');
        
        if(isset($_SESSION['user'])) {
            
            $form = new \Anax\HTMLForm\CFormNewsAdd($redirect);
            $form->setDI($this->di);
            $form->check();
            
            $this->theme->setTitle('Write a news post');
            $this->di->views->add('news/addform', [
                'title' => "Write a news post",
                'content' => $form->getHTML().$undourl,
            ]);
        } else {
            $this->di->views->add('default/link', [
                'anchor' => "Log in to write a news post",
                'url' => $url, 
            ]);
        }
    }
    
    
    /**
     * View all news.
     *
     * @return void
     */
    public function listAction($tagslug=null, $orderby='timestamp DESC', $redirect=null, $header='News by tag', $limit=null)
    {
        $redirect = 'news/list';
        
        $controller = 'news';
        
        if(!isset($limit)) {
            $all = $this->news->findAllWithExcerpt($tagslug, $orderby, 250);
        } else {
            $all = $this->news->findAllLimit($tagslug, $orderby, $limit);
        }
        
        $title = isset($tagslug) ? 'News - ' . ucfirst($tagslug) : 'News';
        $this->theme->setTitle($title);
        
        $this->views->add('news/news', [
            'news' => $all,
            'redirect'  => $redirect,
            'controller' => $controller,
            'tagslug' => $tagslug,
            'title' => $header,
        ]);
        // Set the conditions for writing a news article here, default is all registered users
        if(isset($_SESSION['user'])) {
            if(($_SESSION['user']->xp >= 0) || ($_SESSION['user']->acronym == 'admin')) {
            $this->views->add('news/sidebar', [], 'rsidebar');
            }
        }
    }
    
    /**
     * View an article with id.
     *
     * @return void
     */
    public function viewAction($id, $redirect=null)
    {
        $redirect = 'news/view/' . $id;
        $controller = 'news';
        
        if (is_numeric($id)) {
            $news = $this->news->findNews(null,$id);
        } else {
            $news = $this->news->findNews($id,null);
        }
        
        $this->theme->setTitle($news[0]->title);
        
        $this->views->add('news/article', [
            'news' => $news,
            'redirect'  => $redirect,
            'controller' => $controller,
        ]);
        
        $this->dispatcher->forward([
             'controller' => 'comment',
             'action'     => 'view',
             'params'     => [$news[0]->slug, 'news/view/'.$id, 'triptych-1'],
        ]);
        
        $commenturl = $this->url->create('comment/add/'.$news[0]->slug.'/news/');
        $this->di->views->add('default/link', [
                'anchor' => "Comment this article",
                'url' => $commenturl, 
            ]);
        $comment_number = $news[0]->comments;
        $this->di->views->add('default/button', [
                'anchor' => "Show comments",
                'id' => 'show-comments', 
                'value' => $comment_number, 
            ]);
       
    }
    
    /**
    *
    * Edit a question
    *
    * @param $id selects the question to edit.
    *
    */      
    public function editAction($id, $slug, $redirect='')
    {
        $redirect = 'news/view/' . $id . '/' . $slug;
        //test
        $formundo = new \Anax\HTMLForm\CFormCommentUndo($redirect);
        $formundo->setDI($this->di);
        $formundo->check();
        $undourl = $formundo->getHTML();
        
        $controller = 'news';
        
        $news = $this->news->findNews(null, $id);
        $news = (is_object($news[0])) ? get_object_vars($news[0]) : $news;
        /*if($_SESSION['user']->acronym != $news['acronym']) {
                header('Location: ' . $this->url->create('users/message/wronguser'));
                die("You don't have permission to enter this page!");
            }*/
        
        $form = new \Anax\HTMLForm\CFormNewsEdit($id, $news['tag'], $news['tagslug'], $news['title'], $news['userid'], $news['author'], $news['content'], $news['image'], $news['imagewidth'], $news['imageheight'], $redirect);
        $form->setDI($this->di);
        $form->check();
        
        $this->theme->setTitle("Edit news");
        
        $this->di->views->add('default/page', [
        'title' => "Edit news",
        'content' => '<h4>Article #'.$id.'</h4>'.$form->getHTML().$undourl, 
        ], 'main');
        
    }
    
    /**
     * View all tags.
     *
     * @return void
     */
    public function taglistAction($orderby=null, $title=null, $limit=null)
    {
        $this->tag = new \Anax\News\Tag();
        $this->tag->setDI($this->di);
        
        $tags = $this->tag->findAll($orderby, $limit);
        
        $this->views->add('news/taglist', [
            'tags' => $tags,
            'title' => $title,
        ], 'rsidebar');
        
    }
    
    
}