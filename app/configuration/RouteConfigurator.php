<?php
/**
 * Created by PhpStorm.
 * User: mamedov
 * Date: 13.09.2018
 * Time: 19:09
 */

namespace app\configuration;


use app\models\User;
use core\system\Auth;
use core\system\exceptions\RouterException;
use core\system\route\ParametricalRoute;
use core\system\route\Route;
use core\system\Router;
use core\system\Url;

class RouteConfigurator
{
    public static function routerConfigure(){
        Router::instance()->addRoute(new Route("","main","index"));


        $admin_filter = function (){
            return Auth::instance()->isAuth() && Auth::instance()->getCurrentUser(User::class)->hasRole("admin");
        };


        //auth routes
        Router::instance()->addRoute(new Route("register","auth","register"));
        Router::instance()->addRoute(new Route("login","auth","login"));
        Router::instance()->addRoute(new Route("signup","auth","signup"));
        Router::instance()->addRoute(new Route("signin","auth","signin"));
        Router::instance()->addRoute(new Route("logout","auth","logout"));


        //posts
        Router::instance()->addRoute(new Route("posts/add/handle","post","add_handle"));
        Router::instance()->addRoute(new ParametricalRoute("posts/{id}","post","view"));
        Router::instance()->addRoute(new ParametricalRoute("posts/category/{catid}/{?page}","post","category"));

        //admin
        Router::instance()->addRoute((new Route("admin","admin","index"))->setFilter($admin_filter));
        Router::instance()->addRoute((new Route("admin/users","admin","users"))->setFilter($admin_filter));
        Router::instance()->addRoute((new Route("admin/users/addrole","admin","addrole"))->setFilter($admin_filter));
        Router::instance()->addRoute((new Route("admin/users/deleterole","admin","delrole"))->setFilter($admin_filter));
        Router::instance()->addRoute((new Route("admin/users/editlogin","admin","editlogin"))->setFilter($admin_filter));

        Router::instance()->addRoute(new Route("404","c404","index"));
    }

    public static function onRouterError(RouterException $e){
        //echo $e->getMessage();
        Url::redirect("/404");
    }
}