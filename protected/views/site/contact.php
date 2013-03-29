<?php
$this->pageTitle = Yii::app()->name . ' - Contact Us';
$this->breadcrumbs = array(
    'Контакты',
);
?>

<h1>Контакты</h1>

<?php if (Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p>
    If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
</p>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm'); ?>

    <p class="note"><span class="required">*</span> обязательные поля</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'subject'); ?>
        <?php echo $form->textField($model, 'subject', array('size' => 60, 'maxlength' => 128)); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'body'); ?>
        <?php echo $form->textArea($model, 'body', array('rows' => 6, 'cols' => 50)); ?>
    </div>

    <?php if (extension_loaded('gd')): ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'verifyCode'); ?>
        <div>
            <?php $this->widget('CCaptcha'); ?>
            <?php echo $form->textField($model, 'verifyCode'); ?>
        </div>
        <div class="hint"></div>
    </div>
    <?php endif; ?>

    <div class="row submit">
        <?php echo CHtml::submitButton('Submit'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>