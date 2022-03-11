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
        'https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"',
        'https://fonts.googleapis.com/css?family=Alex+Brush',
        'css/materialdesignicons.min.css',
        'css/fonts.googleapis.css',
        'css/vuetify.min.css',
        'css/font-awesome.min.css',
    ];
    public $js = [
        'js/jquery.min.js',
        'js/jquery-migrate-3.0.1.min.js',
        'js/popper.min.js',
        'js/bootstrap.min.js',
        'js/jquery.easing.1.3.js',
        'js/jquery.waypoints.min.js',
        'js/jquery.stellar.min.js',
        'js/owl.carousel.min.js',
        'js/jquery.magnific-popup.min.js',
        'js/aos.js',
        'js/jquery.animateNumber.min.js',
        'js/bootstrap-datepicker.js',
        'js/scrollax.min.js',
        'js/main.js',
        'js/axios.min.js',
        'js/vue.js',
        'js/vuetify.js',



    ];
    public $depends = [
//        ломает шаблон
//        'yii\web\YiiAsset',
//        'yii\bootstrap4\BootstrapAsset',
    ];
//    public $jsOptions = [
//        'position' => \yii\web\View::POS_HEAD
//    ];
}
