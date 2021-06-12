<?php

namespace mediaphoto\model;

class Tag extends \Illuminate\Database\Eloquent\Model {
    protected $table = 'tag';
    protected $primaryKey = 'id';
    public $timestamps = false;
}