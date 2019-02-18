<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2019-02-18
 * Time: 11:28
 */

namespace floor12\summernote;

use floor12\files\components\SimpleImage;
use marqu3s\summernote\CodemirrorAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\validators\FileValidator;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

class Summernote extends \marqu3s\summernote\Summernote
{
    const IMAGE_FOLDER = '/summerfiles/';

    /** @var array */
    private $defaultOptions = ['class' => 'form-control'];
    /** @var array */
    private $defaultClientOptions = [
        'height' => 200,
        'codemirror' => [
            'theme' => 'monokai'
        ]
    ];
    /** @var array */
    public $options = [];
    /** @var array */
    public $clientOptions = [];

    /**
     * @inheritdoc
     */

    public static function summerUpload()
    {
        $instanse = UploadedFile::getInstanceByName('file');

        $validator = new FileValidator();
        $validator->extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $validator->maxSize = 2000000;

        if (!$validator->validate($instanse))
            throw new BadRequestHttpException("Ошибка валидации изображения");

        $filename = md5(rand(999, 99999) . time()) . "." . $instanse->extension;

        $webPath = \Yii::getAlias("@web" . self::IMAGE_FOLDER);
        $rootPath = \Yii::getAlias("@webroot" . self::IMAGE_FOLDER);

        $savePath = "{$rootPath}{$filename}";

        $instanse->saveAs($savePath);


        $sizes = getimagesize($savePath);

        if ($sizes[0] > 1000) {
            $img = new SimpleImage();
            $img->load($savePath);
            $img->resizeToWidth(1000);
            $img->save($savePath);
        }


        if ($sizes[1] > 800) {
            $img = new SimpleImage();
            $img->load($savePath);
            $img->resizeToHeight(800);
            $img->save($savePath);
        }

        echo "{$webPath}{$filename}";
    }


    public function init()
    {
        $this->options = array_merge($this->defaultOptions, $this->options);
        $this->clientOptions = array_merge($this->defaultClientOptions, $this->clientOptions);
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerAssets();


        echo $this->hasModel()
            ? Html::activeTextarea($this->model, $this->attribute, $this->options)
            : Html::textarea($this->name, $this->value, $this->options);

        $callbacks = $this->getExtendsParams('callbacks');
        $buttons = $this->getExtendsParams('buttons');
        $modules = $this->getExtendsParams('modules');

        $clientOptions = empty($this->clientOptions)
            ? null
            : Json::encode($this->clientOptions);

        $this->getView()->registerJs('jQuery( "#' . $this->options['id'] . '" ).summernote(  summernoteParams );
        ');

    }

    private function registerAssets()
    {
        $view = $this->getView();

        SummernoteAsset::register($view);

    }

    private function getExtendsParams($param)
    {
        $result = '';
        foreach (ArrayHelper::remove($this->clientOptions, $param, []) as $val => $key) {
            $result .= (empty($result) ? '' : ',') . $val . ': ' . $key;
        }
        return $result;
    }

}
