<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'parent_id' => 'nullable|exists:folders,id',
            'color' => 'nullable|string|max:20',
        ]);

        Folder::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'color' => $request->color,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Folder berhasil dibuat');
    }

    public function show(Folder $folder)
    {
        abort_if($folder->user_id !== auth()->id(), 403);

        // subfolder di dalam folder ini
        $folders = Folder::where('parent_id', $folder->id)
            ->where('user_id', auth()->id())
            ->withCount('files')
            ->get();

        // file di dalam folder ini
        $files = $folder->files()->get();

        return view('myPages.arsip.index', [
            'folder' => $folder,
            'folders' => $folders,
            'files' => $files,
            'currentFolderId' => $folder->id
        ]);
    }


    public function update(Request $request, Folder $folder)
    {
        abort_if($folder->user_id !== auth()->id(), 403);

        $request->validate([
            'name' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:20',
        ]);

        $data = [];

        if ($request->filled('name')) {
            $data['name'] = $request->name;
        }

        if ($request->filled('color')) {
            $data['color'] = $request->color;
        }

        if (!empty($data)) {
            $folder->update($data);
        }

        return back()->with('success', 'Folder berhasil diupdate');
    }

    public function destroy(Folder $folder)
    {
        abort_if($folder->user_id !== auth()->id(), 403);

        // hapus file dulu
        $folder->files()->delete();

        // hapus subfolder (recursive nanti bisa kita upgrade)
        $folder->children()->delete();

        $folder->delete();

        return back()->with('success', 'Folder berhasil dihapus');
    }
}
