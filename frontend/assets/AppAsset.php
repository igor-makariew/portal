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
        'css/open-iconic-bootstrap.min.css',
        'css/animate.css',
        'css/owl.carousel.min.css',
        'css/owl.theme.default.min.css',
        'css/magnific-popup.css',
        'css/aos.css',
        'css/ionicons.min.css',
        'css/bootstrap-datepicker.css',
        'css/jquery.timepicker.css',
        'css/flaticon.css',
        'css/icomoon.css',
        'css/style.css',
        'https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"',
        'https://fonts.googleapis.com/css?family=Alex+Brush',
        'css/materialdesignicons.min.css',
        'css/fonts.googleapis.css',
        'css/vuetify.min.css',
        'css/font-awesome.min.css',
        'css/preloder.css'
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
//        'js/jquery.timepicker.min.js',  // отсутствует
        'js/scrollax.min.js',
        'https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false',
//        'js/google-map.js', // проверить
        'js/main.js',
        'js/vue.js',
        'js/axios.min.js',
        'js/vuetify.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
