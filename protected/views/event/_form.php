<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array('htmlOptions' => array('enctype' => 'multipart/form-data'))); ?>

    <p class="note"><span class="required">*</span> - обязательные поля</p>

    <?php echo CHtml::errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title', array('size' => 80, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'description'); ?>
        <?php echo CHtml::activeTextArea($model, 'description', array('rows' => 7, 'cols' => 64)); ?>
        <?php echo $form->error($model, 'description'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'place_event'); ?>
        <?php echo $form->textField($model, 'place_event', array('size' => 80, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'place_event'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'date_event'); ?>
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker',
        array(
            'name' => 'date_event',
            'attribute' => 'date_event', // Model attribute filed which hold user input
            'model' => $model, // Model name
            'language' => 'ru',
            'options'=>array(
                'dateFormat'=>'yy-mm-dd',
            ),

            'htmlOptions' => array('size' => 80),
        )
    );?>
        <?php echo $form->error($model, 'date_event'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'tags'); ?>
        <?php $this->widget('CAutoComplete', array(
        'model' => $model,
        'attribute' => 'tags',
        'url' => array('suggestTags'),
        'multiple' => true,
        'htmlOptions' => array('size' => 80),
    )); ?>
        <p class="hint">Разделителем между тегами является - запятая</p>
        <?php echo $form->error($model, 'tags'); ?>
    </div>
    <div class="files">
        <?php echo $form->labelEx($model, 'obj_file'); ?>
        <div class="row file">
            <?php echo CHtml::activeFileField($model, 'obj_file[]'); ?>
            <?php echo $form->error($model, 'obj_file'); ?>
        </div>
    </div>
    <div>
        <input type="button" id="add" value="+">
        <input type="button" id="remove" value="-">
    </div>


    <div class="row">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
