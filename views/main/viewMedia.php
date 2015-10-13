<?php
use components\Helper;

?>

<?php Helper::renderStatic('main/_filterUserForm', compact('options'), false); ?>
<?php if(!empty($userInfo)): ?>
    <?php Helper::renderStatic('main/_summaryUserInfo', compact('userInfo', 'options'), false); ?>
<?php endif; ?>
<table border="1" align="center" cellpadding="0" cellspacing="0" id="mainTable">
    <thead align="center" style="background-color: lightblue;">
        <tr>
            <td style="width: 100px;">Link</td>
            <td style="width: 20px;">Type</td>
            <td style="width: 30px;">Likes</td>
            <td style="width: 30px;">Comments</td>
            <td style="width: 100px;">Caption</td>
            <td style="width: 100px;">Tags</td>
            <td style="width: 100px;">Create Date</td>
        </tr>
    </thead>
    <tbody align="center">
        <?php Helper::renderStatic('main/_viewMediaMore', compact('media', 'options'), false); ?>
    </tbody>
</table>
