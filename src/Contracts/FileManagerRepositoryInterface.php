<?php

namespace KeyvanLotfi\FileManager\Contracts;

interface FileManagerRepositoryInterface
{
    public function getFilesAndDirectories(string $path = ''): array;
}
