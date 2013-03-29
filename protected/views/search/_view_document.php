<table>
    <tr>
        <td width="60px">
            <? if(!empty($data->files)){
            $file = $data->files[0];?>
            <img width="60px" src="<?=YII_FILES . $file['folder'] . '/' . $file['name']?>"   alt="<?=$file['name']?>">
            <?}
            else{?>
                <img width="60px" src="<?=YII_FILES . 'no-doc.png'?>"   alt="no image">
                <?}?>
        </td>
        <td>
            <div class="post">
                <div class="title">
                    <?php echo CHtml::link($data->title, $data->url); ?>
                </div>
                <div class="content">
                    <?if (!empty($data->obj_file)) { ?>
                    <b>Прикрепленный файл:</b> <a target="_blank" href="<?='/files/' . $data->obj_file?>"
                                                  title="<?=$data->obj_file?>"><?=strlen($data->obj_file) > 20 ? substr($data->obj_file, 0, 20) . '...' : substr($data->obj_file, 0, 20);?></a></br>
                    <? }?>
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
        </td>
    </tr>
</table>