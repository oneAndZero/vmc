<?php
$this->breadcrumbs = array(
    'Мероприятия' => '',
    $model->title,
);
$this->pageTitle = $model->title;
?>

<?php $this->renderPartial('_view_full', array(
    'data' => $model,
)); ?>

