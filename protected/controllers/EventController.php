<?php

class EventController extends Controller
{
    protected $type = 3;
    public $layout = 'column3';
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


    public function actionView()
    {
        $event = $this->loadModel();

        $this->render('view', array(
            'model' => $event,
        ));
    }

    public function actionCreate()
    {
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile('/js/add_photo.js');
        $event = new Event;
        if (isset($_POST['Event'])) {
            $event->attributes = $_POST['Event'];
            $event->date_event = date('Y-m-d',strtotime($_POST['Event']['date_event']));
            $event->type_obj = $this->type;
            if ($event->save()) {
                $files = CUploadedFile::getInstances($event, 'obj_file');
                $this->save_files($files, $event);
                $this->redirect(array('view', 'id' => $event->id));
            }
        }
        $this->render('create', array(
            'model' => $event
        ));
    }

    public function actionUpdate()
    {
        $model = $this->loadModel();

        if (isset($_POST['Event'])) {
            $model->attributes = $_POST['Event'];
            $model->file = CUploadedFile::getInstance($model, 'file');
            if (is_object($model->file)) {
                $model->file->setName(date('dmyHis') . '_' . $this->rus2translit($model->file->getName()));
                $url = Yii::app()->basePath . '\\..\\files\\' . $model->file;
                $model->file->saveAs($url);
            }
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
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


    public function actionIndex()
    {
        $criteria = new CDbCriteria(array(
            'condition' => 'type_obj = ' . $this->type,
            'order' => 'update_time DESC',
        ));

        if (isset($_GET['tag']))
            $criteria->addSearchCondition('tags', $_GET['tag']);

        Event::model()->with('author', 'files')->findByPk($_GET['id']);
        $dataProvider = new CActiveDataProvider('Event', array(
            'pagination' => array(
                'pageSize' => Yii::app()->params['postsPerPage'],
            ),
            'criteria' => $criteria,
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionAdmin()
    {
        $model = new Event('search');
        // echo "<pre>";
        //  print_r($model);
        if (isset($_GET['Event']))
            $model->attributes = $_GET['Event'];
        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionSuggestTags()
    {
        if (isset($_GET['q']) && ($keyword = trim($_GET['q'])) !== '') {
            $tags = Tag::model()->suggestTags($keyword);
            if ($tags !== array())
                echo implode("\n", (array)$tags);
        }
    }

    public function loadModel()
    {
        if ($this->_model === null) {
            $condition = 'type_obj = ' . $this->type;
            if (isset($_GET['id'])) {
                if (Yii::app()->user->isGuest)
                    //$condition='status='.Post::STATUS_PUBLISHED.' OR status='.Post::STATUS_ARCHIVED;
                    $condition .= '';
                else
                    $condition .= '';
                $this->_model = Event::model()->with('author', 'files')->findByPk($_GET['id'], $condition);

            }
            if ($this->_model === null)
                throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $this->_model;
    }


}
