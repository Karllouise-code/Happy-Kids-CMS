<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Image;
use File;
use Validator;
use Request;
use Input;
use Config;
use Hash;
use Session;
use Log;
use Crypt;
use DB;
use Dompdf\Dompdf;
use View;
use Str;
use Illuminate\Support\Arr;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth2;


class Donator extends Eloquent
{

    protected $table = 'tblDonator';
    protected $primaryKey = 'fldDonatorID';
    public $timestamps = false;

    public function transactions_obj() {
        return $this->hasOne(Donate::class, 'fldDonateResponseID', 'fldDonatorDonateResponseID');
        
    }

    public function onSaveRecord($data, $id)
    {
        $donator = new self;
        $donator->fldDonatorFirstName = $data['name']['given_name'];
        $donator->fldDonatorLastName = $data['name']['surname'];
        $donator->fldDonatorEmailAddress = $data['email_address'];
        $donator->fldDonatorPayerID = $data['payer_id'];
        $donator->fldDonatorCountry = $data['address']['country_code'];
        $donator->fldDonatorDonateResponseID = $id;

        $donator->save();

        return $donator;
    }

    public function displayAll()
    {
        return self::orderBy('fldDonatorCreatedAt', 'DESC')->get();
    }

    public function getDonatorOfTheMonth()
    {
        return self::whereMonth('fldDonatorCreatedAt', date('m'))->whereYear('fldDonatorCreatedAt', date('Y'))->orderBy('fldDonatorCreatedAt', 'DESC')->get();
    }
}
