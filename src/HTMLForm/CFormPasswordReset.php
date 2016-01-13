<?php

namespace Anax\HTMLForm;

/**
 * Anax base class for resetting user password.
 *
 */
class CFormPasswordReset extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    private $id;
    
    /**
     * Constructor
     *
     */
    public function __construct($id=null)
    {
        parent::__construct([], [
            
            'newpassword' => [
                'type'        => 'password',
                'label'       => 'Choose a new password',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
            'newpassword2' => [
                'type'        => 'password',
                'label'       => 'Repeat the new password',
                'required'    => true,
                'validation'  => ['not_empty','match' =>
                    'newpassword'],
            ],
            
            'submit' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
                'value'     => 'Submit',
            ],
        ]);
        
        $this->id = $id;
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
        $password = password_hash($this->Value('newpassword'), PASSWORD_DEFAULT);
        
        $now = gmdate('Y-m-d H:i:s');
        
        $this->user = new \Anax\Users\User();
        $this->user->setDI($this->di);
        
        $saved = $this->user->save(array('id' => $this->id, 'password' => $password));
    
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
        $this->AddOutput("<p><i>Formuläret har fyllts i felaktigt.</i></p>");
        $this->redirectTo();
    }
}
