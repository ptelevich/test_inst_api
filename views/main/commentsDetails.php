<?php
use config\Config;
use components\Helper;

?>
<?php Helper::renderStatic('main/_back_to_user', ['options' => $options], false) ?>
<table border="1" align="center">
    <tr>
        <td>Publisher Info</td>
        <td>Text</td>
        <td>Create Date</td>
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
                <?php Helper::renderStatic('main/_likesDetailTables', ['media' => [$value->from], 'options' => $options]) ?>
            </td>
            <td>
                <?= $value->text ?>
            </td>
            <td>
                <?= date('d F, Y H:i:s', $value->created_time) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
