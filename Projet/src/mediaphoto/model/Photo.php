<?php

namespace mediaphoto\model;

class Photo extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'photo';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function author() {
        return $this->belongsTo('\mediaphoto\model\User', 'id_utilisateur');
    }

    public function gallery() {
        return $this->belongsTo('\mediaphoto\model\Gallery', 'id_galerie');
    }

    public function getGalleryPhoto($id_galerie, $id, $nb) {
        return $this::select()->where([
            ['id_galerie', '=', $id_galerie],
            ['id', '!=', $id]
        ])->orderByDesc('id')->limit($nb)->get();
    }

    public function getNextPhoto($id_galerie, $current_id) {
        $next = $this::select('id')->where([
            ['id_galerie', '=', $id_galerie],
            ['id', '>', $current_id]
        ])->first();
        if($next) {
            return $next;
        } else {
            return false;
        }
    }

    public function getPreviousPhoto($id_galerie, $current_id) {
        $previous = $this::select('id')->where([
            ['id_galerie', '=', $id_galerie],
            ['id', '<', $current_id]
        ])->orderByDesc('id')->first();
        if($previous) {
            return $previous;
        } else {
            return false;
        }
    }
}