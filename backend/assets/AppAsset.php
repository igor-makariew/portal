<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
//        'chart.js/Chart.min.css',
    ];
    public $js = [
    ];
    public $depends = [
//        ломает шаблон
//        'yii\web\YiiAsset',
//        'yii\bootstrap4\BootstrapAsset',
    ];
}
