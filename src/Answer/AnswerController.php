<?php

namespace Anax\Answer;

/**
 * To attach answers-flow to a page or some content.
 *
 */
class AnswerController implements \Anax\DI\IInjectionAware
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
        $this->users = new \Anax\Users\User();
        $this->users->setDI($this->di);
        
    }
    
    /**
     * Add an answer.
     *
     * @return void
     */
    public function addAction($pagekey = null, $redirect=null)
    {
        $redirect = 'question/view/'.$pagekey;
        $undourl = '<p><a href="'.$this->di->get('url')->create($redirect).'">Cancel</p>';
        
        $formundo = new \Anax\HTMLForm\CFormCommentUndo($redirect);
        $formundo->setDI($this->di);
        $formundo->check();
        $undourl = $formundo->getHTML();
        
        $url = $this->url->create('users/login');
        $baseurl = $this->url->create('');
        
        if(isset($_SESSION['user'])) {
            
            $answerform = new \Anax\HTMLForm\CFormAnswerAdd($pagekey, $redirect, $baseurl);
            $answerform->setDI($this->di);
            $answerform->check();
            
            $this->theme->setTitle("Write an answer");
            $this->di->views->add('answer/addform', [
                'title' => "Write an answer",
                'content' => $answerform->getHTML().$undourl,
            ], 'main');
        } else {
            $this->di->views->add('default/link', [
                'anchor' => "Sign in to answer",
                'url' => $url, 
            ], 'main');
        }
    }
    
    /**
     * View all answers.
     *
     * @return void
     */
    public function viewAction($pagekey = null, $redirect=null)
    {
        $answers = new \Anax\Answer\Answer();
        $controller = 'answer';
        $answers->setDI($this->di);

        $all = $answers->findAll($pagekey);
        
        $this->views->add('answer/answers', [
                'answers' => $all,
                'pagekey'   => $pagekey,
                'redirect'  => $redirect,
                'controller' => $controller,
                ], 'triptych-1');
        
    }
    
    
    
    /**
    *
    * Edit an answer
    *
    * @param string $pagekey selects the array with the page-id.
    * @param $id selects the answer to edit.
    *
    */      
    public function editAction($id, $redirect='')
    {
        //$redirect = '/view/'.$redirect;
        
        $formundo = new \Anax\HTMLForm\CFormCommentUndo($redirect);
        $formundo->setDI($this->di);
        $formundo->check();
        $undourl = $formundo->getHTML();
        
        $answers = new \Anax\Answer\Answer();
        $controller = 'answer';
        $answers->setDI($this->di);
        
        $answer = $answers->findAnswer(null, $id);
        $answer = (is_object($answer[0])) ? get_object_vars($answer[0]) : $answer;
        
        if($_SESSION['user']->acronym != ($answer['acronym'] || 'admin')) {
                header('Location: ' . $this->url->create('users/message/wronguser'));
                die("You don't have permission to enter this page!");
            }
        
        $form = new \Anax\HTMLForm\CFormAnswerEdit($id, $answer['content'], $answer['acronym'], $answer['web'], $answer['mail'], $answer['pagekey'], $redirect);
        $form->setDI($this->di);
        $form->check();
        
        $this->theme->setTitle("Edit answer");
        
        $this->di->views->add('default/page', [
        'title' => "Edit answer",
        'content' => '<h4>Answer #'.$id.'</h4>'.$form->getHTML().$undourl, 
        ], 'main');
        
    }
    
}