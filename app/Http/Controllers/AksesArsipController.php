<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Folder;
use App\Models\RepoFile;
use Illuminate\Http\Request;

class AksesArsipController extends Controller
{
    public function index(Request $request)
    {
        $users = User::withCount('files')

            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })

            ->paginate(5);

        return view(
            'myPages.aksesArsip.index',
            compact('users')
        );
    }
    // Lihat arsip guru (root)
    public function show(User $user)
    {
        $folders = Folder::where('user_id', $user->id)
            ->whereNull('parent_id')
            ->get();

        $files = RepoFile::where('user_id', $user->id)
            ->whereNull('folder_id')
            ->get();

        return view('myPages.aksesArsip.show', compact('user', 'folders', 'files'));
    }

    // Masuk ke folder tertentu
    public function openFolder(User $user, Folder $folder)
    {
        // Pastikan folder milik user tersebut
        if ($folder->user_id !== $user->id) {
            abort(403);
        }

        $folders = $folder->children;
        $files   = $folder->files;

        return view('myPages.aksesArsip.show', compact('user', 'folders', 'files', 'folder'));
    }
}
