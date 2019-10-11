<?php

namespace login;

use login\model\MissingDBVariable;

class ProductionSettings {
    public $DB_HOST;
    public $DB_NAME;
    public $DB_USERNAME;
    public $DB_PASSWORD;

    public function __construct () {
      $url = getenv('JAWSDB_URL');

      $url = '';

      if (empty($url)) {
        throw new MissingDBVariable;
      }

      
      $dbparts = parse_url($url);
      
      $this->DB_HOST = $dbparts['host'];
      $this->DB_USERNAME = $dbparts['user'];
      $this->DB_PASSWORD = $dbparts['pass'];
      $this->DB_NAME = ltrim($dbparts['path'],'/');
    }
}