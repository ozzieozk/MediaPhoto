<?php

namespace mediaphoto\model;

class User extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'utilisateur';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function galeries() {
        return $this->belongsTo('\mediaphoto\model\Gallery', 'author');
    }

    public static function getLoggedUserId() {
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        if($auth->logged_in) {
            return User::select('id')->where('nom', '=', $_SESSION['user_login'])->first()->id;
        } else {
            return false;
        }
    }
}