<?php
use config\Config;
use components\Helper;

?>

<table border="1" align="center">
    <tr style="background-color: lightblue;">
        <td>User ID</td>
        <td>Username</td>
        <td>Full Name</td>
        <td>Profile Picture</td>
    </tr>
    <?php foreach ($media as $i => $value): ?>
        <?php
        $bgTr = '';
        if($i%2) {
            $bgTr = 'background-color: lightgreen;';
        }
        ?>
        <tr style="<?= $bgTr ?> height: 40px;">
            <td>
                <a href="<?= Helper::generateUrl(Config::get('defaultController'), Config::get('mainViewAction'), ['code' => $options['code'], 'userId' => $value->id]) ?>" target="_blank" title="view more info about user"><?= $value->id ?></a>
            </td>
            <td>
                <a href="http://instagram.com/<?= $value->username ?>/" target="_blank" title="go to user's profile page"><?= $value->username ?></a>
            </td>
            <td><?= $value->full_name ?></td>
            <td>
                <a href="<?= $value->profile_picture ?>" target="_blank">Link</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
