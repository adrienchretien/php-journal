<?php

require_once 'models/Article.php';

use Toolbox\ControllerSecured;

class Journal extends ControllerSecured {

  private function _render() {
    self::$view->isAdmin = true;
    echo self::$view->render('views/page.php');
  }

  protected static function index() {
    self::$view->title = 'Articles';
    $articles = Article::findAll();
    self::$view->articles = Article::sortByDate($articles, SORT_DESC);
    self::$view->content = self::$view->render('views/admin/journal-list.php');
    self::_render();
  }

  protected static function form($title, $article = null) {
    self::$view->title = $title;
    self::$view->article = $article;
    self::$view->content = self::$view->render('views/admin/journal-form.php');
    self::_render();
  }

  protected static function create() {
    self::form('New article');
  }

  protected static function edit($data) {
    try {
      $article = Article::findByMeta('uri', $data['{@path}']);
      self::form('Edit', $article);
    } catch (Exception $e) {
      self::_error($e);
      self::index();
    }
  }

  protected static function save($data) {
    $article = null;
    try {
      if (isset($_POST['slug']) && $_POST['slug'] !== '') {
        $article = Article::findByMeta('slug', $_POST['slug']);
        $article->setMarkdown($_POST['content']);
      } else {
        $article = Article::fromContent($_POST['content']);
      }

      $isPrivate = (isset($_POST['is-private'])) ? 'true' : 'false';
      $isUnfinished = (isset($_POST['is-unfinished'])) ? 'true' : 'false';

      $article->setPrivate($isPrivate);
      $article->setUnfinished($isUnfinished);
      $article->save();

      self::_success('Article saved.');
      self::index();
    } catch (Exception $e) {
      $title = strrchr($_SERVER['HTTP_REFERER'], '/') === '/create' ? 'Nouvel article' : 'Edition de l\'article';

      self::_error($e);
      self::form($title, $article);
    }
  }

  protected static function delete($data) {
    $article = Article::findByMeta('uri', $data['{@path}']);

    if (is_null($article)) {
      self::_error(new Exception('Article not found or already deleted.', 1));
    } else {
      $article->delete();
      self::_success('Article deleted.');
    }

    self::index();
  }

}