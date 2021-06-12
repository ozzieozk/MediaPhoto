<?php

namespace mf\router;

class Router extends AbstractRouter {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function addRoute($name, $url, $ctrl, $mth, $access_level) {
        self::$routes[$url] = array($ctrl, $mth, $access_level);
        self::$aliases[$name] = $url;
    }

    public function setDefaultRoute($url) {
        self::$aliases['default'] = $url;
    }

    public function urlFor($route_name, $param_list=[]) {
        $route = self::$aliases[$route_name];
        $urlFor = $this->http_req->script_name . $route;

        if(!empty($param_list)) {
            $key = array_keys($param_list);
            $value = array_values($param_list);

            $urlFor .= '?' . $key[0] . '=' . $value[0];
        }

        return $urlFor;
    }

    public function run() {
        if(array_key_exists($this->http_req->path_info, self::$routes)) {
            $controller = self::$routes[$this->http_req->path_info][0];
            $method = self::$routes[$this->http_req->path_info][1];
            $level = self::$routes[$this->http_req->path_info][2];

            $access = new \mf\auth\Authentification();

            if($access->checkAccessRight($level)) {
                $c = new $controller();
                $c->$method();
            } else {
                $default = self::$aliases['default'];

                $controller = self::$routes[$default][0];
                $method = self::$routes[$default][1];

                $c = new $controller();
                $c->$method();
            }

        } else {
            $default = self::$aliases['default'];

            $controller = self::$routes[$default][0];
            $method = self::$routes[$default][1];

            $c = new $controller();
            $c->$method();
        }
    }

    public static function executeRoute($route) {
        $aliases = self::$aliases[$route];

        $controller = self::$routes[$aliases][0];
        $method = self::$routes[$aliases][1];

        $c = new $controller();
        $c->$method();
    }
}