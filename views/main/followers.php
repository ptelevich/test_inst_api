<?php
use components\Helper;

?>

<?php Helper::renderStatic('main/_back_to_user', ['options' => $options], false) ?>
<table border="1" align="center" id="followersTable">
    <tr style="background-color: lightblue;">
        <td>User ID</td>
        <td>Username</td>
        <td>Full Name</td>
        <td>Profile Picture</td>
    </tr>
    <?php Helper::renderStatic('main/_followersList', compact('media', 'options'), false); ?>
</table>
