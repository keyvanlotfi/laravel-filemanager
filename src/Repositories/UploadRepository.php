<?php

namespace KeyvanLotfi\FileManager\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use KeyvanLotfi\FileManager\Contracts\UploadRepositoryInterface;

class UploadRepository implements UploadRepositoryInterface
{
    protected $disk;
    protected $allowedFileTypes;
    protected $maxFileSize;


    public function __construct()
    {
        $this->disk = config('filemanager.disk', 'public');
        $this->allowedFileTypes = config('filemanager.allowed_file_types', []);

        // Max Upload Size
        $maxSizeMB = config('filemanager.max_upload_size', 2); // Default to 2MB if not set
        $this->maxFileSize = $maxSizeMB * 1024 * 1024; // Convert to bytes
    }


    public function upload(UploadedFile $file, string $path): bool
    {
        // Check file type
        if (!$this->isAllowedFileType($file)) {
            throw new \Exception('The file type is not allowed.');
        }

        // Check file size
        if (!$this->isWithinMaxSize($file)) {
            throw new \Exception('The file size exceeds the maximum allowed limit.');
        }

        // Store the file
        return Storage::disk($this->disk)->putFileAs($path, $file, $file->getClientOriginalName());
    }

    public function isAllowedFileType(UploadedFile $file): bool
    {
        return in_array($file->getClientOriginalExtension(), $this->allowedFileTypes);
    }

    public function isWithinMaxSize(UploadedFile $file): bool
    {
        return $file->getSize() <= $this->maxFileSize;
    }
}
