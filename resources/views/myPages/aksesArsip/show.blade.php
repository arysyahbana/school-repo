@extends('app')
@section('title', 'Arsip')
@section('content')
    <style>
        .folder-card {
            transition: 0.2s ease-in-out;
            border-radius: 12px;
        }

        .folder-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.12);
        }

        .context-menu {
            display: none;
            position: absolute;
            z-index: 999;
        }
        .file-card {
            border-radius: 14px;
            /* overflow: hidden; */
            cursor: pointer;
            background: #f8f9fa;
            box-shadow: 0 8px 20px rgba(0,0,0,.06);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .file-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(0,0,0,.12);
        }

        /* preview area */
        .file-preview {
            width: 100%;
            height: 180px;
            background: #e9ecef;
        }

        .file-preview img,
        .file-preview iframe {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border: none;
        }

        /* fallback icon */
        .file-fallback {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #6c757d;
        }

        /* badge type */
        .file-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(0,0,0,.65);
            color: #fff;
            padding: 6px 8px;
            border-radius: 10px;
            font-size: 14px;
            z-index: 3;
        }

        /* hover overlay */
        .file-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to top,
                rgba(0,0,0,.65),
                rgba(0,0,0,.2),
                transparent
            );
            opacity: 0;
            transition: opacity .2s ease;
            display: flex;
            align-items: flex-end;
        }

        .file-card:hover .file-overlay {
            opacity: 1;
        }

        /* title */
        .file-title {
            color: #fff;
            font-weight: 600;
            padding: 12px;
            width: 100%;
            font-size: 14px;
            line-height: 1.3;
        }

    </style>

