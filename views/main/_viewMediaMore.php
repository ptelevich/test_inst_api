<?php
use config\Config;
use components\Helper;

?>
<?php foreach ($media->data as $i => $data): ?>
    <?php
        $bgTr = '';
        if($i%2) {
            $bgTr = 'background-color: lightgreen;';
        }
    ?>
    <tr style="<?= $bgTr ?> height: 40px;" class="trContainer">
        <td>
            <span style="display: none" class="createTime"><?= $data->created_time ?></span>
            <a href="<?= $data->link ?>" target="_blank"><?= $data->link ?></a>
        </td>
        <td>
            <?= $data->type ?>
        </td>
        <td>
            <?php
                if(!empty($data->likes->count)) {
                    echo '<a href="'. Helper::generateUrl('main', 'likesDetails',
                            [
                                'code' => $options['code'],
                                'mediaId' => $data->id,
                                'userName' =>  $options['userName'],
                            ]) .'" target="blank">'.$data->likes->count.'</a>';
                } else {
                    echo $data->likes->count;
                }
            ?>
        </td>
        <td>
            <?php
            if(!empty($data->comments->count)) {
                echo '<a href="'. Helper::generateUrl('main', 'commentsDetails',
                        [
                            'code' => $options['code'],
                            'mediaId' => $data->id,
                            'userName' =>  $options['userName'],
                        ]) .'" target="blank">'.$data->comments->count.'</a>';
            } else {
                echo $data->comments->count;
            }
            ?>
        </td>
        <td style="word-wrap: break-word; word-break: break-all;">
            <?php if(isset($data->caption)): ?>
                <?= trim($data->caption->text) ?>
            <?php endif; ?>
        </td>
        <td>
            <?= !empty($data->tags) ? '#' : ''; ?>
            <?= implode(', #', $data->tags) ?>
        </td>
        <td>
            <?= date('d-m-Y', $data->created_time) ?>
        </td>
    </tr>
<?php endforeach; ?>
<?php if ($options['hasNextUrl']): ?>
    <tr class="loadmoretr">
        <td colspan="10">
            <img id="preloaderImg" src="/<?= Config::CONST_INFOLDER . 'css/292.GIF'?>" style="display: none;"/>
            <button
                id="loadMore"
                data-href="<?= Helper::generateUrl($options['c'], $options['a_next']) ?>"
                data-code="<?= $options['code'] ?>"
                data-username="<?= $options['userName'] ?>"
            >Load more... </button>
        </td>
    </tr>
<?php endif; ?>
