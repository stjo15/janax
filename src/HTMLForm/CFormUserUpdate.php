<?php

namespace Anax\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormUserUpdate extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    private $user;
    private $id;
    private $acronym; 
    private $password; 
    private $activedate;
    
    /**
     * Constructor
     *
     */
    public function __construct($id=null, $acronym='',$presentation='',$email='', $web='', $password='', $activedate=null)
    {
    $activecheck = ($activedate == null) ? false : true;
        
        parent::__construct([], [
            
            'presentation' => [
                'type'        => 'textarea',
                'label'       => 'Presentation',
                'description' => 'The name which will be displayed on your public profile. The name must be unique and contain maximum 20 characters.',
                'required'    => false,
                'validation'  => [],
                'value'       => $presentation,
            ],
            'email' => [
                'type'        => 'text',
                'label'       => 'E-mail',
                'placeholder' => 'email@example.com',
                'description' => 'Make sure that you enter the correct e-mail address. It is needed in case you need to restore your password and also for other important messages. Your e-mail address will not be displayed publicly.',
                'required'    => true,
                'value'       => $email,
                'validation'  => array('not_empty', 'email_adress', 'custom_test' => array(
                    'message' => 'This E-mail adress is already registered.', 
                    'test' => function ($value) {
                        if($_SESSION['user']->email == $value) {
                            return true;
                        } else if($email != $value){
                            $this->user = new \Anax\Users\User();
                            $this->user->setDI($this->di);
                            $users = $this->user->findAll();
                            foreach ($users as $user) {
                                $otheremail = $user->email;
                                if($otheremail == $value) {
                                    return false;
                                }
                            }
                        }
                    }),
                ),
            ],
            
            'email2' => [
                'type'        => 'text',
                'label'       => 'Repeat E-mail adress',
                'placeholder' => 'email@example.com',
                'required'    => true,
                'value'       => $email,
                'validation'  => ['not_empty', 'email_adress', 'match' =>
                    'email'],
            ],
            
            'web' => [
                'type'        => 'text',
                'label'       => 'Website',
                'placeholder' => 'http://www.example.com',
                'description' => 'Tip! You can promote a website you like on your public profile. However, links to sexual, violent or illegal content will not be accepted! The URL character limit is 200.',
                'validation'  => ['web_adress'],
                'value'  => $web,
            ],  
            
            'password' => [
                'type'        => 'password',
                'label'       => 'Current password',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
            'newpassword' => [
                'type'        => 'password',
                'label'       => 'Choose a new password',
                'description' => 'Choose a password which contains at least 8 characters. It should contain both letters, numbers and special characters. Your password will be encrypted and is not visible to anyone. Make sure the browser URL starts with "https://" when you fill out this form.',
                'required'    => false,
                'validation'  => [],
            ],
            
            'newpassword2' => [
                'type'        => 'password',
                'label'       => 'Repeat new password',
                'required'    => false,
                'validation'  => ['match' =>
                    'newpassword'],
            ],
            
            'submit' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
                'value'     => 'Submit',
            ],
            'submit-delete' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmitDelete'],
                'value'     => 'Delete account',
            ],
        ]);
        
        $this->id = $id;
        $this->acronym = $acronym;
        $this->password = $password; 
        $this->activedate = $activedate;
        
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
        $valid = password_verify($this->Value('password'), $this->password);
        if($valid) {
            $password = strlen($this->Value('newpassword')) < 8 ? $this->password : password_hash($this->Value('newpassword'), PASSWORD_DEFAULT);
        } else {
            return false; 
        }
        $now = gmdate('Y-m-d H:i:s');
        
        if ($this->activedate == null && !empty($_POST['active'])) {
        $this->activedate = $now;
        }
        else if ($this->activedate != null && empty($_POST['active'])) {
        $this->activedate = null;
        }
        
        $web = !empty($_POST['web']) ? $this->Value('web') : '';
        
        $this->user = new \Anax\Users\User();
        $this->user->setDI($this->di);
        
        $saved = $this->user->save(array('id' => $this->id, 'acronym' => $this->acronym, 'email' => $this->Value('email'), 'presentation' => $this->Value('presentation'), 'web' => $web, 'password' => $password, 'created' => $now, 'updated' => $now, 'deleted' => null, 'gravatar' => 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->Value('email')))) . '.jpg'));
    
       // $this->saveInSession = true;
        
        if($saved) 
        {
        return true;
        }
        else return false;
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitDelete()
    {
    
        //$this->user = new \Anax\Users\User();
        //$this->user->setDI($this->di);
        
        $this->redirectTo('users/soft-delete/' . $this->id);
        return false;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
         $this->redirectTo('users/id/' . $this->user->getProperties()['id']);
    }


    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>The form was not filled out correctly.</i></p>");
        $this->redirectTo();
    }
}
