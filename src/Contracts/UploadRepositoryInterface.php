<?php

namespace KeyvanLotfi\FileManager\Contracts;


use Illuminate\Http\UploadedFile;

interface UploadRepositoryInterface
{
    /**
     * Upload a file to a specific path.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @return bool
     */
    public function upload(UploadedFile $file, string $path): bool;

    /**
     * Check if the file type is allowed.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return bool
     */
    public function isAllowedFileType(\Illuminate\Http\UploadedFile $file): bool;

    /**
     * Check if the file size is within the limit.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return bool
     */
    public function isWithinMaxSize(\Illuminate\Http\UploadedFile $file): bool;
}
