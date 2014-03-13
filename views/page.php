<?php
  use Toolbox\Config;
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?php echo $title; ?></title>
  <script src="/scripts/scripts.js" async></script>
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Code+Pro|Source+Sans+Pro:400,700,400italic,700italic" type="text/css">
  <link rel="stylesheet" href="/styles/styles.css" type="text/css">
<?php if ($isAdmin) { ?><link rel="stylesheet" href="/styles/admin.css" type="text/css"><?php } ?>
</head>
<body>
  <header role="banner">
    <div class="table header">
      <div class="table-cell w-25">
        <a href="/<?php if ($isAdmin) { ?>admin<?php } ?>" class="link-alt spaced upper"><?php echo Config::get('site-name'); ?></a>
      </div>

      <ul class="table-cell w-75 nav spaced-links no-markers" role="navigation">
<?php if ($isAdmin) { ?>
        <li>
          <a href="/admin/journal">Journal</a>
          <ul class="sub-nav no-markers">
            <li><a href="/admin/journal/create">Create article</a></li>
          </ul>
        </li>
        <li><a class="signout" href="/signOut">Sign out</a></li>
<?php } else { ?>
        <li><a href="/journal">Journal</a></li>
<?php } ?>
      </ul>
    </div>
  </header>
  <div class="main<?php if (isset($layoutClass)) { echo ' ' . $layoutClass; } ?>" role="main">
<?php if(isset($error)) { ?>
    <p class="feedback error"><?php echo $error; ?></p>
<?php } else if(isset($success)) { ?>
    <p class="feedback success"><?php echo $success; ?></p>
<?php } ?>
  <?php echo $content; ?>
  </div>
  <footer role="contentinfo">
    <div class="footer small">
      <p class="upper"><?php echo Config::get('site-name'); ?></p>
    </div>
  </footer>
</body>
</html>