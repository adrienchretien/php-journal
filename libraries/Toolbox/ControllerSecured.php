<?php

namespace Toolbox;

class ControllerSecured extends Controller {

  public static function __callStatic($name, $arguments) {
    if (static::_checkSession() && !preg_match('/^_\w+/i', $name)) {
      static::$view = new Template;
      call_user_func_array(array(get_called_class(), $name),$arguments);
    } else {
      static::_redirect('http://' . $_SERVER['HTTP_HOST']);
    }
  }
}