@php use Illuminate\Support\Str; @endphp

    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="bx bx-folder me-1"></i>
                {{ isset($folder) ? $folder->name : 'Arsip ' .$user->name }}
            </h5>
        </div>


        @if(isset($folder))
            <a href="{{ $folder->parent_id
                ? route('akses-arsip.openFolder', [$user->id, $folder->parent_id])
                : route('akses-arsip.show', $user->id) }}"
            class="btn btn-sm btn-label-secondary mb-3">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        @endif

        <!-- Folder Grid -->
        <div class="row g-3">
            @if ($folders->isEmpty() && (!isset($files) || $files->isEmpty()))
                {{-- EMPTY STATE --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bx bx-folder-open fs-1 text-muted"></i>

                            <h5 class="mt-3 mb-1">Belum ada folder dan file</h5>
                            <p class="text-muted mb-4">
                                {{ $user->name ?? '' }} Belum Membuat Folder atau File
                            </p>
                        </div>
                    </div>
                </div>
            @else
                {{-- GRID FOLDER --}}
                @foreach ($folders as $folder)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="folder-wrapper position-relative"
                            data-id="{{ $folder->id }}"
                            data-url="{{ route('akses-arsip.openFolder', [$user->id, $folder->id]) }}"
                            data-name="{{ $folder->name }}">

                            <div class="card border-0 shadow-sm folder-card">
                                <div class="card-body text-center">
                                    <i class="bx bxs-folder fs-1"
                                    style="color: {{ $folder->color ?? '#fbc02d' }}"></i>

                                    <h6 class="mt-2 mb-0 text-dark">
                                        {{ $folder->name }}
                                    </h6>

                                    <small class="text-muted">
                                        {{ $folder->files_count }} File
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <!-- end Folder Grid -->

        <!--File Grid -->
        @if (isset($files) && $files->count())
            <div class="mt-4">
                <h6 class="text-muted mb-3">
                    <i class="bx bx-file me-1"></i> File
                </h6>

                @if ($files->count())
                    <div class="row g-3">
                        @foreach ($files as $file)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="file-card position-relative file-wrapper"

                                    data-id="{{ $file->id }}"
                                    data-name="{{ $file->name }}"
                                    data-open-url="{{ route('files.open', $file->id) }}"
                                    data-download-url="{{ route('files.download', $file->id) }}">

                                    {{-- badge storage --}}
                                    <span class="file-badge">
                                        <i class="bx {{ $file->storage_type === 'gdrive' ? 'bxl-google' : 'bx-hdd' }}"></i>
                                    </span>

                                    {{-- preview --}}
                                    <div class="file-preview">
                                        {{-- LOCAL IMAGE --}}
                                        @if($file->storage_type === 'local' && Str::startsWith($file->mime_type, 'image/'))
                                            <img src="{{ route('files.open', $file->id) }}" alt="{{ $file->name }}">

                                        {{-- LOCAL PDF --}}
                                        @elseif($file->storage_type === 'local' && $file->mime_type === 'application/pdf')
                                            <iframe src="{{ route('files.open', $file->id) }}#toolbar=0"></iframe>

                                        {{-- GOOGLE DRIVE --}}
                                        @elseif($file->storage_type === 'gdrive' && googleDrivePreviewUrl($file->drive_url))
                                            <iframe
                                                src="{{ googleDrivePreviewUrl($file->drive_url) }}"
                                                allow="autoplay"
                                            ></iframe>

                                        {{-- FALLBACK --}}
                                        @else
                                            <div class="file-fallback">
                                                <i class="bx bx-file"></i>
                                            </div>
                                        @endif

                                    </div>

                                    {{-- hover overlay --}}
                                    <div class="file-overlay">
                                        <div class="file-title">
                                            {{ $file->name }}
                                        </div>
                                    </div>

                                    {{-- Context Menu --}}
                                    <div class="dropdown-menu shadow context-menu">
                                        @if ($file->storage_type === 'local')
                                            <a href="{{ route('files.download', $file->id) }}" class="dropdown-item" target="_blank">
                                                <i class="bx bx-download me-1"></i> Download
                                            </a>
                                        @else
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                @if ($files->count() === 0)
                    <div class="text-center text-muted py-4">
                        <i class="bx bx-file-blank fs-1"></i>
                        <p class="mt-2 mb-0">
                            Belum ada file di folder ini
                        </p>
                    </div>
                @endif
            </div>
        @endif
        <!-- end File Grid -->

    </div>

    <script>
    document.getElementById('storageType').addEventListener('change', function () {
        const isDrive = this.value === 'gdrive';

        const inputFile = document.getElementById('inputFile').querySelector('input');
        const inputDrive = document.getElementById('inputDrive').querySelector('input');

        document.getElementById('inputFile').classList.toggle('d-none', isDrive);
        document.getElementById('inputDrive').classList.toggle('d-none', !isDrive);

        inputFile.disabled = isDrive;
        inputDrive.disabled = !isDrive;
    });
    </script>

    {{-- Klik Kiri --}}
    <script>
    document.addEventListener('click', function (e) {
        const card = e.target.closest('.folder-card');
        if (!card) return;

        const wrapper = card.closest('.folder-wrapper');
        const url = wrapper.dataset.url;

        window.location.href = url;
    });
    </script>

    {{-- js file --}}
    {{-- klik kiri --}}
    <script>
    document.addEventListener('click', function (e) {

        if (e.button === 2) return;

        const wrapper = e.target.closest('.file-wrapper');
        if (!wrapper) return;

        if (
            e.target.closest('.context-menu') ||
            e.target.closest('button') ||
            e.target.closest('a')
        ) return;

        const url = wrapper.dataset.openUrl;
        if (!url) return;

        window.open(url, '_blank');
    });
    </script>



    {{-- klik kanan --}}
    <script>
        document.addEventListener('contextmenu', function (e) {
            const wrapper = e.target.closest('.file-wrapper');
            if (!wrapper) return;

            e.preventDefault();

            document.querySelectorAll('.file-wrapper .context-menu').forEach(m => {
                m.style.display = 'none';
            });

            const menu = wrapper.querySelector('.context-menu');
            menu.style.display = 'block';
            menu.style.left = e.offsetX + 'px';
            menu.style.top  = e.offsetY + 'px';
        });
    </script>

@endsection
