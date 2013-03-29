<?php
$this->breadcrumbs = array('Мероприятия');
?>
<h1>Мероприятия</h1>
<?php echo $this->renderPartial('_form', array(
    'model' => $model,
)); ?>