@extends('app')
@section('title', 'Detail Kirim Arsip')

@section('content')

<div class="row">

    {{-- HEADER --}}
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">

                <div class="d-flex align-items-center">

                    <div class="avatar avatar-lg me-3">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="bx bx-file fs-2"></i>
                        </span>
                    </div>

                    <div>
                        <h4 class="mb-1">
                            {{ $arsip->name }}
                        </h4>

                        <small class="text-muted">
                            Dikirim oleh
                            <strong>{{ $arsip->sender->name }}</strong>
                            •
                            {{ $arsip->created_at->format('d M Y H:i') }}
                        </small>
                    </div>

                </div>

                <div>

                    @if($arsip->storage_type == 'local')
                        <a href="{{ route('files.download', $arsip->id) }}"
                           class="btn btn-primary">

                            <i class="bx bx-download me-1"></i>
                            Download

                        </a>
                    @endif

                    @if($arsip->storage_type == 'gdrive')
                        <a href="{{ $arsip->drive_url }}"
                           target="_blank"
                           class="btn btn-success">

                            <i class="bx bx-link-external me-1"></i>
                            Buka Drive

                        </a>
                    @endif

                </div>

            </div>
        </div>
    </div>

    {{-- INFORMASI --}}
    <div class="col-lg-5">

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Informasi Arsip</h6>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Nama Arsip
                            </small>

                            <strong>
                                {{ $arsip->name }}
                            </strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Jenis Upload
                            </small>

                            <strong>
                                {{ strtoupper($arsip->storage_type) }}
                            </strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Tanggal Arsip
                            </small>

                            <strong>
                                {{-- {{ $arsip->document_date?->format('d M Y') ?? '-' }} --}}
                            </strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Pengirim
                            </small>

                            <strong>
                                {{ $arsip->sender->name }}
                            </strong>
                        </div>

                        <div>
                            <small class="text-muted d-block">
                                Total Penerima
                            </small>

                            <strong>
                                {{ $penerima->count() }} Orang
                            </strong>
                        </div>

                    </div>
                </div>
            </div>
            {{-- PENERIMA --}}
            <div class="col-12">

                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center">

                        <h6 class="mb-0">
                            Daftar Penerima
                        </h6>

                        <span class="badge bg-primary">
                            {{ $penerima->count() }} Orang
                        </span>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($penerima as $user)

                                    <tr>

                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            {{ $user->name }}
                                        </td>

                                        <td>
                                            @if ($user->jabatan == 'admin')
                                                <span class="badge bg-label-danger me-1">Admin</span>
                                            @elseif($user->jabatan == 'kepala_madrasah')
                                                <span class="badge bg-label-primary me-1">Kepala</span>
                                            @elseif($user->jabatan == 'wakil')
                                                <span class="badge bg-label-success me-1">Wakil</span>
                                            @elseif($user->jabatan == 'guru')
                                                <span class="badge bg-label-info me-1">Guru</span>
                                            @elseif($user->jabatan == 'kaur')
                                                <span class="badge bg-label-warning me-1">Kaur</span>
                                            @elseif($user->jabatan == 'tu')
                                                <span class="badge bg-label-secondary me-1">TU</span>
                                            @endif
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>
        </div>

    </div>

    {{-- PREVIEW --}}
    <div class="col-lg-7">

        <div class="card mb-4">

            <div class="card-header">
                <h6 class="mb-0">Preview Arsip</h6>
            </div>

            <div class="card-body">

                @if($arsip->storage_type == 'local' &&
                    str_contains($arsip->mime_type, 'pdf'))

                    <iframe
                        src="{{ asset('storage/'.$arsip->path) }}"
                        width="100%"
                        height="600"
                        style="border:none;">
                    </iframe>

                @elseif($arsip->storage_type == 'gdrive' && googleDrivePreviewUrl($arsip->drive_url))

                    <iframe
                        src="{{ googleDrivePreviewUrl($arsip->drive_url) }}"
                        width="100%"
                        height="700"
                        style="border:none;">
                    </iframe>
                @else

                    <div class="text-center py-5">

                        <i class="bx bx-file fs-1 text-primary"></i>

                        <h5 class="mt-3">
                            Preview tidak tersedia
                        </h5>

                        <p class="text-muted">
                            Silakan download file untuk melihat isi arsip.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection
