<?php
$this->breadcrumbs = array('Люди');
?>
<h1>Люди</h1>
<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'model_subject' => $model_subject,
)); ?>