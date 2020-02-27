<?php


namespace bibliovox\models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AudioMot extends Pivot
{
    public $timestamps = false;
    protected $table = 'audioMot';
}