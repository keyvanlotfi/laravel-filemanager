<?php

namespace KeyvanLotfi\FileManager\Controllers;

use Illuminate\Http\Request;
use KeyvanLotfi\FileManager\Contracts\UploadRepositoryInterface;

class UploadController
{
    protected $uploadRepository;

    public function __construct(UploadRepositoryInterface $uploadRepository)
    {
        $this->uploadRepository = $uploadRepository;
    }




    public function upload(Request $request)
    {
        try {
            $file = $request->file('file');
            $path = $request->input('path', '/');

            // Call the repository method to upload the file
            $this->uploadRepository->upload($file, $path);

            return response()->json(['success' => 'File uploaded successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


}
