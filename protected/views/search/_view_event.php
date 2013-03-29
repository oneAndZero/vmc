<table>
    <tr>
        <td width="60px">
            <? if(!empty($data->files)){
            $file = $data->files[0];?>
            <img width="60px" src="<?=YII_FILES . $file['folder'] . '/' . $file['name']?>"   alt="<?=$file['name']?>">
            <?}
            else{?>
                <img width="60px" src="<?=YII_FILES . 'no-image.png'?>"   alt="no image">
            <?}?>
        </td>
        <td>
            <div class="post">
                <div class="title">
                    <?php echo CHtml::link($data->title, $data->url); ?>
                </div>
                <div class="content">
                    <div>
                        <b>Место провидения:</b>
                        <?php echo !empty($data->place_event) ? $data->place_event : 'нет'; ?>
                    </div>
                    <div>
                        <b>Дата проведения:</b>
                        <?php echo !empty($data->date_event) ? date('d.m.Y', strtotime($data->date_event)) : 'нет'; ?>
                    </div>
                    <div>
                        <b>Теги:</b>
                        <?php echo !empty($data->tagLinks) ? implode(', ', (array)$data->tagLinks) : 'нет'; ?>
                    </div>
                    <div>
                        <?if (!Yii::app()->user->isGuest) { ?>
                        <div class="author">
                            Добавлен <?php echo date('d.m.Y H:i', strtotime($data->create_time)) . ' пользователем ' . $data->author->username; ?></br>
                            Обновлен <?php echo date('d.m.Y H:i', strtotime($data->update_time)); ?>
                        </div>
                        <? }?>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>