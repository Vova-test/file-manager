<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FolderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:255|regex:/^[a-zа-яёїі0-9]+$/i',  
        ];
    }
}
