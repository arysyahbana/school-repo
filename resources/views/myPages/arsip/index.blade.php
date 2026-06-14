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

        .upload-area {
            cursor: pointer;
            transition: all .2s ease;
            min-height: 280px;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .upload-area.drag-over {
            border-color: #696cff !important;
            background-color: #f5f5ff;
        }

        .upload-area:hover {
            border-color: #696cff !important;
            background: #f8f9ff;
        }
    </style>

@php use Illuminate\Support\Str; @endphp

    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="bx bx-folder me-1"></i>
                {{ isset($folder) ? $folder->name : 'Arsip Saya' }}
            </h5>

            <button class="btn btn-sm btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#modalCreate">
                <i class="bx bx-plus me-1"></i> Baru
            </button>
        </div>


        @if(isset($folder))
            <a href="{{ $folder->parent_id
                ? route('folders.show', $folder->parent_id)
                : route('arsip.index') }}"
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
                                Mulai dengan membuat folder pertama Anda.
                            </p>

                            <button class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalCreate">
                                <i class="bx bx-plus me-1"></i> Buat Folder
                            </button>
                        </div>
                    </div>
                </div>
            @else
                {{-- GRID FOLDER --}}
                @foreach ($folders as $folder)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="folder-wrapper position-relative"
                            data-id="{{ $folder->id }}"
                            data-url="{{ route('folders.show', $folder->id) }}"
                            data-name="{{ $folder->name }}"
                            data-color="{{ $folder->color ?? '#facc15' }}"
                            data-update-url="{{ route('folders.update', $folder->id) }}"
                            data-delete-url="{{ route('folders.destroy', $folder->id) }}">

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

                            <!-- Context Menu -->
                            <div class="shadow context-menu">
                                <button class="dropdown-item btn-rename">
                                    <i class="bx bx-edit me-1"></i> Rename
                                </button>
                                <button class="dropdown-item btn-color">
                                    <i class="bx bx-palette"></i> Ganti Warna
                                </button>
                                <form method="POST" class="form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item text-danger">
                                        <i class="bx bx-trash me-1"></i> Hapus
                                    </button>
                                </form>
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
                                    data-document-date="{{ $file->document_date }}"
                                    data-open-url="{{ route('files.open', $file->id) }}"
                                    data-download-url="{{ route('files.download', $file->id) }}"
                                    data-delete-url="{{ route('files.destroy', $file->id) }}"
                                    data-update-url="{{ route('files.update', $file->id) }}"
                                    data-move-url="{{ route('files.move', $file->id) }}">

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

                                        {{-- WORD --}}
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
                                    <div class="shadow context-menu">
                                        <button class="dropdown-item btn-edit">
                                            <i class="bx bx-edit me-1"></i> Edit
                                        </button>

                                        @if ($file->storage_type === 'local')
                                            <a href="{{ route('files.download', $file->id) }}" class="dropdown-item" target="_blank">
                                                <i class="bx bx-download me-1"></i> Download
                                            </a>
                                        @else
                                        @endif

                                        <button class="dropdown-item btn-move"
                                        data-move-url="{{ route('files.move', $file->id) }}">
                                            <i class="bx bx-folder me-1"></i> Pindahkan
                                        </button>

                                        <form method="POST" class="form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item text-danger">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </button>
                                        </form>
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

{{-- pilih menu --}}
<div class="modal fade" id="modalCreate" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
            <i class="bx bx-plus-circle me-1"></i> Tambah Data
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="d-grid gap-2">
          <button class="btn btn-outline-primary"
              data-bs-toggle="modal"
              data-bs-target="#modalFolder">
            <i class="bx bx-folder-plus me-1"></i> Folder
          </button>

          <button class="btn btn-outline-success"
              data-bs-toggle="modal"
              data-bs-target="#modalUpload">
            <i class="bx bx-upload me-1"></i> Upload File
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Buat Folder --}}
<div class="modal fade" id="modalFolder" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('folders.store') }}">
      @csrf
      <input type="hidden" name="parent_id" value="{{ $currentFolderId ?? '' }}">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="bx bx-folder-plus me-1"></i> Buat Folder
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <label class="form-label">Nama Folder</label>
          <input type="text" name="name" class="form-control" required>

          <label class="form-label mt-2">Warna Folder</label>
          <input type="color" name="color" class="form-control form-control-color">
        </div>

        <div class="modal-footer">
          <button class="btn btn-label-secondary" data-bs-dismiss="modal">
            <i class="bx bx-x"></i> Batal
          </button>
          <button class="btn btn-primary">
            <i class="bx bx-save"></i> Simpan
          </button>
        </div>
      </div>
    </form>
  </div>
</div>


