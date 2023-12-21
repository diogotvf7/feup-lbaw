<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class FileController extends Controller
{
    static $default = 'default.jpg';
    static $diskName = 'files';

    static $systemTypes = [
        'profile' => ['png', 'jpeg', 'jpg', 'gif'],
        'post' => ['png', 'jpeg', 'jpg', 'gif', 'mp4', 'mp3', 'pdf', 'txt'],
    ];

    private static function getDefaultExtension(String $type)
    {
        return reset(self::$systemTypes[$type]);
    }

    private static function isValidExtension(String $type, String $extension)
    {
        $allowedExtensions = self::$systemTypes[$type];

        // Note the toLowerCase() method, it is important to allow .JPG and .jpg extensions as well
        return in_array(strtolower($extension), $allowedExtensions);
    }

    private static function isValidType(String $type)
    {
        return array_key_exists($type, self::$systemTypes);
    }

    private static function defaultAsset(String $type)
    {
        return asset('userFiles/' . $type . '/' . self::$default);
    }

    private static function getFileName(String $type, int $id, String $extension = null)
    {

        $fileName = null;
        switch ($type) {
            case 'profile':
                $fileName = User::find($id)->profile_picture; // can be null as well
                break;
            case 'post':
                // other models
                break;
            default:
                return null;
        }

        return $fileName;
    }

    private static function delete(String $type, int $id)
    {
        $existingFileName = self::getFileName($type, $id);
        if ($existingFileName) {
            Storage::disk(self::$diskName)->delete('userFiles/' . $type . '/' . $existingFileName);

            switch ($type) {
                case 'profile':
                    User::find($id)->profile_picture = null;
                    break;
                case 'post':
                    // other models
                    break;
            }
        }
    }

    function upload(Request $request)
    {

        // Validation: has file
        if (!$request->hasFile('file')) {
            return redirect()->back()->with('error', 'Error: File not found');
        }

        // Validation: upload type
        if (!$this->isValidType($request->type)) {
            return redirect()->back()->with('error', 'Error: Unsupported upload type');
        }

        // Validation: upload extension
        $file = $request->file('file');
        $type = $request->type;
        $extension = $file->extension();
        if (!$this->isValidExtension($type, $extension)) {
            return redirect()->back()->with('error', 'Error: Unsupported upload extension');
        }

        // Prevent existing old files
        $this->delete($type, $request->id);

        // Generate unique filename
        $fileName = $file->hashName();

        // Validation: model
        $error = null;
        switch ($request->type) {
            case 'profile':
                $user = User::findOrFail($request->id);
                if ($user) {
                    $user->profile_picture = $fileName;
                    $user->save();
                } else {
                    $error = "unknown user";
                }
                break;

            case 'post':
                // other models
                break;

            default:
                redirect()->back()->with('error', 'Error: Unsupported upload object');
        }

        if ($error) {
            redirect()->back()->with('error', `Error: {$error}`);
        }

        $file->storeAs('userFiles/' . $type, $fileName, self::$diskName);

        return redirect()->back()->with('success', 'Success: upload completed!');
    }

    static function get(String $type, int $userId)
    {

        // Validation: upload type
        if (!self::isValidType($type)) {
            return self::defaultAsset($type);
        }

        // Validation: file exists
        $fileName = self::getFileName($type, $userId);
        if ($fileName) {
            return asset('userFiles/' . $type . '/' . $fileName);
        }

        // Not found: returns default asset
        return self::defaultAsset($type);
    }
}
