<?php


namespace common\traits;

use Yii;

trait BreadcrumbsTrait
{
    /**
     * @property array $session;
     */
    private $session;
    public $routes;
    public $keys;
    public $nameLink;
    public $urls;

    /**
     * сессия breadcrumbs
     */
    public function sessionBreadcrumbs()
    {
        $this->session = Yii::$app->session;
        $this->session->open();
        if(!$this->session->has('breadcrumbs')) {
            $this->session->set('breadcrumbs', []);
            $this->routes = [];
        } else {
            $this->routes = $this->session->get('breadcrumbs');
        }
    }

    /**
     * сессия url
     */
    public function sessionUrl()
    {
        $this->session = Yii::$app->session;
        $this->session->open();
        if(!$this->session->has('urls')) {
            $this->session->set('urls', []);
            $this->routes = [];
        } else {
            $this->urls = $this->session->get('urls');
        }
    }



    public $breadcrumbs = [
        'homeLink' => [
            'label' => 'HOME',
            'url' => ['/site/index'],
            'template' => "<li class='breadcrumb-item'><a>{link}</a></li>",
        ],
        'links' => [
//            [
//                'label' => 'Tours',
//                'url' => ['/resorts/index'],
//                'template' => "<li class='breadcrumb-item'><a>{link}</a></li>",
//            ],
//            [
//                'label' => 'Country',
//                'template' => "<li class='breadcrumb-item active'><a>{link}</a></li>",
//            ],
        ]
    ];

    /**
     * получение клечей массива
     *
     * @return array
     */
    public function getKeys() {
        return $this->keys = array_keys($this->breadcrumbs);
    }

    /**
     * получение имени контроллера
     *
     * @param $route
     * @return string
     */
    public function getNameController($route) {
        $names = explode('/', $route);
        return $this->nameLink = strtoupper($names[0]);
    }

    /**
     * запись крошек в сессию
     *
     * @param $route
     */
    public function addRoute($route)
    {
        array_push($this->routes, $route);
        $this->session->set('breadcrumbs', $this->routes);
    }

    /**
     * получение крошек из сессии
     *
     * @return mixed
     */
    public function getRoutes()
    {
        return $this->session->get('breadcrumbs');
    }

    /**
     * удаление повторяющихся крошек в сессии
     *
     * @param $route
     * @return bool
     *
     */
    public function removeRouteInSession($route)
    {
        $this->routes = $this->getRoutes();
        if(in_array($route, $this->routes)) {
            $key = array_search($route, $this->routes);
            if(gettype($key) == "integer") {
                array_splice($this->routes, ($key + 1));
                $this->session->set('breadcrumbs', $this->routes);
                return true;
            }
        }

        return false;
    }

    /**
     * поиск не уникальных крошек в сессии
     *
     * @param $routes
     * @param $route
     * @return bool
     */
    public function findRoute($routes, $route)
    {
        foreach ($routes as $routeValue) {
            if($routeValue == $route) {
                return true;
            }
        }

        return false;
    }

    /**
     * создание хлебных крошек
     *
     * @param $routes
     * @param $route
     * @return array
     */
    public function createBreadcrumbs($routes, $route)
    {
        $this->getKeys();
        if(!$this->findRoute($routes, $route)) {
            $this->addRoute($route);
        }
        if($this->findRoute($routes, $route)) {
            $this->removeRouteInSession($route);
        }


        return $this->getBreadcrumbs($route);
    }

    /**
     *
     *
     * @param $route
     * @return array
     */
    public function getBreadcrumbs($route)
    {
        for ($i = 0; $i < count($this->getRoutes()); $i++) {
            if(in_array('site/index', $this->routes)) {
                $this->session->set('breadcrumbs', []);
                $this->breadcrumbs['links'][] = [];
            }

            if(in_array($route, $this->getUrls())) {
                $this->getNameController($route);
                $this->breadcrumbs['links'][] = [
                    'label' => $this->nameLink,
                    'template' => "<li class='breadcrumb-item'><a>{link}</a></li>",
                ];
                return $this->breadcrumbs;
            }


            if(!$this->findRoute($this->breadcrumbs['links'], $route)) {
                $this->getNameController($this->getRoutes()[$i]);
                if((count($this->getRoutes()) - 1) != $i) {
                    $this->breadcrumbs['links'][] = [
                        'label' => $this->nameLink,
                        'url' => [$this->getRoutes()[$i]],
                        'template' => "<li class='breadcrumb-item'><a>{link}</a></li>",
                    ];
                } else {
                    $this->breadcrumbs['links'][] = [
                        'label' => $this->nameLink,
                        'template' => "<li class='breadcrumb-item'><a>{link}</a></li>",
                    ];
                }
            }
        }
        return $this->breadcrumbs;
    }

    /**
     * получение данных из сессии urls
     *
     * @return mixed
     */
    public function getUrls()
    {
        return $this->session->get('urls');
    }

    /**
     * запись данных urls в сессию
     *
     * @param $urls
     */
    public function setUrls($urls)
    {
        $this->session->set('urls', $urls);
    }

    /**
     * Очистка сессии
     *
     * @param string $param
     */
    public function clearSession(string $param)
    {
        $this->session->set($param, []);
    }
}