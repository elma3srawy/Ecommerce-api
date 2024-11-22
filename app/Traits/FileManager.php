<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait FileManager
{
    protected $path;
    protected function uploadFile(UploadedFile $file , $directory)
    {
        $this->path = $file->store($directory);
        return $this;
    }
    protected function deleteFile($paths)
    {
        $paths = is_array($paths) ? $paths : [$paths];

        foreach ($paths as $path) {
            if (!is_null($path)  && Storage::exists($path)) {
                Storage::delete($path);
            }
        }
        return new self;
    }


    public function __get($name)
    {
        if (property_exists($this, $name))
        {
            return $this->$name;
        }

    }
}
