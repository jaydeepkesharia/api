<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
 	use Notifiable;
    		
    protected $fillable = [
        'device_id','unique_id', 
    ];
}
