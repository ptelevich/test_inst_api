<?php
use config\Config;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />
    <title>Тестовое задание Instagram</title>
    <link rel="stylesheet" href="/<?= Config::CONST_INFOLDER ?>css/custom.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="/<?= Config::CONST_INFOLDER ?>css/bootstrap.min.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="/<?= Config::CONST_INFOLDER ?>js/main.js" type="text/javascript"></script>
</head>
<body>
    <div class="editMessage">
        <?php if(!empty($ok_message)): ?>
            <?= $ok_message; ?>
        <?php endif; ?>
    </div>
    <?= $content ?>
</body>
</html>
