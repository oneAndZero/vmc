<?php
$this->breadcrumbs = array('Документы');
?>
<h1>Документ</h1>
<?php echo $this->renderPartial('_form', array(
    'model' => $model
)); ?>