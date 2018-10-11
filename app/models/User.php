<?php
/**
 * Created by PhpStorm.
 * User: mamedov
 * Date: 20.09.2018
 * Time: 20:34
 */

namespace app\models;


use core\base\Model;

class User extends \core\system\models\User
{
    //public static $table="ggg";
    public function posts(){
        return $this->hasMany(Post::class,"user_id","id");
    }

    public function roles(){
        return $this->belongsToMany(Role::class,"user_roles","users_id","roles_id");
    }

    private $roles = null;
    public function hasRole(string $role){
        if($this->roles == null) $this->roles = $this->roles()->all();
        foreach($this->roles as $r){
            if($r->name == $role) return true;
        }
        return false;
    }
}