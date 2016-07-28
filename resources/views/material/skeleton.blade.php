<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
  <title>{{ getEnv('APP_NAME')}}</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.deep_purple-pink.min.css">
  <link rel="stylesheet" href="/material/design.css">
  <body class="mdl-base">
    @yield('body')
    <script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
    <script>
    function showSnack(message, timeout, actionText, actionHandler) {
      'use strict';
      var snackbarContainer = $("#snackbar")[0];
      var data = {
        message: message,
        timeout: timeout,
        actionText: actionText,
        actionHandler: actionHandler
      };
      snackbarContainer.MaterialSnackbar.showSnackbar(data);
    }
    </script>
  </body>
  </html>
