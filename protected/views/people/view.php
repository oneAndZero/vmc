<?php
$this->breadcrumbs = array(
    'Люди' => '../../../search/index/type_obj/' . $model->type_obj,
    $model->last_name . ' ' . $model->first_name . ' ' . $model->middle_name
);
$this->pageTitle = $model->last_name . ' ' . $model->first_name . ' ' . $model->middle_name;
?>

<?php $this->renderPartial('_view_full', array(
    'data' => $model,
)); ?>

