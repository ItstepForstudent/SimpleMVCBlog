<?php
/**
 * Created by PhpStorm.
 * User: mamedov
 * Date: 27.09.2018
 * Time: 19:32
 */

namespace core\system;


use core\system\hasher\PassHasher;
use core\system\models\User;

class Auth
{

    private static $inst = NULL;

    public static function instance()
    {
        return self::$inst === NULL ? self::$inst = new self() : self::$inst;
    }

    private function __construct(){}

    public function login($login,$pass,$save=false){
        $user = User::where("login","?")->first([$login]);
        if($user->isEmpty()) return false;
        if(!PassHasher::instance()->validateHash($pass,$user->pass)) return false;
        Session::instance()->createUserSession($user->id,$save);
        return true;
    }

    public function logout($deep=false){
        Session::instance()->destroy($deep);
    }

    public function isAuth(){
        return Session::instance()->validateSession();
    }

    private $user = NULL;

    public function getCurrentUser($clazz = User::class){
        if(!$this->isAuth()) return NULL;
        if($this->user===NULL) $this->user = call_user_func("{$clazz}::where","id",Session::instance()->getUserId())->first();
        return $this->user;
    }
}