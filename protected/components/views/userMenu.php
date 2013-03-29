<div class="menu_content">
    <span class="menu_title">Люди</span>
    <ul>
        <li><?php echo CHtml::link('Добавить человека', array('people/create')); ?></li>
        <li><?php echo CHtml::link('Список людей', array('search/index/type_obj/1')); ?></li>
    </ul>

    <span class="menu_title">Документы</span>
    <ul>
        <li><?php echo CHtml::link('Добавить документ', array('document/create')); ?></li>
        <li><?php echo CHtml::link('Список документов', array('search/index/type_obj/2')); ?></li>
    </ul>
    <span class="menu_title">Мероприятия</span>
    <ul>
        <li><?php echo CHtml::link('Добавить мероприятие', array('event/create')); ?></li>
        <li><?php echo CHtml::link('Список мероприятий', array('search/index/type_obj/3')); ?></li>
    </ul>

    <span class="menu_title">Общее</span>
    <ul>
        <li><?php echo CHtml::link('Выход', array('site/logout')); ?></li>
    </ul>
</div>