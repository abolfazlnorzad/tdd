<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadImageController extends Controller
{
    public function upload(Request $request)
    {
        //valid image|max:250|
        $request->validate([
           "image"=>"image|max:250|dimensions:max_width=100,max_height=200"
        ]);

        $image = $request->file("image");

        $image->move(public_path("upload/"),$image->hashName());

        return ['url'=>"/upload/{$image->hashName()}"];
    }
}
