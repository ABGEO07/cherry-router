<?php
/**
 * The file contains Router class
 *
 * PHP version 5
 *
 * @category Library
 * @package  Cherry
 * @author   Temuri Takalandze <takalandzet@gmail.com>
 * @license  https://github.com/ABGEO07/cherry-router/blob/master/LICENSE MIT
 * @link     https://github.com/ABGEO07/cherry-router
 */

namespace Cherry\Routing;

use Cherry\HttpUtils\Request;

/**
 * Cherry project router class
 *
 * @category Library
 * @package  Cherry
 * @author   Temuri Takalandze <takalandzet@gmail.com>
 * @license  https://github.com/ABGEO07/cherry-router/blob/master/LICENSE MIT
 * @link     https://github.com/ABGEO07/cherry-router
 */
class Router
{
    /**
     * Path to routes file
     *
     * @var string
     */
    private $_routesFile;

    /**
     * Path to controllers folder
     *
     * @var string
     */
    private $_controllersPath;

    /**
     * Application routes
     *
     * @var array
     */
    private $_routes;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->_routesFile = ROUTES_FILE;
        $this->_controllersPath = CONTROLLERS_PATH;
        $this->_routes = $this->_getRoutes();

        $this->_checkRoute();
    }

    /**
     * Get routes from routes file
     *
     * @return array Application routes
     */
    private function _getRoutes()
    {
        $originalRoutes = file_get_contents($this->_routesFile)
            or die("Unable to open routes file!");

        $originalRoutes = json_decode($originalRoutes, 1);

        $routesCacheFile = __ROOT__ . '/var/cache/router.json';

        if (file_exists($routesCacheFile)) {
            $routesCache =  json_decode(@file_get_contents($routesCacheFile), 1);
            $routesHash = md5(json_encode($originalRoutes));
            $cacheHash = $routesCache['hash'];

            if ($cacheHash !== $routesHash) {
                $this->_cacheRoutes($routesCacheFile, $originalRoutes);
            }
        } else {
            $this->_cacheRoutes($routesCacheFile, $originalRoutes);
        }

        $routes = file_get_contents($routesCacheFile);

        return json_decode($routes, 1);
    }

    /**
     * Check if current request url math with
     *
     * @return void
     */
    private function _checkRoute()
    {
        //Get request Parameters
        $request = new Request();
        $requestUrl = $request->getPath();
        $requestMethod = $request->getMethod();

        $routes = $this->_routes;
        $controllersPath = $this->_controllersPath;
        $routeFound = false;

        foreach ($routes as $k => $route) {
            if ($k == 'hash') {
                continue;
            }

            $path = $route['path'];
            $method = strtoupper($route['method']);

            $match = array();

            if ($requestMethod == $method
                && preg_match($path, $requestUrl, $match)
            ) {
                $routeFound = true;
                unset($match[0]);

                $action = explode('::', $route['action']);
                $controller = explode('\\', $action[0]);
                $controllerFile = $controllersPath . '/' .
                    $controller[count($controller) - 1] . '.php';

                //Include controller file
                if (file_exists($controllerFile)) {
                    include_once "$controllerFile";
                } else {
                    die('Controller ' . $action[0] . ' not found!');
                }

                //Get controllers new object
                $object = new $action[0]();
                $objMethod = (string)$action[1];

                if (!method_exists($object, $objMethod)) {
                    die(
                        'Method ' . $objMethod .
                        ' not found in controller ' . $action[0]
                    );
                }

                if (empty($match)) {
                    $object->$objMethod();
                } else {
                    $object->$objMethod($match);
                }
            }
        }

        if (!$routeFound) {
            die("Route {$requestUrl} Not Found!");
        }
    }

    /**
     * Cache the routes
     *
     * @param string $routesCacheFile Cache file location.
     * @param array  $routes          Application routes
     *
     * @return void
     */
    private function _cacheRoutes($routesCacheFile, $routes)
    {
        $hash = md5(json_encode($routes));

        foreach ($routes as $k => $v) {
            $routes[$k]['path'] = $this->_convertToRE($v['path']);
        }

        $routes['hash'] = $hash;

        @mkdir(dirname($routesCacheFile), 0755, true);
        @file_put_contents($routesCacheFile, json_encode($routes));
    }

    /**
     * Convert route to regular expression
     *
     * @param string $plainText Router template for converting
     *
     * @return string converted to Regular Expression route
     */
    private function _convertToRE($plainText)
    {
        $plainText = str_replace('/', "\/", $plainText);
        $lastMatch = 0;

        while ($start = strpos($plainText, '{', $lastMatch)) {
            $end = strpos($plainText, '}', $lastMatch);

            //Cut tet for replacing
            $changeMe = substr($plainText, $start, $end - $start + 1);
            $reName = substr($changeMe, 1, strlen($changeMe) - 2);
            $replace = "(?<{$reName}>[a-zA-Z0-9\_\-]+)";
            $plainText = str_replace($changeMe, $replace, $plainText);
            $lastMatch = $start + 1;
        }

        return "@^{$plainText}$@D";
    }
}