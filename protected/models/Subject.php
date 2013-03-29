<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Дом
 * Date: 25.11.12
 * Time: 16:05
 * To change this template use File | Settings | File Templates.
 */
class Subject extends CActiveRecord
{
    /**
     * The followings are the available columns in table 'tbl_post':
     * @var integer $id
     * @var string $title
     * @var string $content
     * @var string $tags
     * @var integer $status
     * @var integer $create_time
     * @var integer $update_time
     * @var integer $author_id
     */
    private static $_items = array();

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{subject}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array();
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Id',
            'name' => 'Предмет',
        );
    }

    public static function items($type)
    {
        if (!isset(self::$_items[$type]))
            self::loadItems($type);
        return self::$_items[$type];
    }

    /**
     * Returns the item name for the specified type and code.
     * @param string the item type (e.g. 'PostStatus').
     * @param integer the item code (corresponding to the 'code' column value)
     * @return string the item name for the specified the code. False is returned if the item type or code does not exist.
     */
    public static function item($type, $code)
    {
        if (!isset(self::$_items[$type]))
            self::loadItems($type);
        return isset(self::$_items[$type][$code]) ? self::$_items[$type][$code] : false;
    }

    /**
     * Loads the lookup items for the specified type from the database.
     * @param string the item type
     */
    private static function loadItems($type)
    {
        self::$_items[$type] = array();
        $models = self::model()->findAll(array(
            'condition' => 'deleted=:deleted',
            'params' => array(':deleted' => $type),
            'order' => 'name',
        ));
        foreach ($models as $model)
            self::$_items[$type][$model->id] = $model->name;
    }

}
