<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class FileController extends Controller
{
    static $default = 'default.jpg';
    static $diskName = 'files';

    static $systemTypes = [
        'profile' => ['png', 'jpeg', 'jpg', 'gif'],
        'post' => ['png', 'jpeg', 'jpg', 'gif','mp4','mp3','pdf'],
    ];

    private static function isValidType(String $type) {
        return array_key_exists($type, self::$systemTypes);
    }

    private static function defaultAsset(String $type) {
        return asset($type . '/' . self::$default);
    }

    private static function getFileName (String $type, int $id) {
        
        $fileName = null;
        switch($type) {
            case 'profile':
                $fileName = User::find($id)->profile_image;
                break;
            case 'posts':
                break;
            }
    
        return $fileName;
    }
    
    public function get(String $fileType,Int $userId){
                // Validation: upload type
        if (!self::isValidType($fileType)) {
            return self::defaultAsset($fileType);
        }

        // Validation: file exists
        $fileName = self::getFileName($fileType, $userId);
        if ($fileName) {
            return asset($fileType . '/' . $fileName);
        }

        return self::defaultAsset($fileType);
    }


}
