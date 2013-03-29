<?php

class Event extends CActiveRecord
{

    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_ARCHIVED = 3;

    private $_oldTags;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{objects}}';
    }


    public function rules()
    {
        return array(
            array('title', 'required'),
            array('title,date_event,place_event', 'match', 'pattern' => '/[А-я\w\s,\-]+/', 'message' => 'Недопустимые символы в названии'),
            array('description', 'match', 'pattern' => '/[А-я\w\s,\-\#\$\%\^\&\*\(\)]*/', 'message' => 'Недопустимые символы в названии'),
            array('tags', 'match', 'pattern' => '/[А-я\w\s,]*/', 'message' => 'Ошибка в символах тегов'),
            array('tags', 'normalizeTags'),
        );
    }

    public function relations()
    {
        return array(
            'author' => array(self::BELONGS_TO, 'User', 'author_id'),
            'files' => array(self::MANY_MANY, 'File', 'tbl_objects_files(id_object, id_file)'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'Id',
            'title' => 'Название',
            'description' => 'Описание',
            'tags' => 'Теги',
            'place_event' => 'Место',
            'date_event' => 'Дата',
            'obj_file' => 'Файл',
        );
    }

    public function getUrl()
    {
        return Yii::app()->createUrl('document/view', array(
            'id' => $this->id
        ));
    }

    public function getTagLinks()
    {
        $links = array();
        foreach (Tag::string2array($this->tags) as $tag)
            $links[] = CHtml::link(CHtml::encode($tag), array('document/index', 'tag' => $tag));
        return $links;
    }

    public function normalizeTags($attribute, $params)
    {
        $this->tags = Tag::array2string(array_unique(Tag::string2array($this->tags)));
    }

    protected function afterFind()
    {
        parent::afterFind();
        $this->_oldTags = $this->tags;
    }

    protected function beforeSave()
    {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->create_time = $this->update_time = date('Y-m-d H:i:s');
                $this->author_id = Yii::app()->user->id;
            } else
                $this->update_time = date('Y-m-d H:i:s');
            return true;
        } else
            return false;
    }

    protected function afterSave()
    {
        parent::afterSave();
        Tag::model()->updateFrequency($this->_oldTags, $this->tags);
    }

    protected function afterDelete()
    {
        parent::afterDelete();
        Comment::model()->deleteAll('post_id=' . $this->id);
        Tag::model()->updateFrequency($this->tags, '');
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('title', $this->title, true);
        $criteria->compare('type', $this->type);

        return new CActiveDataProvider('Event', array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id, update_time DESC',
            ),
        ));
    }
}