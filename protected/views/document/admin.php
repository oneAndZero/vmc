<?php
$this->breadcrumbs = array(
    'Просмотр\Редактирование\Удаление',
);
?>
<h1>Просмотр\Редактирование\Удаление</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'title',
            'type' => 'raw',
            'value' => 'CHtml::link(CHtml::encode($data->title), $data->url)'
        ),
        array(
            'name' => 'type',
            'value' => 'Lookup::item("DocumentType",$data->type)',
            'filter' => Lookup::items('DocumentType'),
        ),
        array(
            'name' => 'create_time',
            'type' => 'raw',
            'filter' => false,
            'value' => 'date("d.m.Y",strtotime($data->create_time))'
        ),
        array(
            'class' => 'CButtonColumn',
        ),
    ),
)); ?>
