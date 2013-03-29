<?php if (!empty($_GET['tag'])): ?>
<h1>Поиск по тегу <i><?php echo CHtml::encode($_GET['tag']); ?></i></h1>
<?php endif; ?>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => 'switch',
    'template' => "{items}\n{pager}",
)); ?>
