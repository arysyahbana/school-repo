<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\RepoFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArsipController extends Controller
{
    public function index()
    {
        // Ambil folder root user
        $folders = Folder::where('user_id', auth()->id())
            ->whereNull('parent_id')
            ->withCount('files')
            ->get();

        // Ambil file di root (folder_id null) milik user
        $files = RepoFile::where('user_id', auth()->id())
            ->whereNull('folder_id')
            ->get();

        $allFolders = Folder::where('user_id', auth()->id())->get();

        return view('myPages.arsip.index', [
            'folder'          => null,
            'folders'         => $folders,
            'files'           => $files,
            'allFolders'      => $allFolders, // ⬅️ PENTING
            'currentFolderId' => null,
        ]);
    }
}
