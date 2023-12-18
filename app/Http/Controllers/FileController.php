<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    static $default = 'default.jpg';
    static $diskName = 'files';

    static $systemTypes = [
        'profile' => ['png', 'jpeg', 'jpg', 'gif'],
        'post' => ['png', 'jpeg', 'jpg', 'gif','mp4','mp3','pdf'],
    ];





    public function get(String $fileType,Int $associatedId){

    }

}
