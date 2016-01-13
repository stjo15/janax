<?php

namespace Anax\HTMLForm;

/**
 * Form to edit question
 *
 */
class CFormNewsEdit extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    private $id; 
    private $userid;
    private $acronym;
    private $redirect;
    private $tag;
    private $tagslug;
    private $user;

    /**
     * Constructor
     *
     */
    public function __construct($id, $tag, $tagslug, $title, $userid, $author, $content, $image, $imagewidth, $imageheight, $redirect)
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
                'value'       => $author,
                'required'    => true,
            ],
            'title' => [
                'type'        => 'text',
                'label'       => 'News title',
                'description' => 'The header or title that summarizes the article content in one sentence. Maximum 110 characters.',
                'value'       => $title,
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'image' => [
                'type'        => 'text',
                'label'       => 'Image name',
                'placeholder' => 'myimage.png',
                'description' => 'The name of the article image. It should not include a path, but must include the file name .jpg, .png, or .gif. The width should not exceed 696px.',
                'value'       => $image,
                'required'    => false,
            ],
            'image-width' => [
                'type'        => 'range',
                'label'       => 'Image width',
                'max'         => 900,
                'min'         => 696,
                'value'       => $imagewidth,
                'description' => 'The width should be at least 696px but no more than 900px.',
                'required'    => false,
            ],
            'image-height' => [
                'type'        => 'range',
                'label'       => 'Image height',
                'max'         => 696,
                'min'         => 250,
                'value'       => $imageheight,
                'description' => 'The height should not exceed 696px but be at least 250px.',
                'required'    => false,
            ],
            'content' => [
                'type'        => 'textarea',
                'label'       => 'Content',
                'value'       => $content,
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
        
        $this->redirect = $redirect;
        $this->id = $id;
        $this->userid = $userid;
        $this->acronym = $_SESSION['user']->acronym;
        $this->tag = $tag;
        $this->tagslug = $tagslug;
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

        $now = date('c');
        
        $this->user = new \Anax\Users\User();
        $this->user->setDI($this->di);
        $this->user = $this->user->find($this->userid);
        
        $acronym = $this->user->getProperties()['acronym'];
        $email = $this->user->getProperties()['email'];
        $web = $this->user->getProperties()['web'];
        
        $tags = '';
        $tagslugs = '';
        if(isset($_POST['tag'])) {
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
                // Remove 1 from old data
                    $oldtags = explode(',', $this->tagslug);
                    foreach($oldtags as $key => $val) { 
                        $this->tag = new \Anax\News\Newstag();
                        $this->tag->setDI($this->di);
                        $tag = $this->tag->findTag($val);
                        $articles = $tag[0]->getProperties()['articles'];
                        $tagid = $tag[0]->getProperties()['id'];
                        $this->tag->customUpdate('newstag', array('articles' => ($articles - 1)), 'id = '. $tagid);
                    }
                $tags = rtrim($tags, ',');
                $tagslugs = rtrim($tagslugs, ',');
        }
        $tags = empty($tags) ? $this->tag : $tags;
        $tagslugs = empty($tagslugs) ? $this->tagslug : $tagslugs;
        
        $slug = slugify($this->Value('title'));
        $this->news = new \Anax\News\News();
        $this->news->setDI($this->di);
        $saved = $this->news->save(array('id' => $this->id, 'tag' => $tags, 'tagslug' => $tagslugs, 'title' => $this->Value('title'), 'slug' => $slug, 'author' => $this->Value('author'), 'content' => $this->Value('content'), 'image' => $this->Value('image'), 'imagewidth' => $this->Value('image-width'), 'imageheight' => $this->Value('image-height'), 'mail' => $email, 'acronym' => $acronym, 'web' => $web, 'updated' => $now, 'ip' => $this->di->request->getServer('REMOTE_ADDR'), 'gravatar' => 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '.jpg'));
        
    //$this->saveInSession = true;
        
        if($saved) 
        {
        return true;
        }
        else return false;
    }
    
    public function callbackDelete()
    {
        $this->news = new \Anax\News\News();
        $this->news->setDI($this->di);
        
        $deleted = $this->news->delete($this->id);
        
        if($deleted) 
        {
            $this->redirect = 'news';
            // Also delete all comments related to this article
            
            $this->comment = new \Phpmvc\Comment\Comment();
            $this->comment->setDI($this->di);
            $comments = $this->comment->findAll($this->id);
            foreach($comments as $id => $comment) {
                $this->comment->delete($comment->id);
            }
            
            // Update User reputation
            $user = new \Anax\Users\User();
            $user->setDI($this->di);
            $user = $user->find($_SESSION['user']->id);
            $xp = $user->getProperties()['xp'];
            $user->update(array('xp' => ($xp - 15)));
            
            // Update number of tags
            $oldtags = explode(',', $this->tagslug);
            $this->tag = new \Anax\News\Tag();
            $this->tag->setDI($this->di);
            foreach($oldtags as $key => $val) { 
                $tag = $this->tag->findTag($val);
                $news = $tag[0]->getProperties()['news'];
                $tagid = $tag[0]->getProperties()['id'];
                $this->tag->customUpdate('newstag', array('news' => ($news - 1)), 'id = '. $tagid);
            }
                    
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
        $this->AddOutput("<p><i>Form was not filled out correctly.</i></p>");
        
    }
}