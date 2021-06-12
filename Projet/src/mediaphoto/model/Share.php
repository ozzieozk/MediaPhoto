<?php

namespace mediaphoto\model;

class Share extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'galerie_partage';
    protected $primaryKey = 'id';
    public $timestamps = false;
}