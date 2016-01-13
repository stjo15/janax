<?php

namespace Anax\Question;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class QuestionController implements \Anax\DI\IInjectionAware
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
        $this->question = new \Anax\Question\Question();
        $this->question->setDI($this->di);
        
        $this->users = new \Anax\Users\User();
        $this->users->setDI($this->di);
    }
    
    /**
     * Add a question with CForm.
     *
     * @return void
     */
    public function addAction($redirect='question')
    {
        $undourl = '<p><a href="'.$this->di->get('url')->create($redirect).'">Cancel</p>';
        
        $formundo = new \Anax\HTMLForm\CFormCommentUndo($redirect);
        $formundo->setDI($this->di);
        $formundo->check();
        $undourl = $formundo->getHTML();
        
        
        $url = $this->url->create('users/login');
        
        if(isset($_SESSION['user'])) {
            
            $form = new \Anax\HTMLForm\CFormQuestionAdd($redirect);
            $form->setDI($this->di);
            $form->check();
            
            $this->theme->setTitle('Ask a question');
            $this->di->views->add('question/addform', [
                'title' => "Ask a question",
                'content' => $form->getHTML().$undourl,
            ]);
        } else {
            $this->di->views->add('default/link', [
                'anchor' => "Log in to ask a question",
                'url' => $url, 
            ]);
        }
    }
    
    
    /**
     * View all questions.
     *
     * @return void
     */
    public function listAction($tagslug=null, $orderby='timestamp DESC', $redirect=null, $header='Question by tag', $limit=null)
    {
        $redirect = '';
        
        $controller = 'question';
        
        if(isset($limit)) {
            $all = $this->question->findAllLimit($tagslug, $orderby, $limit);
        } else {
            $all = $this->question->findAll($tagslug, $orderby);
        }
        
        $title = isset($tagslug) ? ucfirst($tagslug) : 'Questions';
        $this->theme->setTitle($title);
        
        $this->views->add('question/questions', [
            'questions' => $all,
            'redirect'  => $redirect,
            'controller' => $controller,
            'tagslug' => $tagslug,
            'title' => $header,
        ]);
        
        $this->views->add('question/sidebar', [], 'rsidebar');
        
    }
    
    /**
     * View a question with id.
     *
     * @return void
     */
    public function viewAction($id, $redirect=null)
    {
        $redirect = 'question/view/' . $id;
        $controller = 'question';
        
        $question = $this->question->findQuestion(null,$id);
        
        $this->theme->setTitle($question[0]->title);
        
        $this->views->add('question/question', [
            'questions' => $question,
            'redirect'  => $redirect,
            'controller' => $controller,
        ]);
        
        $this->dispatcher->forward([
             'controller' => 'answer',
             'action'     => 'view',
             'params'     => [$id, 'question/view/'.$id],
        ]);
        
        $answerurl = $this->url->create('answer/add/'.$id);
        $this->di->views->add('default/link', [
                'anchor' => "Answer the question",
                'url' => $answerurl, 
            ]);
        
        $commenturl = $this->url->create('comment/add/'.$id.'/question/');
        $this->di->views->add('default/link', [
                'anchor' => "Comment the question",
                'url' => $commenturl, 
            ]);
        
        $this->dispatcher->forward([
             'controller' => 'comment',
             'action'     => 'view',
             'params'     => [$id, 'question/view/'.$id, 'triptych-2'],
        ]);
        
        $answer_number = $question[0]->answers;
        $this->di->views->add('default/button', [
                'anchor' => "Show answers",
                'id' => 'show-answers', 
                'value' => $answer_number, 
            ]);
        
        $comment_number = $question[0]->comments;
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
    public function editAction($id, $redirect='')
    {
        $redirect = 'question/view/' . $id;
        
        $formundo = new \Anax\HTMLForm\CFormCommentUndo($redirect);
        $formundo->setDI($this->di);
        $formundo->check();
        $undourl = $formundo->getHTML();
        
        $controller = 'question';
        
        $question = $this->question->findQuestion(null, $id);
        $question = (is_object($question[0])) ? get_object_vars($question[0]) : $question;
        
        if($_SESSION['user']->acronym != $question['acronym']) {
                header('Location: ' . $this->url->create('users/message/wronguser'));
                die("You don't have permission to enter this page!");
            }
        
        $form = new \Anax\HTMLForm\CFormQuestionEdit($id, $question['tag'], $question['tagslug'], $question['title'], $question['userid'], $question['content'], $redirect);
        $form->setDI($this->di);
        $form->check();
        
        $this->theme->setTitle("Edit question");
        
        $this->di->views->add('default/page', [
        'title' => "Edit question",
        'content' => '<h4>Question #'.$id.'</h4>'.$form->getHTML().$undourl, 
        ], 'main');
        
    }
    
    /**
     * View all tags in page.
     *
     * @return void
     */
    public function listTagsAction($orderby='name ASC', $limit=100)
    {
        $this->tag = new \Anax\Question\Tag();
        $this->tag->setDI($this->di);
        
        $tags = $this->tag->findAll($orderby, $limit);
        $title = 'All tags';
        
        $this->theme->setTitle("All tags");
        
        $this->views->add('question/list-all-tags', [
            'tags' => $tags,
            'title' => $title,
        ], 'main');
        
    }
    
    /**
     * View all tags.
     *
     * @return void
     */
    public function taglistAction($orderby=null, $title=null, $limit=null, $region)
    {
        $this->tag = new \Anax\Question\Tag();
        $this->tag->setDI($this->di);
        
        $tags = $this->tag->findAll($orderby, $limit);
        
        $this->views->add('question/taglist', [
            'tags' => $tags,
            'title' => $title,
        ], $region);
        
    }
    
    
}