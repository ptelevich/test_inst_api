<?php
use components\Helper;

?>

<h3 id="summary_user_info_link"><span>+</span> Summary User Info</h3>
<table border="1" cellspacing="0" cellpadding="0" id="summary_user_info_data" align="center" style="display: none; width">
    <tr>
        <td>ID</td>
        <td><?=$userInfo->id?></td>
    </tr>
    <tr>
        <td>Profile Name</td>
        <td><?=$userInfo->username?></td>
    </tr>
    <tr>
        <td>Full Name</td>
        <td><?=$userInfo->full_name?></td>
    </tr>
    <tr>
        <td>BIO</td>
        <td><?=$userInfo->bio?></td>
    </tr>
    <tr>
        <td>Website</td>
        <td>
            <?php if($userInfo->website): ?>
                <a href="<?=$userInfo->website?>" target="_blank">Link</a>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>Profile Picture</td>
        <td>
            <?php if($userInfo->profile_picture): ?>
                <a href="<?=$userInfo->profile_picture?>" target="_blank">Link</a>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>Counts Media</td>
        <td><?=$userInfo->counts->media?></td>
    </tr>
    <tr>
        <td>Counts Followed By</td>
        <td>
            <a
                href="<?= Helper::generateUrl('main','followers', ['code' => $options['code'],'userName' => $userInfo->username])?>"
                target="_blank"
            ><?=$userInfo->counts->followed_by?></a>
        </td>
    </tr>
    <tr>
        <td>Counts Follows</td>
        <td>
            <a
                href="<?= Helper::generateUrl('main','follows', ['code' => $options['code'],'userName' => $userInfo->username])?>"
                target="_blank"
                ><?=$userInfo->counts->follows?></a>
        </td>
    </tr>
</table>
