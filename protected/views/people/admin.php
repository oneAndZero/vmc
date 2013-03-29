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
            'name' => 'last_name',
            'type' => 'raw',
            'value' => 'CHtml::link(CHtml::encode($data->first_name . " " .  $data->last_name . " " .  $data->middle_name), $data->url)'
        ),
        array(
            'name' => 'type',
            'value' => 'Lookup::item("PeopleType",$data->type)',
            'filter' => Lookup::items('PeopleType'),
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