{{-- Upload File --}}
<div class="modal fade" id="modalUpload" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('files.store') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="folder_id" value="{{ $currentFolderId ?? '' }}">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="bx bx-upload me-1"></i> Upload File
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
        <label class="form-label">Nama File</label>
        <input type="text" name="name" class="form-control" placeholder="Nama file..." required>

        <label class="form-label mt-3">Tanggal File</label>
        <input type="date" name="document_date" class="form-control" required>

        <label class="form-label mt-3">Jenis Upload</label>
        <select name="storage_type" id="storageType" class="form-select" required>
            <option value="local" selected>Upload File</option>
            <option value="gdrive">Link Google Drive</option>
        </select>

        <!-- Upload Local -->
        <div class="mt-3" id="inputFile">

            <label class="form-label">
                File Arsip <span class="text-danger">*</span>
            </label>

            <div id="dropArea"
                class="border border-dashed rounded p-5 text-center upload-area">

                <i class="bx bx-cloud-upload fs-1 text-primary"></i>

                <h5 class="mt-2">
                    Drag & Drop File
                </h5>

                <p class="text-muted mb-3">
                    atau klik area ini untuk memilih file
                </p>

                <input type="file"
                    id="fileInput"
                    name="file"
                    class="d-none">

                <button type="button"
                    class="btn btn-outline-primary"
                    id="btnPilihFile">
                    Pilih File
                </button>

                <div id="fileName"
                    class="mt-3 fw-semibold text-success">
                </div>

                <small class="text-muted d-block mt-3">
                    PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG
                </small>

            </div>

        </div>

          <!-- Link Google Drive -->
          <div class="mt-3 d-none" id="inputDrive">
            <label class="form-label">Link Google Drive</label>
            <input type="url" name="drive_url" class="form-control"
                   placeholder="https://drive.google.com/...">
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-label-secondary" data-bs-dismiss="modal">
            <i class="bx bx-x"></i> Batal
          </button>
          <button class="btn btn-success">
            <i class="bx bx-upload"></i> Simpan
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Rename --}}
<div class="modal fade" id="renameModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="renameForm">
            @csrf
            @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rename Folder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="text"
                           name="name"
                           id="renameInput"
                           class="form-control"
                           required>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Delete Folder --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        Hapus Folder
                    </h5>
                </div>

                <div class="modal-body">
                    <p>
                        Yakin hapus folder
                        <strong id="deleteFolderName"></strong>?
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Ganti warna Folder --}}
<div class="modal fade" id="colorModal" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <form id="colorForm" method="POST">
      @csrf
      @method('PUT')

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="bx bx-palette me-1"></i> Ganti Warna Folder
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body text-center">
          <input type="color"
                 name="color"
                 id="colorInput"
                 class="form-control form-control-color mx-auto"
                 style="width: 120px; height: 50px;">
        </div>

        <div class="modal-footer">
          <button class="btn btn-label-secondary" data-bs-dismiss="modal">
            Batal
          </button>
          <button class="btn btn-primary">
            Simpan
          </button>
        </div>
      </div>
    </form>
  </div>
</div>


{{-- edit file --}}
<div class="modal fade" id="renameFileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="renameFileForm">
            @csrf
            @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label for="" class="">Nama File</label>
                    <input type="text"
                           name="name"
                           id="renameFileInput"
                           class="form-control"
                           required>
                    <label for="" class="mt-3">Tanggal File</label>
                    <input type="date"
                           name="document_date"
                           id="renameFileDate"
                           class="form-control"
                           required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- hapus file --}}
<div class="modal fade" id="deleteFileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="deleteFileForm" class="form-delete-file">
            @csrf
            @method('DELETE')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Hapus File</h5>
                </div>

                <div class="modal-body">
                    <p>Yakin hapus file <strong id="deleteFileName"></strong>?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Move File --}}
