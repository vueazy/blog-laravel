<?php

namespace App\Traits\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandleUploadFile
{
    public function uploadFile(UploadedFile $file, string $folderPrefix): string
    {
        $file_name = $file->getClientOriginalName();
        $file_ext = $file->getClientOriginalExtension();
        $encoded_file_name = md5(time().$file_name).'.'.$file_ext;

        $file->storeAs($folderPrefix, $encoded_file_name);

        return $encoded_file_name;
    }

    public function syncUploadFile(UploadedFile $file, ?string $oldFileName, string $folderPrefix)
    {
        if (!is_null($oldFileName) && Storage::exists("{$folderPrefix}/".$oldFileName)) {
            Storage::delete("{$folderPrefix}/".$oldFileName);
        }

        return $this->uploadFile($file, $folderPrefix);
    }
}
