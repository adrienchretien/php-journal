<?php

use Toolbox\Config;
use Toolbox\Controller;


class Pages extends Controller {

  private static function _render() {
    self::$view->isAdmin = false;
    echo self::$view->render('views/page.php');
  }

  /* Pages */

  protected static function index() {
    self::$view->title = 'Welcome';
    echo self::$view->render('views/index.php');
  }

  protected static function pattern_library() {
    self::$view->title = 'Pattern library';
    self::$view->content = self::$view->render('views/pattern-library.php');
    self::_render();
  }

  protected static function style_guide() {
    self::$view->title = 'Style guide';
    self::$view->content = self::$view->render('views/style-guide.php');
    self::_render();
  }

  /* Errors */

  protected static function forbidden() {
    header("HTTP/1.0 403 Forbidden");
    self::$view->title = '403 − Access forbidden';
    self::$view->message = 'The file you want to access is unavailable.';
    self::$view->content = self::$view->render('views/error.php');
    self::_render();
  }

  protected static function notFound() {
    header("HTTP/1.0 404 Not Found");
    self::$view->title = '404 − File not found';
    self::$view->message = 'The file you want to access is not where it should be.';
    self::$view->content = self::$view->render('views/error.php');
    self::_render();
  }

  /* Log methods */

  protected static function signIn() {
    if (self::_checkSession()) {
      self::_redirect('http://' . $_SERVER['HTTP_HOST'] . '/admin');
    } else {
      self::$view->title = 'Connection';
      echo self::$view->render('views/sign-form.php');
    }
  }

  protected static function signOut() {
    self::_sessionDestroy();
    self::_redirect('http://' . $_SERVER['HTTP_HOST']);
  }

  protected static function logIn($data) {
    $uTest = $_POST['username'] === Config::get('username');
    $pTest = $_POST['password'] === Config::get('password');
    if ($uTest && $pTest) {
      Controller::_sessionStart();
    }

    if (Controller::_checkSession()) {
      self::_redirect('http://' . $_SERVER['HTTP_HOST'] . '/admin');
    } else {
      self::_error(new Exception('Invalid username or password.', 1));
      self::signIn();
    }
  }

}