<div class="modal fade" id="moveFileModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" id="moveFileForm">
      @csrf
      @method('PUT')

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Move File</h5>
        </div>

        <div class="modal-body">
          <select name="folder_id" class="form-select">
            <option value="">📂 Root</option>
            @foreach($folders as $folder)
              <option value="{{ $folder->id }}">
                📂 {{ $folder->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancel
          </button>
          <button type="submit" class="btn btn-primary">
            Move
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- <script>
document.getElementById('storageType').addEventListener('change', function () {
    const isDrive = this.value === 'gdrive';

    const inputFile = document.getElementById('inputFile').querySelector('input');
    const inputDrive = document.getElementById('inputDrive').querySelector('input');

    document.getElementById('inputFile').classList.toggle('d-none', isDrive);
    document.getElementById('inputDrive').classList.toggle('d-none', !isDrive);

    inputFile.disabled = isDrive;
    inputDrive.disabled = !isDrive;
});
</script> --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    // =========================
    // STORAGE TYPE
    // =========================
    const storageType = document.getElementById('storageType');

    storageType.addEventListener('change', function () {

        const isDrive = this.value === 'gdrive';

        const inputFile = document.getElementById('inputFile');
        const inputDrive = document.getElementById('inputDrive');

        inputFile.classList.toggle('d-none', isDrive);
        inputDrive.classList.toggle('d-none', !isDrive);

    });

    // =========================
    // DRAG & DROP
    // =========================
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');
    const btnPilihFile = document.getElementById('btnPilihFile');

    // klik area
    dropArea.addEventListener('click', function () {
        fileInput.click();
    });

    // klik tombol pilih file
    btnPilihFile.addEventListener('click', function (e) {
        e.stopPropagation();
        fileInput.click();
    });

    // pilih file manual
    fileInput.addEventListener('change', function () {

        if (this.files.length > 0) {

            tampilkanFile(this.files[0]);

        }

    });

    // drag masuk
    ['dragenter', 'dragover'].forEach(eventName => {

        dropArea.addEventListener(eventName, function (e) {

            e.preventDefault();
            e.stopPropagation();

            dropArea.classList.add('border-primary');
            dropArea.classList.add('bg-light');

        });

    });

    // drag keluar
    ['dragleave', 'drop'].forEach(eventName => {

        dropArea.addEventListener(eventName, function (e) {

            e.preventDefault();
            e.stopPropagation();

            dropArea.classList.remove('border-primary');
            dropArea.classList.remove('bg-light');

        });

    });

    // drop file
    dropArea.addEventListener('drop', function (e) {

        const files = e.dataTransfer.files;

        if (files.length > 0) {

            fileInput.files = files;

            tampilkanFile(files[0]);

        }

    });

    // helper
    function tampilkanFile(file)
    {
        const ukuranKB = (file.size / 1024).toFixed(2);

        fileName.innerHTML = `
            <div class="alert alert-success mt-3 mb-0">
                <div class="fw-bold">
                    <i class="bx bx-file"></i>
                    ${file.name}
                </div>

                <small>
                    ${ukuranKB} KB
                </small>
            </div>
        `;
    }

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

{{-- Klik Kanan --}}
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


{{-- rename --}}
<script>
document.querySelectorAll('.btn-rename').forEach(btn => {
    btn.addEventListener('click', function () {
        const folder = activeFolder;

        const id   = folder.dataset.id;
        const name = folder.dataset.name;

        document.getElementById('renameInput').value = name;
        document.getElementById('renameForm').action = folder.dataset.updateUrl;

        const modal = new bootstrap.Modal(
            document.getElementById('renameModal')
        );
        modal.show();
    });
});
</script>


{{-- delete --}}
<script>
document.querySelectorAll('.form-delete button').forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.preventDefault();

        const folder = activeFolder;
        const id     = folder.dataset.id;
        const name   = folder.dataset.name;

        document.getElementById('deleteFolderName').innerText = name;
        document.getElementById('deleteForm').action = folder.dataset.deleteUrl;

        const modal = new bootstrap.Modal(
            document.getElementById('deleteModal')
        );
        modal.show();
    });
});
</script>

{{-- Ganti Warna --}}
<script>
document.querySelectorAll('.btn-color').forEach(btn => {
    btn.addEventListener('click', function () {
        const folder = activeFolder;

        document.getElementById('colorForm').action =
            folder.dataset.updateUrl;

        document.getElementById('colorInput').value =
            folder.dataset.color ?? '#facc15';

        new bootstrap.Modal(
            document.getElementById('colorModal')
        ).show();
    });
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

{{-- edit file --}}
<script>
    document.querySelectorAll('.file-wrapper .btn-edit').forEach(btn => {

    btn.addEventListener('click', function () {

        const wrapper = this.closest('.file-wrapper');

        document.getElementById('renameFileInput').value =
            wrapper.dataset.name || '';

        document.getElementById('renameFileDate').value =
            wrapper.dataset.documentDate || '';

        document.getElementById('renameFileForm').action =
            wrapper.dataset.updateUrl;

        new bootstrap.Modal(
            document.getElementById('renameFileModal')
        ).show();

    });

});
</script>

{{-- delete file --}}
<script>
document.querySelectorAll('.file-wrapper .form-delete button')
    .forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            const wrapper = this.closest('.file-wrapper');

            document.getElementById('deleteFileName').innerText =
                wrapper.dataset.name;

            document.getElementById('deleteFileForm').action =
                wrapper.dataset.deleteUrl;

            new bootstrap.Modal(
                document.getElementById('deleteFileModal')
            ).show();
        });
    });
</script>

{{-- move file --}}
<script>
document.addEventListener('click', function (e) {

    const btn = e.target.closest('.btn-move');
    if (!btn) return;

    const moveUrl = btn.dataset.moveUrl;

    const form = document.getElementById('moveFileForm');
    form.action = moveUrl;

    new bootstrap.Modal(
        document.getElementById('moveFileModal')
    ).show();
});
</script>

@endsection
