<?php

class Object extends CActiveRecord
{
    /**
     * The followings are the available columns in table 'tbl_post':
     * @var integer $id
     * @var string $content
     * @var string $tags
     * @var integer $status
     * @var integer $create_time
     * @var integer $update_time
     * @var integer $author_id
     */
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_ARCHIVED = 3;

    private $_oldTags;
    public $image;


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
            array('last_name, first_name, year_1, year_2', 'required'),
            array('middle_name,description', 'safe'),
            array('type', 'in', 'range' => array(1, 2, 3)),
            array('tags', 'match', 'pattern' => '/[А-я\w\s,]*/', 'message' => 'Ошибка в символах тегов'),
            array('tags', 'normalizeTags'),
            array('obj_file', 'file', 'types' => 'jpg, gif, png', 'allowEmpty' => true),
            array('last_name, status', 'safe', 'on' => 'search'),
        );
    }


    public function relations()
    {
        return array(
            'author' => array(self::BELONGS_TO, 'User', 'author_id'),
            'subjects' => array(self::MANY_MANY, 'Subject', 'tbl_people_subject(id_people, id_subject)'),
            'files' => array(self::MANY_MANY, 'File', 'tbl_objects_files(id_object, id_file)'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'Id',
            'last_name' => 'Имя',
            'first_name' => 'Фамилия',
            'middle_name' => 'Отчество',
            'description' => 'Описание',
            'year_1' => 'с:',
            'year_2' => 'по:',
            'type' => 'Тип: ',
            //'subject' => 'Предметы',
            'tags' => 'Теги',
            'obj_file' => 'Изображение',
        );
    }

    public function getUrl()
    {
        if ($this->type_obj == 1)
            $url = 'people';
        elseif ($this->type_obj == 2)
            $url = 'document'; elseif ($this->type_obj == 3)
            $url = 'event';
        return Yii::app()->createUrl($url . '/view', array('id' => $this->id));
    }

    public function getTagLinks()
    {
        $links = array();
        foreach (Tag::string2array($this->tags) as $tag)
            $links[] = CHtml::link(CHtml::encode($tag), array('search/index', 'tag' => $tag));
        return $links;
    }

    public function normalizeTags($attribute, $params)
    {
        $this->tags = Tag::array2string(array_unique(Tag::string2array($this->tags)));
    }

    public function addComment($comment)
    {
        if (Yii::app()->params['commentNeedApproval'])
            $comment->status = Comment::STATUS_PENDING;
        else
            $comment->status = Comment::STATUS_APPROVED;
        $comment->post_id = $this->id;
        return $comment->save();
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

        $criteria->compare('last_name', $this->last_name, true);

        $criteria->compare('type', $this->type);

        return new CActiveDataProvider('People', array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id, update_time DESC',
            ),
        ));
    }
}