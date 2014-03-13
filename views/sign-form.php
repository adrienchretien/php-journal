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
  <link rel="stylesheet" href="/styles/styles.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/styles/home.css" type="text/css" media="screen">
</head>
<body>
  <div class="signIn">
    <?php if(isset($error)) { ?>
    <p class="feedback error"><?php echo $error; ?></p>
    <?php } else if(isset($success)) { ?>
    <p class="feedback success"><?php echo $success; ?></p>
    <?php } ?>
    <form class="form" action="/logIn" method="post">
      <fieldset>
        <label for="username">
          Nom d'utilisateur <span class="required">*</span>
          <input class="input" type="text" id="username" name="username">
        </label>
        <label for="password">
          Mot de passe <span class="required">*</span>
          <input class="input" type="password" id="password" name="password">
        </label>
      </fieldset>
      <div class="commands">
        <button class="button" type="submit">Me connecter</button>
      </div>
    </form>
  </div>
</body>
</html>