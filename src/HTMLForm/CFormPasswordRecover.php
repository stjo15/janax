<?php

namespace Anax\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormPasswordRecover extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

   
    private $url;

    /**
     * Constructor
     *
     */
    public function __construct($url)
    {
        parent::__construct([], [
            'email' => [
                'type'        => 'text',
                'label'       => 'E-mail',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
            ],
            'submit' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
                'value'     => 'Submit',
            ],
            
        ]);
        
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
        $email = $this->Value('email');
        
        $this->user = new \Anax\Users\User();
        $this->user->setDI($this->di);
        
        $res = $this->user->findByEmail($email);
        
        if(count($res)>=1) {
            $userid = $res[0]->getProperties()['id'];
            $acronym = $res[0]->getProperties()['acronym'];
            $temp = slugify($acronym.'7D3F5G23j5B52D3'.$userid);
            $encrypt = password_hash($temp,PASSWORD_DEFAULT);
            
            $link = $this->url . 'users/reset/'.$userid.'/'.$encrypt;
            $message = "The password restore link was sent to your e-mail.";
            $to = $email;
            $subject="Password recovery";
            $from = ADMIN_MAIL;
            $body='Hi '.$acronym.'. Follow this link to choose a new password: '.$link.' Kind regards, Volvo S90 Q&A admin.';
            $headers = "From: " . strip_tags($from) . "\r\n";
            $headers .= "Answer to: ". strip_tags($from) . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
 
            $sent = mail($to,$subject,$body,$headers);
            
            
            $this->user->save(array('id' => $userid, 'password' => $encrypt));
            
            if($sent) {
                return true;
                header('location: www.volvo-s90.com/users/login');
            } else {
                return false;
            }
        } else {
            return false;
        }
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        $this->AddOutput("<p><i>Formuläret är inte korrekt ifyllt.</i></p>");
        return false;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {   
         $this->redirectTo('users/login');
    }


    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Formuläret är inte korrekt ifyllt.</i></p>");
        $this->redirectTo();
    }
}
