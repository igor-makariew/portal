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
        'css/vuetify.min.css',
//        'chart.js/Chart.min.css',
    ];
    public $js = [
        'js/axios.min.js',
        'js/vue.js',
        'js/vuetify.js',
    ];
    public $depends = [
//        ломает шаблон
//        'yii\web\YiiAsset',
//        'yii\bootstrap4\BootstrapAsset',
    ];
}
