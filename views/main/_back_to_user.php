<?php
use config\Config;
use components\Helper;
?>

<a href="<?= Helper::generateUrl(Config::get('defaultController'), Config::get('mainViewAction'), ['code' => $options['code'], 'userName' => $options['userName']]) ?>"><< Back to user</a>
