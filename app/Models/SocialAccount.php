<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
  protected $table = 'social_accounts';
  public $timestamps = false;
  // allow all the fields in the social_accounts table to be mass assignable
  protected $fillable = array('*');
}
