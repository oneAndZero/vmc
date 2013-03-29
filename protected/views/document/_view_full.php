<div class="post">
    <div class="title">
        <?=$data->title?>
    </div>
    <div class="content">
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
        <?
        if (!empty($data->files)) {
            $this->beginWidget('galleria');
            foreach ($data->files as $file) {
                ?>
                <img src="<?=YII_FILES . $file['folder'] . DIRECTORY_SEPARATOR . $file['name']?>"   alt="<?=$file['name']?>">
                <?
            }
            $this->endWidget();
        } else {
            echo "Нет файлов";
        }?>
    </div>
</div>