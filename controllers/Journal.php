<?php

use Toolbox\Config;
use Toolbox\Controller;

require_once 'models/Article.php';

class Journal extends Controller {

  private static function _render() {
    self::$view->isAdmin = false;
    echo self::$view->render('views/page.php');
  }

  protected static function index() {
    $list = Article::findAll();
    $articles = [];

    foreach ($list as $article) {
      if (!$article->isPrivate() && !$article->isUnfinished()) {
        array_push($articles, $article);
      }
    }

    self::$view->title = 'Journal';
    self::$view->articles = Article::sortByDate($articles, SORT_DESC);
    self::$view->content = self::$view->render('views/journal-list.php');
    self::_render();
  }

  protected static function view($data) {
    $article = Article::findByMeta('uri', $data['{@path}']);

    if (is_null($article)) {
      Pages::notFound();
    } else {
      self::$view->title = $article->get('title');
      self::$view->layoutClass = 'one-column';
      self::$view->content = $article->getHtml();
      self::_render();
    }
  }

}