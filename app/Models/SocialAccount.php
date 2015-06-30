<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class social extends Model
{
  // allow all the fields in the social table to be mass assignable
  protected $fillable = array('*');
}
