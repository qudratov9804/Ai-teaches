<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Store an uploaded file on the configured disk with a random,
     * safe filename and return metadata for persisting to the database.
     *
     * @return array{file_path: string, original_name: string, mime_type: string, file_size: int}
     */
    public function store(UploadedFile $file, string $directory): array
    {
        $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();

        $path = $file->storeAs($directory, $filename, config('uploads.disk'));

        return [
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType() ?? $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ];
    }

    /**
     * Permanently delete a previously stored file from the configured disk.
     */
    public function delete(string $path): void
    {
        Storage::disk(config('uploads.disk'))->delete($path);
    }
}
