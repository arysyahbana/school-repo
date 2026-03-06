@extends('app')
@section('title', 'Akses Arsip')
@section('content')
    <!-- Hoverable Table rows -->
    <div class="card">
    <h5 class="card-header">Akses Arsip</h5>
    <div class="table-responsive text-nowrap">
        {{-- <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary mx-3 mb-2"><i class="bx bx-plus-circle me-1"></i> Tambah</a> --}}
        <table class="table table-hover text-center">
        <thead>
            <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Foto</th>
            <th>Jabatan</th>
            <th>Jumlah File</th>
            <th>Actions</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach ($users as $user)
            <tr>
                <td><i class="fab fa-angular fa-lg text-danger me-3"></i>{{ $loop->iteration ?? '' }}</td>
                <td>{{ $user->name ?? '-' }}</td>
                <td class="">
                  <div
                        data-bs-toggle="tooltip"
                        data-popup="tooltip-custom"
                        data-bs-placement="top"
                        class="avatar avatar-md pull-up"
                        title="{{ $user->name ?? 'Anonymous' }}"
                    >
                        @if ($user->foto == '')
                            <img src="{{ asset('dist/assets/img/avatars/5.png') }}" alt="Avatar" class="rounded-circle" />
                        @else
                            <img src="{{ asset('storage/'.$user->foto) }}" alt="Avatar" class="rounded-circle" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;" />
                        @endif
                    </div>
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
                <td>{{ $user->files_count }} file</td>
                <td>
                    <a href="{{ route('akses-arsip.show', $user->id) }}" class="btn btn-sm btn-info"><i class="bx bx-show me-1"></i> Buka</a>
                </td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
    </div>
    <!--/ Hoverable Table rows -->

@endsection
