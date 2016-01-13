<?php

namespace Anax\HTMLForm;

/**
 * Form to add answer
 *
 */
class CFormAnswerAdd extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    private $pagekey;
    private $redirect;
    private $id;
    private $acronym;
    private $url;

    /**
     * Constructor
     *
     */
    public function __construct($pagekey, $redirect, $url)
    {
        parent::__construct([], [
            
            'content' => [
                'type'        => 'textarea',
                'label'       => 'Answer',
                'description' => 'Give an answer to the question asked. There is no character limit. Please stay to the topic of the question. If you need the asker to clarify the question, please write a comment instead. Markdown syntax suppported.',
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
            
        ]);
        
        $this->pagekey = $pagekey;
        $this->redirect = $redirect;
        $this->id = $_SESSION['user']->id;
        $this->acronym = $_SESSION['user']->acronym;
        $this->url = $url;
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
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {
        
        $now = date('Y-m-d H:i:s');
        
        $this->user = new \Anax\Users\User();
        $this->user->setDI($this->di);
        $user = $this->user->find($this->id);
        
        $acronym = $user->getProperties()['acronym'];
        $email = $user->getProperties()['email'];
        $web = $user->getProperties()['web'];
        $userid = $user->getProperties()['id'];
        $xp = $user->getProperties()['xp'];
        
        $this->question = new \Anax\Question\Question();
        $this->question->setDI($this->di);
        $question = $this->question->findQuestion(null,$this->pagekey);
        $answers = $question[0]->getProperties()['answers'];
        $askermail = $question[0]->getProperties()['mail'];
        $askerid = $question[0]->getProperties()['userid'];
        $questiontitle = $question[0]->getProperties()['title'];
        $this->question->customUpdate('question', array('answers' => ($answers + 1)), 'id = '.$this->pagekey);
        $title = 'Answer for question #'.$this->pagekey;
        
        $this->newanswer = new \Anax\Answer\Answer();
        $this->newanswer->setDI($this->di);
        $saved = $this->newanswer->save(array('title' => $title, 'content' => $this->Value('content'), 'mail' => $email, 'acronym' => $acronym, 'userid' => $userid, 'web' => $web, 'pagekey' => $this->pagekey, 'timestamp' => $now, 'updated' => $now, 'ip' => $this->di->request->getServer('REMOTE_ADDR'), 'gravatar' => 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '.jpg'));
        
       // $this->saveInSession = true;
        
        if($saved) 
        {
            $user->update(array('xp' => ($xp + 3)));
            
            // Send mail to the asker to notify about the answer
            $this->asker = new \Anax\Users\User();
            $this->asker->setDI($this->di);
            $asker = $this->asker->find($askerid);
            
            $to = $askermail;
            $from = ADMIN_MAIL;
            $subject="Your question was answered!";
            $link = $this->url . '/question/view/' . $this->pagekey;
            $body = "Someone answered your question with title '" . $questiontitle . "'! Follow this link to view the question: " . $link;
            $headers = "From: " . strip_tags($from) . "\r\n";
            $headers .= "Svara till: ". strip_tags($from) . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            mail($to,$subject,$body,$headers);
            
            return true;
        }
            else return false;
    }

     /**
     * Callback reset
     *
     */
    public function callbackReset()
    {
         $this->redirectTo($this->redirect);
    }


    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        $this->AddOutput("<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it</i></p>");
        return false;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {   
         $xmlfile = ANAX_APP_PATH . 'rss/' . $this->pagekey . "_rss.xml";
         if(file_exists($xmlfile)) {
             $rss = new \Anax\Rss\RssFeed();
             $rss->setDI($this->di);
             $xml = $rss->getFeed($this->pagekey);
             $fh = fopen($xmlfile, 'w') or die("can't open file");
             fwrite($fh, $xml);
             fclose($fh);
         }
        
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