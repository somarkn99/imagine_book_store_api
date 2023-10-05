<?php

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

if (! function_exists('UploadFile')) {
    function UploadFile($file, $folder)
    {
        //get file name with extention
        $filenameWithExt = $file->getClientOriginalName();
        //get just file name
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $filename = str_replace(' ', '_', $filename);
        //GET EXTENTION
        $extention = $file->getClientOriginalExtension();
        //file name to store
        $fileNameToStore = $folder.'/'.$filename.'_'.time().'.'.$extention;
        //upload image
        $path = $file->storeAs($folder, $filename.'_'.time().'.'.$extention, ['disk' => 'public']);

        return $fileNameToStore;
    }
}

if (! function_exists('UpdateFile')) {
    function UpdateFile($file, $path, $oldFile)
    {
        Delete_File($oldFile);
        $new_path = UploadFile($file, $path);

        return $new_path;
    }
}

if (! function_exists('Delete_File')) {
    function Delete_File($path)
    {
        // Parse the URL
        $parsedUrl = parse_url($path);

        // Get the path component
        $path = $parsedUrl['path'];

        $desiredPart = trim($path, '/'); // Remove leading and trailing slashes

        // Validate input
        if (empty($desiredPart)) {
            throw new InvalidArgumentException('Empty path');
        }

        $fullPath = public_path('storage/'.$desiredPart);

        // Check if the file exists before attempting to delete
        if (! file_exists($fullPath)) {
            throw new FileNotFoundException('File not found');
        }

        // Attempt to delete the file using try-catch
        try {
            unlink($fullPath);

            return true;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error($e);

            return false;
        }
    }
}
