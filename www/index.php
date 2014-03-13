<?php

chdir('..');

require_once 'vendor/autoload.php';
require_once 'controllers/Pages.php';

use Toolbox\Router\RouteController;

date_default_timezone_set('Europe/Paris');

$controller = new RouteController('config/routes');

try {
  $routes = $controller->find($_SERVER['REQUEST_URI']);

  if(count($routes) > 0) {
    $routes[0]->go();
  } else {
    Pages::notFound();
  }
} catch (Exception $e) {
  echo $e->getMessage();
}