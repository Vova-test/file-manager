<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    public function rules()
    {
        return [
            'image' => 'required|max:2048',  
        ];
    }
}
