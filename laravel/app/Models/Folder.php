<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $table = 'folders';

    public $timestamps = true;

    protected $guarded = [
    	'created_at',
    	'updated_at'
 	];

 	public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function childrenFolders()
	{
	    return $this->hasMany(Folder::class)->with('folders');
	}
}
