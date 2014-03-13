<?php

namespace Toolbox;

/**
 * Simple PHP template engine
 *
 * @author : Chad Emrys Minick
 * @source : http://codeangel.org/articles/simple-php-template-engine.html
 *
 * Usage :
 * 1 - Create a template file and use the echo function to render variables provided.
 *     Example : <title><?php echo $title; </title>
 * 2 - In your controller :
 *     Example : $view->new Template();
 *               $view->title = "Hello world !";
 *               $view->content = $view->render('content.php'); // Include inner template
 *               echo $view->render('main.php');
 */
class Template {

  private $variables = array();

  public function __get($key) {
    return $this->variables[$key];
  }

  public function __set($key, $value) {
    if($key == 'view_template_file') {
      throw new \Exception("Cannot bind variable named 'view_template_file'");
    }
    $this->variables[$key] = $value;
  }

  public function render($view_template_file) {
    if(array_key_exists('view_template_file', $this->variables)) {
      throw new \Exception("Cannot bind variable called 'view_template_file'");
    }
    extract($this->variables);
    ob_start();
    include($view_template_file);
    return ob_get_clean();
  }
}