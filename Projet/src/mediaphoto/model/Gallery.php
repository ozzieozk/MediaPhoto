<?php

namespace mediaphoto\model;

class Gallery extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'galerie';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function author() {
        return $this->belongsTo('\mediaphoto\model\User', 'auteur');
    }

    public function photos() {
        return $this->hasMany('\mediaphoto\model\Photo', 'id_galerie')->limit(15)->offset(0);
    }

    public function partage() {
        return $this->hasMany('\mediaphoto\model\Share', 'id_galerie');
    }

    public function getShareUsername($id) {
        return User::select('nom', 'nom_complet')->where('id', '=', $id)->first();
    }

    public static function getUserGalleries($userId) {
        return Gallery::where('auteur', '=', $userId)->orderByDesc('id')->get();
    }
}