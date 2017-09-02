<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'bootstrap-rtl/css/bootstrap.css',
        'fa/css/font-awesome.css'
    ];
    public $js = [
        'bootstrap-rtl/js/bootstrap.js',
        'js/site.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
