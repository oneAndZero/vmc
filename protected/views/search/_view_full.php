<div class="post">
    <div class="title">
        <?=$data->title?>
    </div>
    <div class="content">
        <?if (!empty($data->obj_file)) { ?>
        <div>
            <b>Прикрепленный файл:</b> <a target="_blank" href="<?='/files/' . $data->obj_file?>"
                                          title="<?=$data->obj_file?>"><?=$data->obj_file;?></a>
        </div>
        <? }?>
        <div>
            <span><b>Описание:</b> <? echo !empty($data->description) ? $data->description : "нет"?></span>
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