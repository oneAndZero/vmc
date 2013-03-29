<?php

class File extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{files}}';
    }

    public function relations()
    {
        return array( //'post' => array(self::BELONGS_TO, 'Post', 'post_id'),
        );
    }

    public function rules()
    {
        return array(
            array('name', 'required'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'Id',
            'name' => 'Название',
            'type' => 'Тип',
            'extensions' => 'Расширение',
        );
    }

    protected function beforeSave()
    {
        if (parent::beforeSave()) {
            if ($this->isNewRecord)
                return true;
        } else
            return false;
    }

    public function behaviors()
    {
        return array(
            'galleria' => array(
                'class' => 'application.extensions.galleria.GalleriaBehavior',
                'image' => 'name', //required, will be image name of src
                'imagePrefix' => 'folder', //optional, not required
                'description' => 'description', //optional, not required
                'title' => 'name', //optional, not required
            ),
        );
    }
}