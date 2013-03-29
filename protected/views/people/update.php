<?php
$this->breadcrumbs = array(
    'Обновить',
);
?>

<h1>Обновить</h1>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'model_subject' => $model_subject,
)); ?>