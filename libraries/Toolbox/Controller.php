<?php

namespace Toolbox;

class Controller {

  protected static $view;

  public static function __callStatic($name, $arguments) {
    if (!preg_match('/^_\w+/i', $name)){
      static::$view = new Template;
      call_user_func_array(array(get_called_class(), $name),$arguments);
    }
  }

  /* Error messages */

  protected static function _error($e) {
    $msg = '';

    if (Config::get('debug') == 'true') {
      $msg .= $e->getFile() . '(' . $e->getLine() . '): ' . PHP_EOL;
      $msg .= $e->getMessage() . PHP_EOL;
      $msg .= 'Trace : ' . PHP_EOL;
      $msg .= $e->getTraceAsString();
    } else {
      $msg .= $e->getMessage();
    }

    static::$view->error = $msg;
  }

  protected static function _success($message) {
    static::$view->success = $message;
  }

  /* Header modifications */

  protected static function _redirect($url) {
    header('Location: ' . $url);
    exit();
  }

  /* Sessions */

  protected static function _sessionStart() {
    session_start();
    session_regenerate_id();
    $_SESSION['user-agent'] = md5($_SERVER['HTTP_USER_AGENT'] . Config::get('secret'));
  }

  protected static function _sessionDestroy() {
    session_start();
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
      );
    }
    session_destroy();
  }

  protected static function _checkSession() {
    if(!isset($_SESSION)) {
      session_start();
    }
    return isset($_SESSION['user-agent']) && $_SESSION['user-agent'] === md5($_SERVER['HTTP_USER_AGENT'] . Config::get('secret'));
  }

}