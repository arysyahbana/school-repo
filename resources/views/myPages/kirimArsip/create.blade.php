@extends('app')
@section('title', 'Kirim Arsip')

@section('content')
    <script>
    const USERS = @json($users);
    </script>
    <style>
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

        .btn-check:checked + .card{
            border-color:#696cff !important;
            background:#f5f5ff;
            box-shadow:0 0 0 .15rem rgba(105,108,255,.15);
        }

        .cursor-pointer{
            cursor:pointer;
            transition:.2s;
        }

        .cursor-pointer:hover{
            transform:translateY(-2px);
        }
    </style>

    <form action="{{ route('kirim-arsip.store') }}"
      method="POST"
      enctype="multipart/form-data">
        @csrf
        <div class="card">
            <h5 class="card-header">Kirim Arsip</h5>

            <div class="card-body">

                {{-- Informasi --}}
                <div class="alert alert-primary d-flex align-items-center mb-4">
                    <i class="bx bx-info-circle me-2"></i>
                    Arsip yang dikirim akan otomatis masuk ke folder root arsip penerima.
                </div>

                <div class="row">

                    {{-- FORM KIRI --}}
                    <div class="col-lg-7">

                        <div class="card border shadow-none">
                            <div class="card-body">

                                <h6 class="mb-4">Informasi Arsip</h6>

                                {{-- Nama Arsip --}}
                                <div class="mb-4">
                                    <label class="form-label">
                                        Nama Arsip <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" name="name"
                                        class="form-control"
                                        placeholder="Contoh : SK Pembagian Tugas Tahun 2026" required>
                                </div>

                                {{-- Tanggal Arsip --}}
                                <div class="mb-4">
                                    <label class="form-label">
                                        Tanggal Arsip <span class="text-danger">*</span>
                                    </label>

                                    <input type="date" name="document_date"
                                        class="form-control"
                                        placeholder="Contoh : SK Pembagian Tugas Tahun 2026">
                                </div>

                                {{-- Jenis Upload --}}
                                <div class="mb-4">

                                    <label class="form-label d-block">
                                        Jenis Upload <span class="text-danger">*</span>
                                    </label>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input"
                                            type="radio"
                                            name="storage_type"
                                            value="local"
                                            id="upload_file"
                                            checked>

                                        <label class="form-check-label" for="upload_file">
                                            Upload File
                                        </label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input"
                                            type="radio"
                                            name="storage_type"
                                            value="gdrive"
                                            id="link_drive">

                                        <label class="form-check-label" for="link_drive">
                                            Link Google Drive
                                        </label>
                                    </div>

                                </div>

                                {{-- Upload File --}}
                                <div id="area-upload">

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

                                {{-- Link Drive --}}
                                <div id="area-drive" style="display:none">

                                    <label class="form-label">
                                        Link Google Drive <span class="text-danger">*</span>
                                    </label>

                                    <input type="url"
                                        class="form-control"
                                        placeholder="https://drive.google.com/..."
                                        name="drive_url">

                                </div>

                            </div>
                        </div>

                    </div>

                    {{-- PENERIMA --}}
                    <div class="col-lg-5">

                        <div class="card border shadow-none">
                            <div class="card-body">
                                <h6 class="mb-4">Pilih Penerima</h6>
                                <div class="row g-3 mb-4">

                                    <div class="col-md-6">
                                        <input type="radio"
                                            class="btn-check recipient-type"
                                            name="recipient_type"
                                            id="semua"
                                            value="all">

                                        <label class="card border shadow-none h-100 p-3 cursor-pointer"
                                            for="semua">

                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-group fs-2 text-primary me-3"></i>

                                                <div>
                                                    <h6 class="mb-1">Semua User</h6>
                                                    <small class="text-muted">
                                                        Kirim ke seluruh pengguna
                                                    </small>
                                                </div>
                                            </div>

                                        </label>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="radio"
                                            class="btn-check recipient-type"
                                            name="recipient_type"
                                            id="guru"
                                            value="guru">

                                        <label class="card border shadow-none h-100 p-3"
                                            for="guru">

                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-book-reader fs-2 text-success me-3"></i>

                                                <div>
                                                    <h6 class="mb-1">Semua Guru</h6>
                                                    <small class="text-muted">
                                                        Hanya guru
                                                    </small>
                                                </div>
                                            </div>

                                        </label>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="radio"
                                            class="btn-check recipient-type"
                                            name="recipient_type"
                                            id="tendik"
                                            value="tendik">

                                        <label class="card border shadow-none h-100 p-3"
                                            for="tendik">

                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-briefcase fs-2 text-warning me-3"></i>

                                                <div>
                                                    <h6 class="mb-1">Semua Tendik</h6>
                                                    <small class="text-muted">
                                                        Kaur & Tata Usaha
                                                    </small>
                                                </div>
                                            </div>

                                        </label>
                                    </div>

                                    <div class="col-md-6">
                                        <input type="radio"
                                            class="btn-check recipient-type"
                                            name="recipient_type"
                                            id="custom"
                                            value="custom"
                                            checked>

                                        <label class="card border shadow-none h-100 p-3"
                                            for="custom">

                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-user-plus fs-2 text-info me-3"></i>

                                                <div>
                                                    <h6 class="mb-1">Pilih Perorangan</h6>
                                                    <small class="text-muted">
                                                        Tentukan penerima sendiri
                                                    </small>
                                                </div>
                                            </div>

                                        </label>
                                    </div>

                                </div>

                                <div id="select2-container">
                                    <select class="js-example-basic-single form-control"
                                        name="users[]"
                                        multiple="multiple">

                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }} ({{ $user->jabatan }})
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div id="preview-users" style="display:none">
                                    <div class="border rounded p-3 bg-light">

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">
                                                Daftar Penerima
                                            </h6>

                                            <span id="total-users"
                                                class="badge bg-primary">
                                                0 User
                                            </span>
                                        </div>

                                        <div id="user-list"
                                            style="max-height:300px; overflow-y:auto;">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="card border shadow-none mt-4">
                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="mb-1">Informasi Pengiriman</h6>

                            <small class="text-muted">
                                Arsip akan masuk ke folder root arsip penerima.
                            </small>
                        </div>

                        <div>
                            <button type="button"
                                    class="btn btn-outline-secondary me-2">
                                Batal
                            </button>

                            <button class="btn btn-primary">
                                <i class="bx bx-send me-1"></i>
                                Kirim Arsip
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {

        const fileRadio = document.getElementById('upload_file');
        const driveRadio = document.getElementById('link_drive');

        const areaUpload = document.getElementById('area-upload');
        const areaDrive = document.getElementById('area-drive');

        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('fileInput');
        const fileName = document.getElementById('fileName');
        const btnPilihFile = document.getElementById('btnPilihFile');

        // Toggle Upload / Drive
        fileRadio.addEventListener('change', function() {
            areaUpload.style.display = 'block';
            areaDrive.style.display = 'none';
        });

        driveRadio.addEventListener('change', function() {
            areaUpload.style.display = 'none';
            areaDrive.style.display = 'block';
        });

        // Klik area
        dropArea.addEventListener('click', function() {
            fileInput.click();
        });

        btnPilihFile.addEventListener('click', function(e) {
            e.stopPropagation();
            fileInput.click();
        });

        // Pilih file manual
        fileInput.addEventListener('change', function() {

            if (this.files.length > 0) {

                fileName.innerHTML = `
                    <i class="bx bx-file"></i>
                    ${this.files[0].name}
                `;

            }

        });

        // Drag masuk
        ['dragenter', 'dragover'].forEach(eventName => {

            dropArea.addEventListener(eventName, function(e) {

                e.preventDefault();
                e.stopPropagation();

                dropArea.classList.add('drag-over');

            });

        });

        // Drag keluar
        ['dragleave', 'drop'].forEach(eventName => {

            dropArea.addEventListener(eventName, function(e) {

                e.preventDefault();
                e.stopPropagation();

                dropArea.classList.remove('drag-over');

            });

        });

        // Drop file
        dropArea.addEventListener('drop', function(e) {

            const files = e.dataTransfer.files;

            if (files.length > 0) {

                fileInput.files = files;

                fileName.innerHTML = `
                    <i class="bx bx-file"></i>
                    ${files[0].name}
                `;

            }

        });

    });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const radios = document.querySelectorAll('.recipient-type');

            const select2Container = document.getElementById('select2-container');
            const previewUsers = document.getElementById('preview-users');
            const userList = document.getElementById('user-list');
            const totalUsers = document.getElementById('total-users');

            function renderUsers(users)
            {
                userList.innerHTML = '';

                totalUsers.innerText = users.length + ' User';

                users.forEach(user => {

                    userList.innerHTML += `
                        <div class="d-flex align-items-center border rounded p-2 mb-2 bg-white">

                            <div class="avatar avatar-sm me-3">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                    ${user.name.charAt(0)}
                                </span>
                            </div>

                            <div>
                                <div class="fw-semibold">
                                    ${user.name}
                                </div>

                                <small class="text-muted">
                                    ${user.jabatan ?? '-'}
                                </small>
                            </div>

                        </div>
                    `;

                });
            }

            radios.forEach(radio => {

                radio.addEventListener('change', function () {

                    let selectedUsers = [];

                    if (this.value === 'custom') {

                        select2Container.style.display = 'block';
                        previewUsers.style.display = 'none';
                        return;
                    }

                    select2Container.style.display = 'none';
                    previewUsers.style.display = 'block';

                    if (this.value === 'all') {

                        selectedUsers = USERS;

                    } else if (this.value === 'guru') {

                        selectedUsers = USERS.filter(user =>
                            user.jabatan &&
                            (
                                user.jabatan.toLowerCase().includes('guru') ||
                            user.jabatan.toLowerCase().includes('wakil')
                            )
                        );

                    } else if (this.value === 'tendik') {

                        selectedUsers = USERS.filter(user =>
                            user.jabatan &&
                            (
                                user.jabatan.toLowerCase().includes('kaur') ||
                                user.jabatan.toLowerCase().includes('tu') ||
                                user.jabatan.toLowerCase().includes('tata usaha')
                            )
                        );

                    }

                    renderUsers(selectedUsers);

                });

            });

        });
    </script>
@endsection
