<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2019-02-18
 * Time: 11:28
 */

namespace floor12\summernote;

use yii\web\AssetBundle;

class SummernoteAsset extends AssetBundle
{
    public $sourcePath = '@vendor/floor12/yii2-assets/';
    public $css = [
        'summernote.css',
        'codemirror.css',
        'monokai.css'
    ];
    public $js = [
        'summernote.conf.js',
        'summernote.js',
        'codemirror.js',
        'xml.js',
        'summernote-ru-RU.min.js',
        'summernote-cleaner.js'
    ];
}