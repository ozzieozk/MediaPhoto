<?php

namespace mediaphoto\model;

class TagPhoto extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'tag_photo';
    protected $primaryKey = 'id';
    public $timestamps = false;
}