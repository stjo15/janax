<?php

namespace Anax\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormUserAdd extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;



    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct([], [
            'acronym' => [
                'type'        => 'text',
                'label'       => 'Username',
                'description' => 'The name which will be displayed on your public profile. The name must be unique and contain maximum 20 characters.',
                'required'    => true,
                'validation'  => array('not_empty', 'custom_test' => array(
                    'message' => 'Username is already taken.', 
                    'test' => function ($value) {
                        $this->user = new \Anax\Users\User();
                        $this->user->setDI($this->di);
                        $users = $this->user->findAll();
                        foreach ($users as $user) {
                            $acronym = $user->acronym;
                            if($acronym == $value) {
                                return false;
                            }
                        }
                    }),
                ),
            ],
            'presentation' => [
                'type'        => 'textarea',
                'label'       => 'Presentation',
                'description' => 'The presentation will appear in your public profile. There is no character limit, but it is recommended to limit the presentation to 400 characters.',
                'required'    => false,
                'validation'  => [],
            ],
            'email' => [
                'type'        => 'text',
                'label'       => 'E-mail',
                'placeholder' => 'email@example.com',
                'description' => 'Make sure that you enter the correct e-mail address. It is needed in case you need to restore your password and also for other important messages. Your e-mail address will not be displayed publicly.',
                'required'    => true,
                'validation'  => array('not_empty', 'email_adress', 'custom_test' => array(
                    'message' => 'This E-mail adress is already registered.', 
                    'test' => function ($value) {
                        $this->user = new \Anax\Users\User();
                        $this->user->setDI($this->di);
                        $users = $this->user->findAll();
                        foreach ($users as $user) {
                            $email = $user->email;
                            if($email == $value) {
                                return false;
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
                'validation'  => ['not_empty', 'email_adress', 'match' =>
                    'email'],
            ],
            
            'web' => [
                'type'        => 'text',
                'label'       => 'Website',
                'placeholder' => 'http://www.example.com',
                'description' => 'Tip! You can promote a website you like on your public profile. However, links to sexual, violent or illegal content will not be accepted! The URL character limit is 200.',
                'validation'  => ['web_adress'],
            ],  
            
            'password' => [
                'type'        => 'password',
                'label'       => 'Choose password',
                'description' => 'Choose a password which contains at least 8 characters. It should contain both letters, numbers and special characters. Your password will be encrypted and is not visible to anyone. Make sure the browser URL starts with "https://" when you fill out this form.',
                'required'    => true,
                'validation'  => array('not_empty', 'custom_test' => array(
                    'message' => 'You need to enter at least 8 characters.', 
                    'test' => function ($value) {
                        if(strlen($value) < 8) {
                            return false;
                        };
                    }),
                ),
            ],
            
            'password2' => [
                'type'        => 'password',
                'label'       => 'Repeat password',
                'required'    => true,
                'validation'  => ['not_empty', 'match' =>
                    'password'],
            ],
            
            'submit' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
                'value'     => 'Register',
            ],
            
        ]);
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
        
        $now = gmdate('Y-m-d H:i:s');
        $active = $now;
        $web = !empty($_POST['web']) ? $this->Value('web') : '';

        $this->newuser = new \Anax\Users\User();
        $this->newuser->setDI($this->di);
        $saved = $this->newuser->save(array('acronym' => $this->Value('acronym'), 'email' => $this->Value('email'), 'presentation' => $this->Value('presentation'), 'web' => $this->Value('web'), 'password' => password_hash($this->Value('password'),PASSWORD_DEFAULT), 'created' => $now, 'updated' => $now, 'deleted' => null, 'active' => $active, 'gravatar' => 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->Value('email')))) . '.jpg'));
    
       // $this->saveInSession = true;
        
        if($saved) 
        {
        return true;
        }
        else {
            return false;
        }
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        $this->AddOutput("<p><i>The form was not filled out correctly.</i></p>");
        return false;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {   
         //login the created user and redirect to profile page
         $this->redirectTo('users/login');
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
