@extends('app')
@section('title', 'Arsip')
@section('content')
    <style>
        .folder-card{
            border-radius:18px;
            transition:.25s;
            background:#fff;
            cursor:pointer;
            border:1px solid #eceef1;
            border-top:5px solid transparent;
        }

        .folder-card:hover{
            transform:translateY(-4px);
            box-shadow:0 10px 24px rgba(0,0,0,.12);
        }

        .folder-icon{
            width:56px;
            height:56px;
            border-radius:14px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#f5f5f9;
        }

        .folder-icon i{
            font-size:2rem;
            color:#566a7f;
        }

        .folder-name{
            font-weight:600;
            line-height:1.4;
            margin:0;
        }

        .folder-accent{
            width:6px;
            height:22px;
            border-radius:999px;
            display:block;
        }

        .folder-color-dot{
            width:10px;
            height:10px;
            border-radius:50%;
            display:inline-block;
            flex-shrink:0;
        }
        .context-menu{
            display:none;
            position:fixed;
            z-index:9999;

            background:#fff;
            border:0;
            border-radius:12px;
            min-width:200px;

            box-shadow:
                0 4px 18px rgba(0,0,0,.08),
                0 1px 3px rgba(0,0,0,.06);
        }

        .context-menu .dropdown-item{
            padding:.65rem 1rem;
            transition:.15s;
            border-radius:8px;
        }

        .context-menu .dropdown-item:hover{
            background:#f5f5f9;
        }
        .file-card {
            border-radius: 16px;
            overflow: visible;
            cursor: pointer;
            background: #f8f9fa;
            box-shadow: 0 8px 20px rgba(0,0,0,.06);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        /* .file-card:hover {
            transform: translateY(-3px);
            box-shadow:0 12px 28px rgba(0,0,0,.12);
        } */

        /* preview area */
        .file-preview {
            width: 100%;
            height: 180px;
            background: #e9ecef;
            overflow:hidden;
            border-radius:16px 16px 0 0;
        }

        .file-preview img,
        .file-preview iframe {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border: none;
            pointer-events:none;
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

        .file-info{
            padding:12px;
            background:#fff;
            border-radius:0 0 16px 16px;
        }

        .file-name{
            font-weight:600;
            font-size:14px;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
            margin-bottom:6px;
        }

        .file-date{
            font-size:12px;
            color:#8592a3;
            display:flex;
            align-items:center;
            gap:4px;
        }

        .file-sender{
            margin-top:12px;
            padding:8px 10px;
            background:#f5f5f9;
            border-radius:12px;

            display:flex;
            align-items:center;
            gap:10px;
        }

        .sender-avatar{
            width:34px;
            height:34px;
            border-radius:50%;
            overflow:hidden;
            background:#696cff;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:13px;
            font-weight:600;
            flex-shrink:0;
        }

        .sender-avatar img{
            width:100%;
            height:100%;
            object-fit:cover;
        }

        .sender-name{
            font-size:13px;
            font-weight:600;
        }

        .border-dashed {
            border-style: dashed !important;
            border-width: 2px !important;
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

                            <div class="card folder-card h-100" style="border-top:5px solid {{ $folder->color }}">

                                <div class="card-body">

                                    <div class="folder-header mb-3">

                                        <div class="folder-icon">
                                            <i class="bx bxs-folder"></i>
                                        </div>

                                    </div>

                                    <div class="d-flex align-items-center gap-2 mb-1">

                                        <span class="folder-accent"
                                            style="background-color: {{ $folder->color ?? '#fbc02d' }}">
                                        </span>

                                        <h6 class="folder-name mb-0">
                                            {{ $folder->name }}
                                        </h6>

                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">

                                        <small class="text-muted">
                                            {{ $folder->files_count }} File
                                        </small>

                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bx bx-chevron-right text-muted"></i>
                                        </div>

                                    </div>

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

                                        @elseif(
                                            $file->storage_type === 'local' &&
                                            in_array($file->mime_type, [
                                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                                'application/msword'
                                            ])
                                        )

                                            <div class="file-fallback text-primary">
                                                <i class="bx bxs-file-doc fs-1"></i>
                                                <small class="d-block mt-2">Word</small>
                                            </div>

                                        {{-- EXCEL --}}
                                        @elseif(
                                            $file->storage_type === 'local' &&
                                            in_array($file->mime_type, [
                                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                                'application/vnd.ms-excel'
                                            ])
                                        )

                                            <div class="file-fallback text-success">
                                                <i class="bx bxs-spreadsheet fs-1"></i>
                                                <small class="d-block mt-2">Excel</small>
                                            </div>

                                        {{-- POWERPOINT --}}
                                        @elseif(
                                            $file->storage_type === 'local' &&
                                            in_array($file->mime_type, [
                                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                                'application/vnd.ms-powerpoint'
                                            ])
                                        )

                                            <div class="file-fallback text-danger">
                                                <i class="bx bxs-slideshow fs-1"></i>
                                                <small class="d-block mt-2">PowerPoint</small>
                                            </div>

                                        {{-- FALLBACK --}}
                                        @else
                                            <div class="file-fallback">
                                                <i class="bx bx-file"></i>
                                            </div>
                                        @endif

                                    </div>

                                    <div class="file-info">

                                        <div class="file-name"
                                            title="{{ $file->name }}">

                                            {{ $file->name }}

                                        </div>

                                        @if($file->document_date)
                                            <div class="file-date">

                                                <i class="bx bx-calendar"></i>

                                                {{ \Carbon\Carbon::parse($file->document_date)->format('d M Y') }}

                                            </div>
                                        @endif

                                        @if($file->sender)

                                            <div class="file-sender">

                                                <div class="sender-avatar">

                                                    @if($file->sender->foto)

                                                        <img src="{{ asset('storage/'.$file->sender->foto) }}">

                                                    @else

                                                        {{ strtoupper(substr($file->sender->name,0,1)) }}

                                                    @endif

                                                </div>

                                                <div>

                                                    <small class="text-muted d-block">
                                                        Dikirim oleh
                                                    </small>

                                                    <span class="sender-name">
                                                        {{ $file->sender->name }}
                                                    </span>

                                                </div>

                                            </div>

                                        @endif

                                    </div>

                                    {{-- Context Menu --}}
                                    @if ($file->storage_type === 'local')
                                    <div class="dropdown-menu shadow context-menu">
                                        <a href="{{ route('files.download', $file->id) }}" class="dropdown-item" target="_blank">
                                            <i class="bx bx-download me-1"></i> Download
                                        </a>
                                    </div>
                                    @else
                                    @endif
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

        const wrapper = e.target.closest(
            '.folder-wrapper, .file-wrapper'
        );

        if (!wrapper) return;

        e.preventDefault();

        // tutup semua menu
        document.querySelectorAll('.context-menu').forEach(menu => {
            menu.style.display = 'none';
        });

        // ambil menu milik item yg diklik
        const menu = wrapper.querySelector('.context-menu');

        if (!menu) return;

        menu.style.display = 'block';

        menu.style.left = `${e.clientX}px`;
        menu.style.top  = `${e.clientY}px`;

    });

    // klik kiri di luar => tutup
    document.addEventListener('click', function () {
        document.querySelectorAll('.context-menu').forEach(menu => {
            menu.style.display = 'none';
        });
    });
    </script>

@endsection
