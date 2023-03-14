<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use backend\widgets\updateAvatars;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?= Html::img(Yii::$app->urlManagerBackend->baseUrl.'/images/UploadFiles/'. updateAvatars::widget(),
                    ['alt' => 'User Image', 'class' => 'img-circle avatar-image',])?>
            </div>
            <div class="pull-left info">
                <p></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div><?= $user['username']?>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree', 'id' => 'adminMenu'],
                'items' => [
                    ['label' => 'Menu admin', 'options' => ['class' => 'header']],
                    ['label' => 'Users', 'icon' => 'users', 'url' => ['/users/index']],
                    ['label' => 'Countries', 'icon' => 'flag', 'url' => ['/countries/index']],
                    ['label' => 'Resorts', 'icon' => 'bed', 'url' => ['resorts/index']],
                    ['label' => 'Exel', 'icon' => 'file', 'url' => ['/exel/index']],
                    ['label' => 'Mailing List', 'icon' => 'envelope', 'url' => ['/mailing/index']],
                    ['label' => 'Api News', 'icon' => 'newspaper-o', 'url' => ['/news/index']],
                    ['label' => 'Chat', 'icon' => 'twitter', 'url' => ['/chat/index']],
                    ['label' => 'WebSocket', 'icon' => 'file', 'url' => ['/socket/index']],
                    ['label' => 'Events', 'icon' => 'file', 'url' => ['/ev/index']],
                    ['label' => 'File manager', 'icon' => 'file', 'url' => ['/rabbit-send/index']],
                    ['label' => 'Pdf send', 'icon' => 'file', 'url' => ['/rabbitmq/index']],
                    [
                        'label' => 'Games',
                        'icon' => 'gamepad',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Memory', 'icon' => 'gamepad', 'url' => ['/game/index']],
                            ['label' => '2048', 'icon' => 'gamepad', 'url' => ['/game-2048/index']],
                        ]
                    ],
                    ['label' => 'Module Settings', 'icon' => 'file', 'url' => ['settings/post-auto/index']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => \Yii::$app->user->isGuest],
                    [
                        'label' => 'Some tools',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
