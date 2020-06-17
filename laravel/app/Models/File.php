<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';

    public $timestamps = true;

    protected $guarded = [
    	'created_at',
    	'updated_at'
 	];
}
