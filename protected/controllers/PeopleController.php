<?php

class PeopleController extends Controller
{

    public $layout = 'column3';
    protected $type = 1;
    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    private $_model;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow all users to access 'index' and 'view' actions.
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated users to access all actions
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     */
    public function actionView()
    {
        $people = $this->loadModel();

        $this->render('view', array(
            'model' => $people,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile('/js/add_photo.js');
        $people = new People;
        $model_subject = new Subject;

        if (isset($_POST['People'])) {
            $people->attributes = $_POST['People'];
            $people->type_obj = $this->type;
            if ($people->save()) {
                $files = CUploadedFile::getInstances($people, 'obj_file');
                $this->save_files($files, $people);

                if (isset($_POST['Subject']) and !empty($_POST['Subject']['name'])) {
                    foreach ($_POST['Subject']['name'] as $subject) {
                        $model_peop_subj = new People_subject;
                        $model_peop_subj->setAttribute('id_subject', $subject);
                        $model_peop_subj->setAttribute('id_people', $people->id);
                        $model_peop_subj->save();
                    }
                }
                $this->redirect(array('view', 'id' => $people->id));
            }
        }

        $this->render('create', array(
            'model' => $people,
            'model_subject' => $model_subject,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpdate()
    {
        $model = $this->loadModel();
        $model_subject = new Subject;


        if (isset($_POST['People'])) {
            $model->attributes = $_POST['People'];
            $model->obj_file = CUploadedFile::getInstance($model, 'obj_file');
            if (is_object($model->obj_file)) {
                $file_name = md5(time() . $model->obj_file->getName());
                $model->obj_file->setName($file_name . '.' . $model->obj_file->getExtensionName());
                $url = Yii::app()->basePath . '\\..\\files\\' . $model->obj_file;
                $model->obj_file->saveAs($url);
            }
            if ($model->save()) {
                if (isset($_POST['Subject']) and !empty($_POST['Subject']['name'])) {
                    foreach ($_POST['Subject']['name'] as $subject) {
                        $model_peop_subj = new People_subject;
                        $model_peop_subj->setAttribute('id_subject', $subject);
                        $model_peop_subj->setAttribute('id_people', $model->id);
                        $model_peop_subj->save();
                    }
                }
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'model_subject' => $model_subject,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     */
    public function actionDelete()
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel()->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria(array(
            'condition' => 'type_obj = 1',
            'order' => 'update_time DESC',
        ));
        if (isset($_GET['tag']))
            $criteria->addSearchCondition('tags', $_GET['tag']);

        $dataProvider = new CActiveDataProvider('People', array(
            'pagination' => array(
                'pageSize' => Yii::app()->params['postsPerPage'],
            ),
            'criteria' => $criteria,
        ));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new People('search');
        if (isset($_GET['People']))
            $model->attributes = $_GET['People'];
        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Suggests tags based on the current user input.
     * This is called via AJAX when the user is entering the tags input.
     */
    public function actionSuggestTags()
    {
        if (isset($_GET['q']) && ($keyword = trim($_GET['q'])) !== '') {
            $tags = Tag::model()->suggestTags($keyword);
            if ($tags !== array())
                echo implode("\n", (array)$tags);
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     */
    public function loadModel()
    {
        if ($this->_model === null) {
            $condition = 'type_obj = ' . $this->type;
            if (isset($_GET['id'])) {
                if (Yii::app()->user->isGuest)
                    //$condition='status='.Post::STATUS_PUBLISHED.' OR status='.Post::STATUS_ARCHIVED;
                    $condition = '';
                else
                    $condition = '';
                $this->_model = People::model()->with('author', 'subjects', 'files')->findByPk($_GET['id']);
            }
            if ($this->_model === null)
                throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $this->_model;
    }

    /**
     * Creates a new comment.
     * This method attempts to create a new comment based on the user input.
     * If the comment is successfully created, the browser will be redirected
     * to show the created comment.
     * @param Post the post that the new comment belongs to
     * @return Comment the comment instance
     */
    protected function newComment($post)
    {
        $comment = new Comment;
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'comment-form') {
            echo CActiveForm::validate($comment);
            Yii::app()->end();
        }
        if (isset($_POST['Comment'])) {
            $comment->attributes = $_POST['Comment'];
            if ($post->addComment($comment)) {
                if ($comment->status == Comment::STATUS_PENDING)
                    Yii::app()->user->setFlash('commentSubmitted', 'Thank you for your comment. Your comment will be posted once it is approved.');
                $this->refresh();
            }
        }
        return $comment;
    }

}


