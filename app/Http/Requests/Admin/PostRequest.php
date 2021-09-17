<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "title" => ['required'],
            "body" => 'required|min:5',
            "image" => ['required',"url"],
            "tags" => ['required',"array"],
            "tags.*"=>[Rule::exists("tags","id")]
        ];
    }
}
