<?php

use Toolbox\Config;
use Toolbox\ControllerSecured;

class Admin extends ControllerSecured {

  private function _render() {
    self::$view->isAdmin = true;
    echo self::$view->render('views/page.php');
  }

  protected static function index() {
    self::_redirect('http://' . $_SERVER['HTTP_HOST'] . '/admin/journal');
  }
}