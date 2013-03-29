<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to 'column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = 'column1';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    protected function save_files($files, $obj)
    {
        foreach ($files as &$file) {
            if (is_object($file)) {
                $file_name = $this->rus2translit($file->getName());
                $file_obj = new File;
                $file_obj->name = $file_name;
                $file_obj->type = $file->getType();
                $file_obj->extension = $file->getExtensionName();

                $file->setName($file_name);

                $folder_obj = $this->createFolder(Yii::getPathOfAlias('application.data.files.' . $obj->id));
                $folder_file = md5(microtime());
                $folder = $this->createFolder($folder_obj . '/' . $folder_file);
                $folder_file = $obj->id . '/' . $folder_file;
                $file_obj->folder = $folder_file;

                if ($file->saveAs($folder . '/' . $file_name)) {
                    if ($file_obj->save()) {
                        $model_obj_file = new Object_file;
                        $model_obj_file->setAttribute('id_object', $obj->id);
                        $model_obj_file->setAttribute('id_file', $file_obj->id);
                        $model_obj_file->save();
                    }
                }
            }
        }
    }

    private function createFolder($folder)
    {
        if (is_dir($folder) == false) {
            mkdir($folder, 0777);
            chmod($folder, 0777);
        }
        return $folder;
    }

    protected function rus2translit($string)
    {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
            'Ь' => '', 'Ы' => 'Y', 'Ъ' => '',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }
}