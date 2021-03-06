<?php

namespace UniSharp\LaravelFilemanager;

use Illuminate\Support\Facades\File;

class LfmFileRepository implements RepositoryContract
{
    private $path;

    public function __construct($storage_path)
    {
        $this->path = $storage_path;
    }

    public function __call($function_name, $arguments)
    {
        // TODO: check function exists
        return File::$function_name($this->path, ...$arguments);
    }

    // TODO: check ending with slash in tests
    public function rootPath()
    {
        return public_path() . '/';
    }

    public function isDirectory()
    {
        $parent_path = substr($this->path, 0, strrpos($this->path, '/'));
        $current_path = $this->path;
        $this->path = $parent_path;

        return in_array($current_path, $this->directories());
    }

    public function move($new_lfm_path)
    {
        return File::move($this->path, $new_lfm_path->path('storage'));
    }

    public function save($file, $new_filename)
    {
        File::move($file->getRealPath(), $this->path . '/' . $new_filename);
    }
}
