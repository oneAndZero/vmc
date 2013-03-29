<?php

class EventController extends Controller
{

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
//        $obj = $this->loadModel();
//
//        $this->render('view', array(
//            'model' => $obj,
//        ));
    }

    public function actionUpdate()
    {
        $model = $this->loadModel();

        if (isset($_POST['Object'])) {
            $model->attributes = $_POST['Object'];
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
            'condition' => 'type_obj = 3',
            'order' => 'update_time DESC',
        ));

        if (isset($_GET['tag']))
            $criteria->addSearchCondition('tags', $_GET['tag']);


        $dataProvider = new CActiveDataProvider('Object', array(
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
        $model = new Object('search');
        // echo "<pre>";
        //  print_r($model);
        if (isset($_GET['Object']))
            $model->attributes = $_GET['Object'];
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
            $condition = 'type_obj = 3';
            if (isset($_GET['id'])) {
                if (Yii::app()->user->isGuest)
                    //$condition='status='.Post::STATUS_PUBLISHED.' OR status='.Post::STATUS_ARCHIVED;
                    $condition .= '';
                else
                    $condition .= '';
                $this->_model = Object::model()->with('author', 'subjects', 'files')->findByPk($_GET['id'], $condition);
            }
            if ($this->_model === null)
                throw new CHttpException(404, 'The requested page does not exist . ');
        }
        return $this->_model;
    }


}
