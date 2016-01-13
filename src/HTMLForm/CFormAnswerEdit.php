<?php

namespace Anax\HTMLForm;

/**
 * Form to edit comment
 *
 */
class CFormAnswerEdit extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    private $id;
    private $redirect;
    private $pagekey;
    private $user;

    /**
     * Constructor
     *
     */
    public function __construct($id, $content, $acronym, $web, $mail, $pagekey, $redirect)
    {
        parent::__construct([], [
            
            'content' => [
                'type'        => 'textarea',
                'label'       => 'Answer',
                'description' => 'Give an answer to the question asked. There is no character limit. Please stay to the topic of the question. If you need the asker to clarify the question, please write a comment instead. Markdown syntax suppported.',
                'value'       =>  $content,
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'submit' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
                'value'     => 'Submit',
            ],
            'reset' => [
                'type'      => 'reset',
                'value'     => 'Reset',
            ],
            
            'delete' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackDelete'],
                'value'     => 'Delete',
            ],
            
        ]);
        
        $this->id = $id;
        $this->redirect = $redirect;
        $this->pagekey = $pagekey;
    }



    /**
     * Customise the check() method.
     *
     * @param callable $callIfSuccess handler to call if function returns true.
     * @param callable $callIfFail    handler to call if function returns true.
     */
    public function check($callIfSuccess = null, $callIfFail = null)
    {
        return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
    }



    /**
     * Callback for submit button.
     *
     */
    public function callbackSubmit()
    {

        $now = date('Y-m-d H:i:s');
        
        $this->user = new \Anax\Users\User();
        $this->user->setDI($this->di);
        $user = $this->user->find($_SESSION['user']->id);
        
        $acronym = $user->getProperties()['acronym'];
        $email = $user->getProperties()['email'];
        $web = $user->getProperties()['web'];
        $title = 'Answer for question #'.$this->pagekey;
        
        $this->answer = new \Anax\Answer\Answer();
        $this->answer->setDI($this->di);
        $saved = $this->answer->save(array('id' => $this->id, 'title' => $title, 'content' => $this->Value('content'), 'mail' => $email, 'acronym' => $acronym, 'web' => $web, 'updated' => $now, 'ip' => $this->di->request->getServer('REMOTE_ADDR'), 'gravatar' => 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '.jpg'));
        
        
        
    //$this->saveInSession = true;
        
        if($saved) 
        {
        return true;
        }
        else return false;
    }
    
    public function callbackDelete()
    {
        $this->answer = new \Anax\Answer\Answer();
        $this->answer->setDI($this->di);
        
        $deleted = $this->answer->delete($this->id);
        
        $this->question = new \Anax\Question\Question();
        $this->question->setDI($this->di);
        $question = $this->question->findQuestion(null,$this->pagekey);
        $answers = $question[0]->getProperties()['answers'];
        $this->question->customUpdate('question', array('answers' => ($answers - 1)), 'id = '.$this->pagekey);
        
        if($deleted) 
        {
            $user = new \Anax\Users\User();
            $user->setDI($this->di);
            $user = $user->find($_SESSION['user']->id);
            $xp = $user->getProperties()['xp'];
            $user->update(array('xp' => ($xp - 4)));
            return true;
        }
            else return false;
        
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {   
         $this->redirectTo($this->redirect);
    }


    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>The form was not filled out correctly.</i></p>");
        
    }
}