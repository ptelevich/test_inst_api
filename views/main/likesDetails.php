<?php
use components\Helper;

?>
<?php Helper::renderStatic('main/_back_to_user', ['options' => $options], false) ?>

<?php Helper::renderStatic('main/_likesDetailTables', compact('media', 'options'), false); ?>
