@extends('app')
@section('title', 'User')
@section('content')
    <!-- Hoverable Table rows -->
    <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3 px-5">
        <h5 class="mb-0">User</h5>
        <form method="GET" action="{{ route('user.index') }}">
            <div class="input-group shadow-sm">

                <span class="input-group-text">
                    <i class="bx bx-search"></i>
                </span>

                <input type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari nama pegawai..."
                    value="{{ request('search') }}">

                @if(request('search'))
                    <a href="{{ route('user.index') }}"
                    class="btn btn-outline-secondary">
                        Reset
                    </a>
                @endif

            </div>
        </form>
    </div>
    <div class="table-responsive text-nowrap px-5">
        <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary mx-3 mb-2"><i class="bx bx-plus-circle me-1"></i> Tambah</a>
        <table class="table table-hover text-center">
        <thead>
            <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Foto</th>
            <th>Email</th>
            <th>Jabatan</th>
            <th>Actions</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse ($users as $user)
            <tr>
                <td><i class="fab fa-angular fa-lg text-danger me-3"></i>{{ $users->firstItem() + $loop->index }}</td>
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
                 <td><i class="fab fa-angular fa-lg text-danger me-3"></i>{{ $user->email ?? '-' }}</td>
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
                <td>
                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $user->id }}"><i class="bx bx-trash me-1"></i> Hapus</button>
                </td>
            </tr>
            <!-- Modal Hapus -->
            <div class="modal fade" id="modalHapus{{ $user->id }}" tabindex="-1" aria-labelledby="modalHapusLabel{{ $user->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title text-danger d-flex align-items-center gap-2" id="modalHapusLabel">

                            <!-- SVG Icon Hapus -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                                class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm4 0A.5.5 0 0 1 10 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5z"/>
                                <path fill-rule="evenodd"
                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 2v1h11V2h-11z"/>
                            </svg>

                            Konfirmasi Hapus
                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body text-center">
                            <p class="mb-0">
                            Apakah Anda yakin ingin menghapus data ini? {{ $user->id }}
                            <br>
                            <small class="text-muted">Data yang sudah dihapus tidak dapat dikembalikan.</small>
                            </p>
                        </div>

                        <div class="modal-footer border-0 justify-content-center">
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                            Batal
                            </button>
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger px-4">
                                    Ya, Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <tr>
                <td colspan="6" class="py-5 text-center">

                    <i class="bx bx-search-alt fs-1 text-muted d-block mb-2"></i>

                    <div class="text-muted">
                        Tidak ditemukan data untuk
                        <strong>"{{ request('search') }}"</strong>
                    </div>

                </td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>
    <div class="card-footer px-5">
        {{ $users->onEachSide(1)->links() }}
    </div>
    </div>
    <!--/ Hoverable Table rows -->

@endsection
