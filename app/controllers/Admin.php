<?php
/**
 * Created by PhpStorm.
 * User: mamedov
 * Date: 11.10.2018
 * Time: 19:38
 */

namespace app\controllers;


use app\models\Role;
use app\models\User;
use core\base\Controller;
use core\base\View;
use core\system\database\Database;
use core\system\Request;
use core\system\Url;

class Admin extends Controller
{
    public function action_index(){
        $v = new View("admin/index");
        $v->setTemplate("admin");
        echo $v->render();
    }

    public function action_users()
    {
        $v = new View("admin/users");
        $v->setTemplate("admin");
        $v->users = User::all();
        $v->roles=Role::all();
        echo $v->render();
    }

    public function action_addrole()
    {
       $user_id = (int)Request::post("userid");
       $role_id = (int)Request::post("roleid");
       try {
           Database::instance()->user_roles->insert([
               "users_id" => $user_id,
               "roles_id" => $role_id
           ]);
       }catch (\Exception $e){}
//       var_dump($user_id);
//       var_dump($role_id);


       Url::redirect($_SERVER["HTTP_REFERER"]);
    }

    public function action_delrole(){
        $user_id = (int)Request::post("userid");
        $role_id = (int)Request::post("roleid");
        Database::instance()->user_roles
            ->where("users_id",$user_id)
            ->andWhere("roles_id",$role_id)
            ->delete();
        Url::redirect($_SERVER["HTTP_REFERER"]);
    }
    public function action_editlogin(){
        $user_id = (int)Request::post("userid");
        $login = Request::post("value");

        $u = User::where("id",$user_id)->first();
        $u->login = $login;
        $u->save();
    }
}