<?php

namespace KeyvanLotfi\FileManager\Repositories;

use Illuminate\Support\Facades\Storage;
use KeyvanLotfi\FileManager\Contracts\FileManagerRepositoryInterface;

class FileManagerRepository implements FileManagerRepositoryInterface
{
    protected $disk;

    public function __construct()
    {
        $this->disk = config('filemanager.disk', 'public');
    }


    public function getFilesAndDirectories(string $path = ''): array
    {
        $path = trim($path, '/');

        $files = Storage::disk($this->disk)->files($path);
        $directories = Storage::disk($this->disk)->directories($path);
        $rootDirectories = Storage::disk($this->disk)->directories('/');


        return [
            'files' => collect($files)->map(function ($file) {
                return [
                    'name' => basename($file),
                    'path' => '/' . ltrim($file, '/'),
                    'extension' => pathinfo($file, PATHINFO_EXTENSION),
                    'type' => 'file',
                ];
            })->toArray(),

            'directories' => collect($directories)->map(function ($directory) use ($path) {
                return [
                    'name' => basename($directory),
                    'path' => $path ? $path . '/' . basename($directory) : basename($directory),
                    'type' => 'directory',
                ];
            })->toArray(),

            'rootDirectories' => collect($rootDirectories)->map(function ($rootDirectory) {
                return [
                    'name' => basename($rootDirectory),
                    'path' => Storage::disk($this->disk)->url($rootDirectory),
                ];
            })->toArray(),

            'path' => $path,
        ];
    }


    public function rename(string $oldPath, string $newName, string $type): bool
    {
        // Determine the new path based on the item type
        if ($type === 'file') {
            $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
            $newFileName = $newName . ($extension ? '.' . $extension : '');
            $newPath = dirname($oldPath) . '/' . $newFileName; // New path for the file
        } elseif ($type === 'directory')
            $newPath = dirname($oldPath) . '/' . $newName; // New path for the directory


        // Check if the old path exists
        if (!Storage::disk($this->disk)->exists($oldPath))
            return false;


        // Rename the item
        return Storage::disk($this->disk)->move($oldPath, $newPath);
    }


    public function move(string $oldPath, string $newPath, string $type): bool
    {
        // Ensure newPath ends with a slash for directories
        if ($type === 'directory')
            $newPath = rtrim($newPath, '/') . '/' . basename($oldPath);
        elseif ($type === 'file') {
            // Add the file name to the new path for files
            $newPath = rtrim($newPath, '/') . '/' . basename($oldPath);
        }

        // Check if the old path exists
        if (!Storage::disk($this->disk)->exists($oldPath))
            return false; // Old path does not exist


        // Check if the new path is valid and does not already exist
        if (Storage::disk($this->disk)->exists($newPath))
            return false; // Destination already exists

        // Move the item to the new path
        return Storage::disk($this->disk)->move($oldPath, $newPath);
    }


    public function delete($path, $type)
    {
        if ($type === 'directory')
            return Storage::disk($this->disk)->deleteDirectory($path);

        return Storage::disk($this->disk)->delete($path);
    }


    public function createFolder($folderName, $path)
    {
        // Ensure that the path ends with a slash if needed
        $fullPath = $path ? rtrim($path, '/') . '/' . $folderName : $folderName;


        return Storage::disk($this->disk)->makeDirectory($fullPath);
    }
}
