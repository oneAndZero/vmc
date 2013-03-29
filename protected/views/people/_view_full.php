<div class="post">
    <div class="title">
        <?=$data->first_name . ' ' . $data->last_name . ' ' . $data->middle_name?>
    </div>
    <div class="content">
        <div>
            <span><b>Работал:</b> с <?=$data->year_1 . ' по ' . $data->year_2?></span>
        </div>
        <?if ($data->type == 1) { ?>
        <div>
            <span><b>Предметы:</b> <?
                if(!empty($data->subjects)){
                    $a = array();
                    foreach ($data->subjects as $subject)
                        $a[] = $subject->name;
                    echo implode(', ', $a);
                } else echo "нет";?></span>
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