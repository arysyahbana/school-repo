<?php

namespace App\Http\Controllers;

use App\Models\RepoFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class KirimArsipController extends Controller
{
    public function index(Request $request)
    {
        $arsips = RepoFile::query()

            ->where('sender_id', auth()->id())

            ->whereNotNull('send_batch_id')

            ->when($request->search, function ($query) use ($request) {

                $query->where('name', 'like', '%' . $request->search . '%');
            })

            ->selectRaw('
            send_batch_id,
            name,
            storage_type,
            created_at,
            COUNT(*) as total_penerima
        ')

            ->groupBy(
                'send_batch_id',
                'name',
                'storage_type',
                'created_at'
            )

            ->latest('created_at')

            ->paginate(5);

        return view(
            'myPages.kirimArsip.index',
            compact('arsips')
        );
    }

    public function create()
    {
        $users = User::all();
        return view('myPages.kirimArsip.create', compact('users'));
    }

    public function store(Request $request)
    {
        /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */

        $rules = [
            'name'            => 'required|string|max:255',
            'recipient_type'  => 'required|in:all,guru,tendik,custom',
            'storage_type'    => 'required|in:local,gdrive',
            'document_date'   => 'nullable|date',
        ];

        if ($request->storage_type === 'local') {
            $rules['file'] = 'required|file|max:10240';
        }

        if ($request->storage_type === 'gdrive') {
            $rules['drive_url'] = 'required|url';
        }

        if ($request->recipient_type === 'custom') {
            $rules['users'] = 'required|array|min:1';
            $rules['users.*'] = 'exists:users,id';
        }

        $request->validate($rules);

        /*
    |--------------------------------------------------------------------------
    | AMBIL PENERIMA
    |--------------------------------------------------------------------------
    */

        switch ($request->recipient_type) {

            case 'all':

                $recipientIds = User::whereIn('jabatan', [
                    'kepala_madrasah',
                    'wakil',
                    'guru',
                    'kaur',
                    'tu',
                ])->pluck('id')->toArray();

                break;

            case 'guru':

                $recipientIds = User::whereIn('jabatan', [
                    'wakil',
                    'guru',
                ])->pluck('id')->toArray();

                break;

            case 'tendik':

                $recipientIds = User::whereIn('jabatan', [
                    'kaur',
                    'tu',
                ])->pluck('id')->toArray();

                break;

            default:

                $recipientIds = $request->users;

                break;
        }

        // Jangan kirim ke diri sendiri
        $recipientIds = collect($recipientIds)
            ->reject(fn($id) => $id == auth()->id())
            ->unique()
            ->values()
            ->toArray();

        $sendBatchId = Str::uuid();

        /*
    |--------------------------------------------------------------------------
    | DATA DASAR FILE
    |--------------------------------------------------------------------------
    */

        $data = [
            'name'          => $request->name,
            'document_date' => $request->document_date,
            'sender_id'     => auth()->id(),
            'send_batch_id' => $sendBatchId,
            'folder_id'     => null,
            'storage_type'  => $request->storage_type,
        ];

        /*
    |--------------------------------------------------------------------------
    | LOCAL STORAGE
    |--------------------------------------------------------------------------
    */

        if ($request->storage_type === 'local') {

            $uploaded = $request->file('file');

            $path = $uploaded->store('repo_files', 'public');

            $data += [
                'path'      => $path,
                'mime_type' => $uploaded->getMimeType(),
                'size'      => $uploaded->getSize(),
            ];
        }

        /*
    |--------------------------------------------------------------------------
    | GOOGLE DRIVE
    |--------------------------------------------------------------------------
    */

        if ($request->storage_type === 'gdrive') {

            $data += [
                'drive_url' => $request->drive_url,
            ];
        }

        /*
    |--------------------------------------------------------------------------
    | SIMPAN KE MASING-MASING PENERIMA
    |--------------------------------------------------------------------------
    */

        foreach ($recipientIds as $recipientId) {

            RepoFile::create([
                ...$data,
                'user_id' => $recipientId,
            ]);
        }

        return redirect()
            ->route('kirim-arsip.index')
            ->with('success', 'Arsip berhasil dikirim.');
    }

    public function show($batchId)
    {
        $files = RepoFile::with(['user', 'sender'])
            ->where('send_batch_id', $batchId)
            ->get();

        $arsip = $files->first();

        $penerima = $files->pluck('user');

        return view(
            'myPages.kirimArsip.show',
            compact(
                'arsip',
                'penerima'
            )
        );
    }

    public function destroy($batchId)
    {
        $files = RepoFile::where('send_batch_id', $batchId)
            ->where('sender_id', auth()->id())
            ->get();

        if ($files->isEmpty()) {
            return back()->with(
                'error',
                'Data tidak ditemukan.'
            );
        }

        foreach ($files as $file) {

            // Hapus file fisik jika upload lokal
            if (
                $file->storage_type === 'local'
                && $file->path
                && Storage::disk('public')->exists($file->path)
            ) {
                Storage::disk('public')->delete($file->path);
            }

            $file->delete();
        }

        return redirect()
            ->route('kirim-arsip.index')
            ->with(
                'success',
                'Arsip berhasil dihapus.'
            );
    }
}
