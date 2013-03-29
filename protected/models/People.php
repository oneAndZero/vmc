<?php

class People extends CActiveRecord
{

    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_ARCHIVED = 3;

    private $_oldTags;
    public $image;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{objects}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
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

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'author' => array(self::BELONGS_TO, 'User', 'author_id'),
            'subjects' => array(self::MANY_MANY, 'Subject', 'tbl_people_subject(id_people, id_subject)'),
            'files' => array(self::MANY_MANY, 'File', 'tbl_objects_files(id_object, id_file)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
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

    /**
     * @return string the URL that shows the detail of the post
     */
    public function getUrl()
    {
        return Yii::app()->createUrl('people/view', array(
            'id' => $this->id
        ));
    }

    /**
     * @return array a list of links that point to the post list filtered by every tag of this post
     */
    public function getTagLinks()
    {
        $links = array();
        foreach (Tag::string2array($this->tags) as $tag)
            $links[] = CHtml::link(CHtml::encode($tag), array('people/index', 'tag' => $tag));
        return $links;
    }

    /**
     * Normalizes the user-entered tags.
     */
    public function normalizeTags($attribute, $params)
    {
        $this->tags = Tag::array2string(array_unique(Tag::string2array($this->tags)));
    }

    /**
     * Adds a new comment to this post.
     * This method will set status and post_id of the comment accordingly.
     * @param Comment the comment to be added
     * @return boolean whether the comment is saved successfully
     */
    public function addComment($comment)
    {
        if (Yii::app()->params['commentNeedApproval'])
            $comment->status = Comment::STATUS_PENDING;
        else
            $comment->status = Comment::STATUS_APPROVED;
        $comment->post_id = $this->id;
        return $comment->save();
    }

    /**
     * This is invoked when a record is populated with data from a find() call.
     */
    protected function afterFind()
    {
        parent::afterFind();
        $this->_oldTags = $this->tags;
    }

    /**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
     */
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

    /**
     * This is invoked after the record is saved.
     */
    protected function afterSave()
    {
        parent::afterSave();
        Tag::model()->updateFrequency($this->_oldTags, $this->tags);
    }

    /**
     * This is invoked after the record is deleted.
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        Comment::model()->deleteAll('post_id=' . $this->id);
        Tag::model()->updateFrequency($this->tags, '');
    }

    /**
     * Retrieves the list of posts based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the needed posts.
     */
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