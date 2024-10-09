<?php

namespace KeyvanLotfi\FileManager\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use KeyvanLotfi\FileManager\Contracts\FileManagerRepositoryInterface;
use KeyvanLotfi\FileManager\Requests\CreateDirectoryRequest;


class FileManagerController
{
    protected $fileManagerRepository, $disk;


    public function __construct(FileManagerRepositoryInterface $fileManagerRepository)
    {
        $this->fileManagerRepository = $fileManagerRepository;
        $this->disk = config('filemanager.disk', 'public');
    }


    public function getItems(Request $request)
    {
        $path = $request->has('path') ? $request->input('path') : '';
        $data = $this->fileManagerRepository->getFilesAndDirectories($path);


        return response()->json([
            'files' => $data['files'],
            'directories' => $data['directories'],
            'rootDirectories' => $data['rootDirectories'],
            'path' => $path
        ]);
    }


    public function rename(CreateDirectoryRequest $request)
    {
        // Extract old file data and new name
        $item = $request->input('item');
        $newName = $request->input('new_name');
        $oldPath = $item['path'];
        $type = $item['type'];


        // Perform rename operation using the repository
        $result = $this->fileManagerRepository->rename($oldPath, $newName, $type);


        if (!$result)
            return response()->json(['error' => 'Unable to rename file'], 500);
    }


    public function move(Request $request)
    {
        // Extract old file/folder data and the new path
        $newPath = $request->new_path;
        $oldPath = $request->moving['item']['path'];
        $type = $request->moving['item']['type'];

        // Perform move operation using the repository
        $result = $this->fileManagerRepository->move($oldPath, $newPath, $type);

        if (!$result)
            return response()->json(['error' => 'Unable to move the item'], 500);
    }


    public function delete(Request $request)
    {
        $this->fileManagerRepository->delete($request->path, $request->type);
    }


    public function download(Request $request)
    {
        return Storage::disk($this->disk)->download($request->path);
    }


    public function createFolder(CreateDirectoryRequest  $request)
    {
        $folderName = $request->folder_name;
        $path       = $request->path;


        $result = $this->fileManagerRepository->createFolder($folderName, $path);


        if (!$result)
            return response()->json(['error' => 'Unable to create folder'], 500);
    }
}
