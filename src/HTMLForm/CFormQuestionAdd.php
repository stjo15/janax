<?php

namespace Anax\HTMLForm;

/**
 * Form to add question
 *
 */
class CFormQuestionAdd extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    private $redirect;
    private $id;
    private $acronym;

    /**
     * Constructor
     *
     */
    public function __construct($redirect)
    {
        parent::__construct([], [
            
            'tag' => array(
                'type'        => 'checkbox-multiple',
                'label'       => 'Tags',
                'required'    => true,
                'values'     => array(
                        'News',
                        'Technology',
                        'Economy',
                        'Purchase',
                        'Design',
                        'Performance',
                        'Maintenance',
                        'Accessories',
                        'General'
                        ),
            ),
            'title' => [
                'type'        => 'text',
                'label'       => 'Question title',
                'description' => "Please write a short (max 200 characters) title to your question. The title should be the question itself. If it has a question mark at the end without looking strange, it's a proper title.",
                'required'    => true,
                'validation'  => array('not_empty', 'custom_test' => array(
                    'message' => 'You entered more than 200 characters.', 
                    'test' => function ($value) {
                        if(strlen($value) > 200) {
                            return false;
                        };
                    }),
                ),
            ],
            'content' => [
                'type'        => 'textarea',
                'label'       => 'Description',
                'description' => "Describe the details of your question. There is no character limit. Please stay to the topic of your question. Markdown syntax suppported.",
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'submit' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
                'value'     => 'Save',
            ],
            'reset' => [
                'type'      => 'reset',
                'value'     => 'Reset',
            ],
            
        ]);
        
        $this->redirect = $redirect;
        $this->id = $_SESSION['user']->id;
        $this->acronym = $_SESSION['user']->acronym;
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
        if(empty($_POST['tag'])) {
            $this->AddOutput("<p><i>You need to choose at least one tag!</i></p>");
            return false;
        }
        
        $now = date('Y-m-d H:i:s');
        
        $this->user = new \Anax\Users\User();
        $this->user->setDI($this->di);
        $user = $this->user->find($this->id);
        
        $acronym = $user->getProperties()['acronym'];
        $email = $user->getProperties()['email'];
        $web = $user->getProperties()['web'];
        $userid = $user->getProperties()['id'];
        $xp = $user->getProperties()['xp'];
        
        $tags = '';
        $tagslugs = '';
        foreach($_POST['tag'] as $key => $val) {
            $slug = slugify($val);
            $tagslugs .= $slug . ',';
            $tags .= $val . ',';
            // Update total number of tags
            $this->tag = new \Anax\Question\Tag();
            $this->tag->setDI($this->di);
            $tag = $this->tag->findTag($slug);
            $questions = $tag[0]->getProperties()['questions'];
            $tagid = $tag[0]->getProperties()['id'];
            $this->tag->customUpdate('tag', array('questions' => ($questions + 1)), 'id = '. $tagid);
        }
        $tags = rtrim($tags, ',');
        $tagslugs = rtrim($tagslugs, ',');
        
        $slug = slugify($this->Value('title'));
        
        $this->newquestion = new \Anax\Question\Question();
        $this->newquestion->setDI($this->di);
        $saved = $this->newquestion->save(array('tag' => $tags, 'tagslug' => $tagslugs, 'title' => $this->Value('title'), 'slug' => $slug, 'content' => $this->Value('content'), 'mail' => $email, 'acronym' => $acronym, 'userid' => $userid, 'web' => $web, 'timestamp' => $now, 'updated' => $now, 'ip' => $this->di->request->getServer('REMOTE_ADDR'), 'gravatar' => 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '.jpg'));
        
       // $this->saveInSession = true;
        
        if($saved) 
        {
            // Give xp to the user
            $user->update(array('xp' => ($xp + 3)));
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
         /*$xmlfile = ANAX_APP_PATH . 'rss/' . $this->id . "_rss.xml";
         if(file_exists($xmlfile)) {
             $rss = new \Anax\Rss\RssFeed();
             $rss->setDI($this->di);
             $xml = $rss->getFeed($this->tag);
             $fh = fopen($xmlfile, 'w') or die("can't open file");
             fwrite($fh, $xml);
             fclose($fh);
         }*/
        
         $this->redirectTo($this->redirect);
    }


    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Form was not filled out correctly.</i></p>");
    }
}