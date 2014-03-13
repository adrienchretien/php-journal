<?php
  $this->layoutClass = 'one-column';

  $articleContent = '';
  $articleSlug = '';
  $isPrivate = '';
  $isUnfinished = '';

  if (isset($article)) {
    $articleContent = $article->getMarkdown();
    $articleSlug = $article->get('slug');
    $isPrivate = $article->isPrivate() ? ' checked' : '';
    $isUnfinished = $article->isUnfinished() ? ' checked' : '';
  } else if (isset($_POST['content'])) {
    $articleContent = $_POST['content'];
    $articleSlug = $_POST['slug'];
    $isPrivate = ' ' . $_POST['is-private'];
    $isUnfinished = ' ' . $_POST['is-unfinished'];
  }
?>

    <h1><?php echo $title; ?></h1>
    <form action="/admin/journal/save" method="post">
      <fieldset>
        <input class="input" type="hidden" id="slug" name="slug" value="<?php echo $articleSlug; ?>">
        <label for="content">
          Contenu <span class="required">*</span>
          <textarea class="input" id="content" name="content"><?php echo $articleContent; ?></textarea>
        </label>
      </fieldset>
      <fieldset>
        <legend>Options</legend>
        <div class="group--inline">
          <input type="checkbox" id="is-private" name="is-private"<?php echo $isPrivate; ?>>
          <label for="is-private">Article privé</label>
          <input type="checkbox" id="is-unfinished" name="is-unfinished"<?php echo $isUnfinished; ?>>
          <label for="is-unfinished">Article incomplet</label>
        </div>
      </fieldset>
      <div class="commands">
        <button class="button" type="submit">Enregistrer</button>
      </div>
    </form>
<?php echo $markdownNotation; ?>