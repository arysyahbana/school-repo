<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Folder;
use App\Models\RepoFile;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | KARTU STATISTIK
        |--------------------------------------------------------------------------
        */

        if (in_array($user->jabatan, ['admin', 'kepala_madrasah'])) {

            $stats = [
                'total_users'   => User::count(),
                'total_files'   => RepoFile::count(),
                'total_folders' => Folder::count(),
                'total_kirim'   => RepoFile::whereNotNull('send_batch_id')
                    ->distinct('send_batch_id')
                    ->count(),
            ];

            $usersByRole = User::selectRaw('jabatan, COUNT(*) as total')
                ->groupBy('jabatan')
                ->get();
        } else {

            $stats = [
                'total_files' => RepoFile::where('user_id', $user->id)->count(),

                'total_folders' => Folder::where('user_id', $user->id)->count(),

                'arsip_masuk' => RepoFile::where('user_id', $user->id)
                    ->whereNotNull('sender_id')
                    ->count(),

                'arsip_dikirim' => RepoFile::where('sender_id', $user->id)
                    ->distinct('send_batch_id')
                    ->count(),
            ];

            $usersByRole = collect();
        }

        /*
        |--------------------------------------------------------------------------
        | ARSIP TERBARU
        |--------------------------------------------------------------------------
        */

        $latestFiles = RepoFile::with('sender')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | HISTORI KIRIM
        |--------------------------------------------------------------------------
        */

        $latestSends = RepoFile::with('sender')
            ->whereNotNull('send_batch_id')
            ->latest()
            ->take(5)
            ->get();

        $activities = RepoFile::with(['sender', 'user'])
            ->latest()
            ->take(10)
            ->get();

        return view('myPages.dashboard', compact(
            'stats',
            'latestFiles',
            'latestSends',
            'activities',
            'usersByRole'
        ));
    }
}
