<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2019-02-18
 * Time: 11:28
 */

namespace floor12\summernote;

use floor12\files\components\SimpleImage;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\validators\FileValidator;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;
use yii\widgets\InputWidget;

class Summernote extends InputWidget
{
    const IMAGE_FOLDER = '/summerfiles/';
    /** @var array */
    public $options = [];
    /** @var array */
    public $clientOptions = [];
    /** @var array */
    private $defaultOptions = ['class' => 'form-control'];

    /** Simple static method to use on controller to process uploaded files
     * @throws BadRequestHttpException
     */
    public static function summerUpload()
    {
        $instanse = UploadedFile::getInstanceByName('file');

        $validator = new FileValidator();
        $validator->extensions = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
        $validator->maxSize = 2000000;

        if (!$validator->validate($instanse))
            throw new BadRequestHttpException("Ошибка валидации изображения");

        $filename = md5(rand(999, 99999) . time()) . "." . $instanse->extension;

        $webPath = \Yii::getAlias("@web" . self::IMAGE_FOLDER);
        $rootPath = \Yii::getAlias("@webroot" . self::IMAGE_FOLDER);

        $savePath = "{$rootPath}{$filename}";

        $instanse->saveAs($savePath);


        $sizes = getimagesize($savePath);

        $mime = mime_content_type($savePath);

        if ($mime == 'image/svg+xml')
            return "{$webPath}{$filename}";

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

        return "{$webPath}{$filename}";
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->options = array_merge($this->defaultOptions, $this->options);
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

        if (sizeof($this->clientOptions))
            $this->getView()->registerJs('
            var summernoteParams = ' . Json::encode($this->clientOptions) . ';');

        $this->getView()->registerJs('jQuery( "#' . $this->options['id'] . '" ).summernote(  summernoteParams );
        ');
    }

    private function registerAssets()
    {
        $view = $this->getView();
        SummernoteAsset::register($view);
    }
}