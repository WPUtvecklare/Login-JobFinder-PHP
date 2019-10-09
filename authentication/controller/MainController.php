<?php

namespace login\controller;

# View
require_once('authentication/view/LoginView.php');
require_once('authentication/view/RegisterView.php');
require_once('authentication/view/DateTimeView.php');
require_once('authentication/view/LayoutView.php');
require_once('authentication/view/Messages.php');

# Settings
require_once('authentication/LocalSettings.php');
require_once('authentication/ProductionSettings.php');

# Model
require_once('authentication/model/Database.php');
require_once('authentication/model/AuthenticationSystem.php');
require_once('authentication/model/Username.php');
require_once('authentication/model/Password.php');
require_once('authentication/model/UserCredentials.php');
require_once('authentication/model/NewUser.php');
require_once('authentication/model/UserStorage.php');

# Controller
require_once('authentication/controller/LoginController.php');
require_once('authentication/controller/RegisterController.php');
require_once('authentication/controller/MainController.php');

class MainController {
  private $storage;
  private $authSystem;
  
  private $layoutView;
  private $loginView;
  private $registerView;
  private $dateTimeView;

  private $loginController;
  private $registerController;

  private $isLoggedIn;

  public function __construct () {
    $this->storage = new \login\model\UserStorage();
    $this->authSystem = new \login\model\AuthenticationSystem($this->storage);

    $this->layoutView = new \login\view\layoutView();
    $this->dateTimeView = new \login\view\DateTimeView();
    $this->loginView = new \login\view\LoginView($this->storage);
    $this->registerView = new \login\view\RegisterView();

    $this->loginController = new \login\controller\LoginController($this->loginView, $this->authSystem);
    $this->registerController = new \login\controller\RegisterController($this->registerView, $this->authSystem);

    $this->isLoggedIn = $this->storage->hasStoredUser();
  }

  public function run () {
    if ($this->loginView->hasCookie() && !$this->isLoggedIn) {
      $this->isLoggedIn = $this->loginController->loginByCookie();
    } else if ($this->loginView->userWantsToLogin() && !$this->isLoggedIn) {
      $this->isLoggedIn = $this->loginController->login();
    } else if ($this->loginView->userHasClickedLogout() && $this->isLoggedIn) {
      $this->isLoggedIn = $this->loginController->logout();
    } else if ($this->loginView->hasCookie() && $this->isLoggedIn) {
      $this->loginController->generateNewPassword();
    }
    
    if ($this->layoutView->userWantsToRegister()) {
      if ($this->registerView->userhasClickedRegister()) {
        $this->registerController->register();
      }

    } else if ($this->storage->hasNewRegistreredUser()) {
      $this->loginController->welcomeNewUser();
    }
  }

  public function renderHTML ($view = null) {
    if (!$this->isLoggedIn && $this->layoutView->userWantsToRegister()) {
      return $this->layoutView->render($this->isLoggedIn, $this->registerView, $this->dateTimeView);  
    } else if (!$this->isLoggedIn) {
      return $this->layoutView->render($this->isLoggedIn, $this->loginView, $this->dateTimeView);
    } else {
      return $this->layoutView->render($this->isLoggedIn, $this->loginView, $this->dateTimeView, $view);
    }
  }
}