<?php

namespace Anax\Users;
 
/**
 * A controller for users and admin related events.
 *
 */
class UsersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    
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
    * List all users.
    *
    * @return void
    */
    public function listAction()
    {
        $this->users->denyAccessToPage('admin');
        
        $all = $this->users->findAllActive();
        $status = $this->users->IsAuthenticated();
 
        $this->theme->setTitle("Visa alla användare");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Visa alla användare",
            'status' => $status,
        ], 'main');
        
        $this->views->add('users/users-sidebar', [], 'rsidebar');
     }
     
     /**
    * List user with id.
    *
    * @param int $id of user to display
    *
    * @return void
    */
    public function idAction($id = null)
    {
        $user = $this->users->find($id);
        $acronym = $user->acronym;
        
        $id = isset($id) ? $id : $_SESSION['user']->id;
        
        // Get questions asked
        $this->questions = new \Anax\Question\Question();
        $this->questions->setDI($this->di);
        $questions = $this->questions->findUserIdFromTable('question', $id);
        
        // Get answers given
        $this->answers = new \Anax\Answer\Answer();
        $this->answers->setDI($this->di);
        $answers = $this->answers->findUserIdFromTable('answer', $id);
        
        $redirect = $this->url->create('user/id/' . $id);
        
        $this->theme->setTitle($acronym);
        
        // Flash
        $this->views->add('users/view-flash', [
            'user' => $user,
            'title' => $acronym,
        ], 'flash');
        
        // Featured 1
        $this->views->add('users/view-featured-1', [
            'user' => $user,
        ], 'featured-1');
        // Featured 2
        $this->views->add('users/view-featured-2', [
            'user' => $user,
        ], 'featured-2');
        // Featured 3
        $this->views->add('users/view-featured-3', [
            'user' => $user,
        ], 'featured-3');
        
        // Main
        $this->views->add('users/view-main', [
            'user' => $user,
            'title' => 'Presentation',
        ], 'main');
        
        $this->di->views->add('default/button', [
                'anchor' => "Show comments made",
                'id' => 'show-comments', 
                'value' => null, 
            ]);
        
        // Right sidebar
        $this->views->add('users/view-rsidebar', [
            'user' => $user,
            'title' => '<i class="fa fa-cogs"></i>',
        ], 'rsidebar');
        // Questions
        $this->views->add('users/comments', [
            'user' => $user,
            'controller' => 'question',
            'title' => 'Asked questions',
            'comments' => $questions,
            'redirect' => $redirect,
        ], 'triptych-1');
        // Comments
        $this->views->add('users/comments', [
            'user' => $user,
            'controller' => 'answer',
            'title' => 'Answered questions',
            'comments' => $answers,
            'redirect' => $redirect,
        ], 'triptych-2');
    }
    
    /**
     * list users sorted by column.
     *
     * @return void
     */
    public function userlistAction($orderby=null, $title=null, $limit=null, $template, $region='main')
    {
        $users = $this->users->findByColumn($orderby, $limit);
        $this->views->add('users/'.$template, [
            'users' => $users,
            'title' => $title,
        ], $region);
        
    }
    
    /**
    * Add new user.
    *
    *
    * @return void
    */
    public function addAction()
    {
        $form = new \Anax\HTMLForm\CFormUserAdd();
        $form->setDI($this->di);
        $status = $form->check();
        
        $this->theme->setTitle("Register new user");
        $this->views->add('users/add', [
            'title' => "Register new user",
            'form' => $form->GetHTML(),
            ]);   
    }
    
    /**
    * Delete user.
    *
    * @param integer $id of user to delete.
    *
    * @return void
    */
    public function deleteAction($id = null)
    {
        $this->users->denyAccessToPage('user', $id);
        
        if (!isset($id)) {
            die("Missing id");
        }
 
        $res = $this->users->delete($id);
        
        $this->users->logout();
        
        $url = $this->url->create('');
        $this->response->redirect($url);
    }
    
    
    /**
    * Delete (soft) user.
    *
    * @param integer $id of user to delete.
    *
    * @return void
    */
    public function softDeleteAction($id = null)
    {
        $this->users->denyAccessToPage('user', $id);
        
        if (!isset($id)) {
            die("Missing id");
        }
 
        $now = gmdate('Y-m-d H:i:s');
 
        $user = $this->users->find($id);
 
        $user->deleted = $now;
        $user->active = null;
        $user->save();
 
        $url = $this->url->create('users');
        $this->response->redirect($url);
    }
    
    /**
    * Activate user.
    *
    * @param integer $id of user to activate.
    *
    * @return void
    */
    public function activateAction($id = null)
    {
        $this->users->denyAccessToPage('user', $id);
        
        if (!isset($id)) {
            die("Missing id");
        }
 
        $now = gmdate('Y-m-d H:i:s');
 
        $user = $this->users->find($id);
 
        $user->active = $now;
        $user->save();
 
        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }
    
    /**
    * Inactivate user.
    *
    * @param integer $id of user to inactivate.
    *
    * @return void
    */
    public function inActivateAction($id = null)
    {
        $this->users->denyAccessToPage('user', $id);
        
        if (!isset($id)) {
            die("Missing id");
        }
 
        $user = $this->users->find($id);
 
        $user->active = null;
        $user->save();
 
        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }
    
    /**
    * Restore (soft) deleted user.
    *
    * @param integer $id of user to restore.
    *
    * @return void
    */
    public function restoreAction($id = null)
    {
        $this->users->denyAccessToPage('user', $id);
        
        if (!isset($id)) {
            die("Missing id");
        }
        
        $now = gmdate('Y-m-d H:i:s');
        
        $user = $this->users->find($id);
 
        $user->deleted = null;
        $user->save();
 
        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }
    
    /**
    * List all active and not deleted users.
    *
    * @return void
    */
    public function activeAction()
    {
        $this->users->denyAccessToPage('admin');
        
        $all = $this->users->query()
            ->where('active IS NOT NULL')
            ->andWhere('deleted is NULL')
            ->execute();
 
        $this->theme->setTitle("Aktiva användare");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Aktiva användare",
            ]);
    }
    
    /**
    * List all inactive users.
    *
    * @return void
    */
    public function inActiveAction()
    {
        $this->users->denyAccessToPage('admin');
        
        $all = $this->users->query()
            ->where('active is NULL')
            ->execute();
 
        $this->theme->setTitle("Inaktiva användare");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Inaktiva användare",
            ]);
    }
    
    /**
    * List all active and not deleted users.
    *
    * @return void
    */
    public function softDeletedAction()
    {
        $this->users->denyAccessToPage('admin');
        
        $all = $this->users->query()
            ->where('deleted IS NOT NULL')
            ->execute();
 
        $this->theme->setTitle("Användare i papperskorgen");
        $this->views->add('users/deleted', [
            'users' => $all,
            'title' => "Användare i papperskorgen",
            ]);
    }
    
    /**
    * Update user.
    *
    * @param $id of user to update.
    *
    * @return void
    */
    public function updateAction($id = null)
    {
        $this->users->denyAccessToPage('user', $id);
        
        $user = $this->users->find($id);
    
        $presentation = $user->getProperties()['presentation'];
        $acronym = $user->getProperties()['acronym'];
        $email = $user->getProperties()['email'];
        $web = $user->getProperties()['web'];
        $password = $user->getProperties()['password'];
        $active = $user->getProperties()['active'];
        $deleted = $user->getProperties()['deleted'];
        $created = $user->getProperties()['created'];
    
        $form = new \Anax\HTMLForm\CFormUserUpdate($id, $acronym, $presentation, $email, $web, $password, $active, $created);
        $form->setDI($this->di);
        $status = $form->check();
    
        $this->di->theme->setTitle("Edit user");
        $this->di->views->add('users/update', [
                'title' => "Edit user",
                'form' => "<h4>".$user->getProperties()['acronym']." 
            (id ".$user->getProperties()['id'].")</h4>".$form->getHTML()
            ]);
    }
    
    /**
    * Setup user table.
    *
    * @return void
    */
    public function setupAction()
    {
        
    $this->users->denyAccessToPage('admin');
    
    $this->db->setVerbose(false);
 
    $this->db->dropTableIfExists('user')->execute();
 
    $this->db->createTable(
        'user',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'acronym' => ['varchar(20)', 'unique', 'not null'],
            'email' => ['varchar(80)'],
            'name' => ['varchar(80)'],
            'password' => ['varchar(255)'],
            'created' => ['datetime'],
            'updated' => ['datetime'],
            'deleted' => ['datetime'],
            'active' => ['datetime'],
        ]
    )->execute();
    
    $this->db->insert(
        'user',
        ['acronym', 'email', 'name', 'password', 'created', 'active']
    );
 
    $now = gmdate('Y-m-d H:i:s');
 
    $this->db->execute([
        'admin',
        'admin@dbwebb.se',
        'Administrator',
        password_hash('admin', PASSWORD_DEFAULT),
        $now,
        $now
    ]);
    
    $all = $this->users->findAll();
 
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Visa alla användare",
        ]);
    }
    
    
    /**
    * Check if user is logged in or not.
    *
    * @param int $id of user to display
    *
    * @return void
    */
    public function checkAction()
    {
        $id = isset($_SESSION['user']) ? $_SESSION['user']->id : null;
        $status = $this->users->IsAuthenticated();
        
        if($id) {
            
           $url = $this->url->create('users/id/'.$id);
           $this->response->redirect($url);
        }
        else {
           $url = $this->url->create('users/login');
           $this->response->redirect($url);
        }
        
    }
    
    /**
    * View a login form
    *
    *
    * @return void
    */
    public function loginAction()
    {
        $form = new \Anax\HTMLForm\CFormUserLogin();
        $form->setDI($this->di);
        $status = $form->check();
        
        $this->theme->setTitle("Sign in");
        $this->views->add('users/login', [
            'title' => "Sign in",
            'form' => $form->GetHTML(),
            ]);   
    }
    
    /**
    * Logout user and unset session
    *
    *
    * @return void
    */
    public function logoutAction()
    {
        $this->users->logout();
    }
    
    /**
    * Go to a message page and display the message
    *
    *
    * @return void
    */
    public function messageAction($message=null)
    {   
        $adminmessage = 'You need to be administrator to have access to this page!';
        $wrongusermessage = 'You don\'t have permission to enter this page!';
        $usermessage = "You need to <a href='../login'>sign in</a> to enter this page!";
        $content = '';
        if($message == 'admin') {
            $content = $adminmessage;
        }
        else if($message == 'wronguser') {
            $content = $wrongusermessage;
        } else {
            $content = $usermessage;
        }
        
        $this->theme->setTitle("Message");
        $this->views->add('default/error', [
            'title' => "Message",
            'content' => $content,
            ]);   
    }
    
    /**
    * Ask user for mail adress and send restore link
    *
    *
    * @return void
    */
    public function recoverAction()
    {
        $url = $this->url->create();
        
        $form = new \Anax\HTMLForm\CFormPasswordRecover($url);
        $form->setDI($this->di);
        $status = $form->check();
        
        $this->theme->setTitle("Recover password");
        $this->views->add('users/recover', [
            'title' => "Recover password",
            'form' => $form->GetHTML(),
            ]);   
    }
    
    /**
    * Ask user to reset the password.
    *
    * $param 
    *
    * @return void
    */
    public function resetAction($id, $encrypt)
    {
        
        $user = $this->users->find($id);
        $password = $user->getProperties()['password'];
        if($password == $encrypt) {
            $form = new \Anax\HTMLForm\CFormPasswordReset($id);
            $form->setDI($this->di);
            $status = $form->check();
        
            $this->theme->setTitle("Choose a new password");
            $this->views->add('users/reset', [
                'title' => "Choose a new password",
                'form' => $form->GetHTML(),
                ]);   
        } else {
            $this->theme->setTitle("Message");
            $this->views->add('default/error', [
                'title' => "Message",
                'content' => 'You don\'t have access to this page!',
            ]);   
        }
    }
    
    /**
    * Save the best laptime from the racing game
    *
    *
    * @return void
    */
    public function laptimeAction()
    {
        $bestlap = $_POST['bestlap'];
        $numlaps = $_POST['numlaps'];
        $user = $this->user->find($_SESSION['user']->id);
        $user->update(array('bestlap' => $bestlap, 'numlaps' => $numlaps));
        
        $_SESSION['user']->bestlap = $bestlap;
        $_SESSION['user']->numlaps = $numlaps;
        
        $this->theme->setTitle("Game saved");
        $this->views->add('default/error', [
            'title' => "Game saved",
            'content' => 'Your laptime was saved successfully. Thank you for playing!',
        ]);
    }
    

}