<?php
$this->breadcrumbs = array(
    'Мероприятия' => '../../../search/index/type_obj/'.$model->type_obj,
    $model->title,
);
$this->pageTitle = $model->title;
?>

<?php $this->renderPartial('_view_full', array(
    'data' => $model,
)); ?>

