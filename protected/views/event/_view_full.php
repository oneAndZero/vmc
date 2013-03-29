<div class="post">
    <div class="title">
        <?=$data->title?>
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
            <span><b>Описание:</b> <? echo !empty($data->description) ? $data->description : "нет"?></span>
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
        <div>
            <?
            if (!empty($data->files)) {
                $this->beginWidget('Galleria');
                foreach ($data->files as $file) {
                    ?>
                    <img src="<?=YII_FILES . $file['folder'] . '/' . $file['name']?>"   alt="<?=$file['name']?>">
                    <?
                }
                $this->endWidget();
            } else {
                echo "Нет файлов";
            }?>
        </div>
    </div>
</div>
