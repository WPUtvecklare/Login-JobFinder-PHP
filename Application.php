<?php

namespace login;

//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

require_once('DatabaseConfig.php');

require_once('model/Database.php');
require_once('model/AuthenticationSystem.php');
require_once('model/Username.php');
require_once('model/Password.php');
require_once('model/UserCredentials.php');

require_once('controller/LoginController.php');

class Application {
  private $storage;
  private $authSystem;
  private $layoutView;
  private $loginView;
  private $loginController;

  public function __construct () {
    // $this->storage = new \login\model\UserStorage();
    // $this->user = $this->storage->loadUser();

    
    $this->authSystem = new \login\model\AuthenticationSystem();
    $this->layoutView = new \login\view\layoutView();
    $this->loginView = new \login\view\LoginView();
    $this->loginController = new \login\controller\LoginController($this->loginView, $this->authSystem);
  }

  public function run () {
    $dtv = new \login\view\DateTimeView();
    // Kolla sessionen
    $isLoggedIn = $this->loginController->login();
    $this->layoutView->render(false, $this->loginView, $dtv);

  }

  private function changeState () {
    // $this->loginController->doChangeUsername();
    // $this->storage->saveUser($this->user);
  }
}