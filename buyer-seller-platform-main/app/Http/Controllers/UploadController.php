<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UploadController extends Controller
{
    protected array $allowedFolders = [
        'products',
        'business-documents',
        'categories',
        'subcategories',
        'profile-photos',
        'business-logos',
        'company-photos',
        'certifications',
        'awards',
        'company-videos',
    ];

    public function store(Request $request)
    {
        $request->validate([
            'folder' => ['required', 'string', Rule::in($this->allowedFolders)],
        ]);

        $folder = (string) $request->input('folder');
        $isVideoFolder = $folder === 'company-videos';

        $data = $request->validate([
            'file' => [
                'required',
                'file',
                'max:51200', // 50 MB
                $isVideoFolder ? 'mimetypes:video/mp4,video/quicktime,video/webm' : 'mimetypes:image/jpeg,image/png,image/webp,application/pdf',
            ],
            'folder' => ['required', 'string', Rule::in($this->allowedFolders)],
        ]);

        $file = $data['file'];
        $folder = $data['folder'];

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');
        $filename = Str::uuid() . '.' . $extension;
        $path = trim($folder, '/') . '/' . $filename;

        Storage::disk('s3')->putFileAs(
            $folder,
            $file,
            $filename,
            [
                'visibility' => 'public',
                'ContentType' => $file->getMimeType(),
            ]
        );

        $baseUrl = rtrim((string) config('filesystems.disks.s3.url'), '/');
        $url = $baseUrl . '/' . ltrim($path, '/');

        return response()->json([
            'ok' => true,
            'path' => $path,
            'url' => $url,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
    }
}
