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

                            <!-- Context Menu -->
                            <div class="dropdown-menu shadow context-menu">
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

          {{-- <button class="btn btn-outline-secondary"
              data-bs-toggle="modal"
              data-bs-target="#modalUpload">
            <i class="bx bxl-google me-1"></i> Link Google Drive
          </button> --}}
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

        <label class="form-label">Jenis Upload</label>
        <select name="storage_type" id="storageType" class="form-select" required>
            <option value="local" selected>Upload File</option>
            <option value="gdrive">Link Google Drive</option>
        </select>

        <!-- Upload Local -->
        <div class="mt-3" id="inputFile">
           <label class="form-label">Pilih File</label>
           <input type="file" name="file" class="form-control">
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
                    <h5 class="modal-title">Edit Nama File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="text"
                           name="name"
                           id="renameFileInput"
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

{{-- Klik Kanan --}}
<script>
let activeMenu = null;
let activeFolder = null;

document.addEventListener('contextmenu', function (e) {
    const folder = e.target.closest('.folder-wrapper');
    if (!folder) return;

    e.preventDefault();

    // tutup menu lain
    document.querySelectorAll('.context-menu').forEach(menu => {
        menu.style.display = 'none';
    });

    const menu = folder.querySelector('.context-menu');
    menu.style.display = 'block';
    menu.style.left = e.offsetX + 'px';
    menu.style.top  = e.offsetY + 'px';

    activeMenu = menu;
    activeFolder = folder;
});

// klik di luar → tutup menu
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

{{-- edit file --}}
<script>
    document.querySelectorAll('.file-wrapper .btn-edit').forEach(btn => {
        btn.addEventListener('click', function () {
            const wrapper = this.closest('.file-wrapper');

            document.getElementById('renameFileInput').value =
                wrapper.dataset.name;

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
