<?php

namespace login\model;

use login\model\InvalidCharactersException;
use login\model\TooShortNameException;

class Username {
  private $username;
  private static $MIN_USERNAME_LENGTH = 3;

  public function __construct(string $username) {
    $this->handleInvalidLength($username);
    $this->handleInvalidCharacters($username);

    $this->username = $username;
  }

  public function getUsername () : string {
    return $this->username;
  }

  private function hasInvalidLength (string $username) : bool {
    if (empty($username) || strlen($username) < self::$MIN_USERNAME_LENGTH) {
      return true;
    } else {
      return false;
    }
  }

  private function hasInvalidCharacters(string $username) : bool {
    if ($username != strip_tags($username)) {
      return true;
    } else {
      return false;
    }
  }

  private function handleInvalidCharacters (string $username) : void {
    if ($this->hasInvalidCharacters($username)) {
      throw new InvalidCharactersException;
    }
  }

  private function handleInvalidLength (string $username) : void {
    if ($this->hasInvalidLength($username)) {
      throw new TooShortNameException;
    }
  }
}
