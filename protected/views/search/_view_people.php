<table>
    <tr>
        <td width="60px">
            <? if(!empty($data->files)){
            $file = $data->files[0];?>
            <img width="60px" src="<?=YII_FILES . $file['folder'] . '/' . $file['name']?>"   alt="<?=$file['name']?>">
            <?}
        else{?>
            <img width="60px" src="<?=YII_FILES . 'no-avatar.png'?>"   alt="no image">
            <?}?>
        </td>
        <td>
            <div class="post">
                <div class="title">
                    <?php echo CHtml::link($data->first_name . ' ' . $data->last_name . ' ' . $data->middle_name, $data->url); ?>
                </div>
                <div class="content">
                    <div>
                        <span><b>Работал:</b> с <?=$data->year_1 . ' по ' . $data->year_2?></span>
                    </div>
                    <div>
                        <b>Теги:</b>
                        <?php echo !empty($data->tagLinks) ? implode(', ', (array)$data->tagLinks) : 'нет'; ?>
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

