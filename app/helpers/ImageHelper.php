<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


if (! function_exists('uploadImage'))
{
    function uploadImage($file, $destination)
    {
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($destination, $filename, 'public');
        return $path;
    }
}
if (! function_exists('DeleteOldImages'))
{
    function DeleteOldImages($filename)
    {
        if(File::exists(public_path().'images/posts'.$filename))
        {
            File::delete(public_path().'images/posts'.$filename);
        }
       
    }
}

