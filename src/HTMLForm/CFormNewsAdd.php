<?php

namespace Anax\HTMLForm;

/**
 * Form to add question
 *
 */
class CFormNewsAdd extends \Mos\HTMLForm\CForm
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
            'author' => [
                'type'        => 'text',
                'label'       => 'Author',
                'description' => 'The full name of the author. It will be visible above the article.',
                'required'    => true,
            ],
            'title' => [
                'type'        => 'text',
                'label'       => 'Header',
                'description' => 'The header or title that summarizes the article content in one sentence. Maximum 110 characters.',
                'required'    => true,
            ],
            'image' => [
                'type'        => 'text',
                'label'       => 'Image name',
                'placeholder' => 'myimage.png',
                'description' => 'The name of the article image. It should not include a path, but must include the file name .jpg, .png, or .gif. The width should be at least 696px.',
                'required'    => false,
            ],
            'image-width' => [
                'type'        => 'range',
                'label'       => 'Image width',
                'max'         => 900,
                'min'         => 696,
                'value'       => 696,
                'description' => 'The width should be at least 696px but no more than 900px.',
                'required'    => false,
            ],
            'image-height' => [
                'type'        => 'range',
                'label'       => 'Image height',
                'max'         => 696,
                'min'         => 250,
                'value'       => 389,
                'description' => 'The height should not exceed 696px but be at least 250px.',
                'required'    => false,
            ],
            'content' => [
                'type'        => 'textarea',
                'label'       => 'Content',
                'description' => 'The main content. Support for Markdown syntax.',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'submit' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
                'value'     => 'Publish',
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
        
        $now = date('c');
        
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
            $this->tag = new \Anax\News\Newstag();
            $this->tag->setDI($this->di);
            $tag = $this->tag->findTag($slug);
            $articles = $tag[0]->getProperties()['articles'];
            $tagid = $tag[0]->getProperties()['id'];
            $this->tag->customUpdate('newstag', array('articles' => ($articles + 1)), 'id = '. $tagid);
        }
        $tags = rtrim($tags, ',');
        $tagslugs = rtrim($tagslugs, ',');
        
        $slug = slugify($this->Value('title'));
        $this->newarticle = new \Anax\News\News();
        $this->newarticle->setDI($this->di);
        $saved = $this->newarticle->save(array('tag' => $tags, 'tagslug' => $tagslugs, 'title' => $this->Value('title'), 'slug' => $slug, 'author' => $this->Value('author'), 'content' => $this->Value('content'), 'image' => $this->Value('image'), 'imagewidth' => $this->Value('image-width'), 'imageheight' => $this->Value('image-height'), 'mail' => $email, 'acronym' => $acronym, 'userid' => $userid, 'web' => $web, 'timestamp' => $now, 'updated' => $now, 'ip' => $this->di->request->getServer('REMOTE_ADDR'), 'gravatar' => 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '.jpg'));
        
       // $this->saveInSession = true;
        
        if($saved) 
        {
            // Give xp to the user
            $user->update(array('xp' => ($xp + 10)));
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