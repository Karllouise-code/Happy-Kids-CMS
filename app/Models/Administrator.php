<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contacts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\Passport;
use Laravel\Passport\HasApiTokens;

use Input;
use Hash;
use Config;
use Auth;
use Log;
use Crypt;
use Str;


class Administrator extends Authenticatable
{
    protected $table = 'tblAdministrator';
    protected $primaryKey = 'fldAdministratorID';
    public $timestamps = false;
 
    use HasApiTokens, Notifiable;
 
    protected $fillable = [
       'fldAdministratorID', 'fldAdministratorFirstname', 'fldAdministratorLastname', 'fldAdministratorEmail', 'fldAdministratorPassword', 'fldAdministratorMobile'
    ];


}
