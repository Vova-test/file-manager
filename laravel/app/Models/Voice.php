<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voice extends Model
{
	protected $table = 'voices';

    public $timestamps = true;

    protected $guarded = [
    	'created_at',
    	'updated_at'
 	];
}
