<script>
    $(document).ready(function () {
        change_type();
        $('.type_people').change(function () {
            change_type();
        });

        function change_type() {
            type_people = $('.type_people input:checked').val();
            if (type_people == 1) {
                $('.student').hide();
                $('.administration').hide();
                $('.teacher').show();
            }
            else if (type_people == 2) {
                $('.teacher').hide();
                $('.administration').hide();
                $('.student').show();
            }
            else if (type_people == 3) {
                $('.student').hide();
                $('.teacher').hide();
                $('.administration').show();
            }
        }
    });
</script>
<div class="form">
    <?php for ($i = 1930; $i < 2020; $i++) $years[$i] = $i;?>
    <?php $form = $this->beginWidget('CActiveForm', array('htmlOptions' => array('enctype' => 'multipart/form-data'))); ?>

    <p class="note"><span class="required">*</span> - обязательные поля</p>

    <?php echo CHtml::errorSummary($model); ?>
    <style>
        div.form .type_people label {
            display: inline;
        }

        div.form .years label {
            display: inline;
        }

        div.form .subject label {
            display: inline;
        }

        div.form .teacher {
            display: none;
        }

        div.form .student {
            display: none;
        }

        div.form .administration {
            display: none;
        }
    </style>
    <div class="row type_people">
        <?php echo $form->labelEx($model, 'type'); ?>
        <?php echo $form->radioButtonList($model, 'type', Lookup::items('PeopleType'), array('separator' => ' | ')); ?>
        <?php echo $form->error($model, 'type'); ?>
    </div>
    <div class="row teacher student administration">
        <?php echo $form->labelEx($model, 'first_name'); ?>
        <?php echo $form->textField($model, 'first_name', array('size' => 80, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'first_name'); ?>
    </div>
    <div class="row teacher student administration">
        <?php echo $form->labelEx($model, 'last_name'); ?>
        <?php echo $form->textField($model, 'last_name', array('size' => 80, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'last_name'); ?>
    </div>
    <div class="row teacher student administration">
        <?php echo $form->labelEx($model, 'middle_name'); ?>
        <?php echo $form->textField($model, 'middle_name', array('size' => 80, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'middle_name'); ?>
    </div>
    <div class="row years teacher student administration">
        <span class="teacher administration"><b>Работал</b></span>
        <span class="student"><b>Учился</b></span>
        <?php echo $form->labelEx($model, 'year_1'); ?>
        <?php echo $form->dropDownList($model, 'year_1', $years); ?>
        <?php echo $form->error($model, 'year_1'); ?>

        <?php echo $form->labelEx($model, 'year_2'); ?>
        <?php echo $form->dropDownList($model, 'year_2', $years); ?>
        <?php echo $form->error($model, 'year_2'); ?>
    </div>

    <div class="row teacher student administration">
        <?php echo $form->labelEx($model, 'description'); ?>
        <?php echo CHtml::activeTextArea($model, 'description', array('rows' => 7, 'cols' => 64)); ?>
        <?php echo $form->error($model, 'description'); ?>
    </div>
    <div class="row teacher subject">
        <?php echo $form->labelEx($model_subject, 'name'); ?><br/>
        <?php echo CHtml::activeCheckBoxList($model_subject, 'name', Subject::items(0)); ?>
        <?php echo $form->error($model_subject, 'name'); ?>
    </div>

    <div class="row teacher student administration">
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
    <div class="row teacher student administration">
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
    </div>


    <div class="row buttons teacher student administration">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->