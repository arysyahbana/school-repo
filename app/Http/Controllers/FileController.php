<?php

namespace App\Http\Controllers;

use App\Models\RepoFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function store(Request $request)
    {
        // Aturan dasar
        $rules = [
            'storage_type' => 'required|in:local,gdrive',
            'folder_id'    => 'nullable|exists:folders,id',
        ];

        // Tambahkan aturan khusus berdasarkan storage_type
        if ($request->input('storage_type') === 'local') {
            $rules['file'] = 'required|file|max:10240';
            // opsional: bisa tambahkan 'name' jika mau wajib isi
        } elseif ($request->input('storage_type') === 'gdrive') {
            $rules['drive_url'] = 'required|url';
            // opsional: bisa tambahkan 'name' jika mau wajib isi
        }

        $validated = $request->validate($rules);

        // Siapkan data untuk disimpan
        $data = [
            'user_id'      => auth()->id(),
            'folder_id'    => $request->folder_id,
            'storage_type' => $request->storage_type,
            'sender_id' => null,
            'document_date' => $request->document_date,
        ];

        if ($request->storage_type === 'local') {
            $uploaded = $request->file('file');
            $path = $uploaded->store('repo_files', 'public');

            $data += [
                'name'      => $request->name ?? $uploaded->getClientOriginalName(),
                'path'      => $path,
                'mime_type' => $uploaded->getMimeType(),
                'size'      => $uploaded->getSize(),
            ];
        }

        if ($request->storage_type === 'gdrive') {
            $data += [
                'name'      => $request->name ?? 'Google Drive File',
                'drive_url' => $request->drive_url,
            ];
        }

        RepoFile::create($data);

        return back()->with('success', 'File berhasil ditambahkan');
    }


    public function update(Request $request, RepoFile $file)
    {
        abort_if($file->user_id !== auth()->id(), 403);

        $request->validate([
            'name' => 'required|string|max:150',
            'document_date' => 'nullable|date',
        ]);

        $file->update([
            'name' => $request->name,
            'document_date' => $request->document_date,
        ]);

        return back()->with('success', 'File berhasil diubah');
    }

    public function destroy(RepoFile $file)
    {
        abort_if($file->user_id !== auth()->id(), 403);

        // hapus file fisik
        if ($file->storage_type === 'local' && $file->path) {
            $fullPath = storage_path('app/public/' . $file->path);

            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        // hapus database
        $file->forceDelete();

        return back()->with('success', 'File berhasil dihapus permanen');
    }

    public function download(RepoFile $file)
    {
        abort_if(
            $file->user_id !== auth()->id()
                && !in_array(auth()->user()->jabatan, ['admin', 'kaur', 'kepala_madrasah']),
            403
        );

        $path = storage_path('app/public/' . $file->path);
        abort_if(!file_exists($path), 404);

        $ext = pathinfo($path, PATHINFO_EXTENSION);

        $filename = $file->name;

        if (!str_ends_with(strtolower($filename), '.' . strtolower($ext))) {
            $filename .= '.' . $ext;
        }

        return response()->download(
            $path,
            $filename,
            [
                'Content-Type' => $file->mime_type,
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }


    public function open(RepoFile $file)
    {
        abort_if(
            $file->user_id !== auth()->id()
                && !in_array(auth()->user()->jabatan, ['admin', 'kaur', 'kepala_madrasah']),
            403
        );

        // Google Drive → buka langsung
        if ($file->storage_type === 'gdrive') {
            return redirect()->away($file->drive_url);
        }

        // Local file
        $path = storage_path('app/public/' . $file->path);

        abort_if(!file_exists($path), 404);

        return response()->file($path);
    }

    public function move(Request $request, RepoFile $file)
    {
        abort_if($file->user_id !== auth()->id(), 403);

        $file->update([
            'folder_id' => $request->folder_id
        ]);

        return back()->with('success', 'File moved');
    }
}
