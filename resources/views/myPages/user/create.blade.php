@extends('app')
@section('title', 'Tambah User')
@section('content')
    <!-- Hoverable Table rows -->
    <div class="card">
    <div class="d-flex justify-content-between">
        <h5 class="card-header">Tambah User</h5>
        <div class="my-auto">
            <a href="javascript:history.back()" class="btn btn-sm btn-secondary mx-3 mb-2">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>
    </div>
        <div class="">
            <div class="row">
                <div class="col-xxl">
                    <div class="mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data">
                            @csrf
                            <!-- Nama -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="nama">Nama</label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-sm input-group-merge">
                                        <span class="input-group-text">
                                            <i class="bx bx-user"></i>
                                        </span>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="nama"
                                            name="name"
                                            placeholder="Nama Lengkap"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Foto -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="foto">Foto</label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-sm input-group-merge">
                                        <span class="input-group-text">
                                            <i class="bx bx-image"></i>
                                        </span>
                                        <input
                                            type="file"
                                            id="foto"
                                            class="form-control"
                                            name="foto"
                                            accept="image/*"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="email">Email</label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-sm input-group-merge">
                                        <span class="input-group-text">
                                            <i class="bx bx-envelope"></i>
                                        </span>
                                        <input
                                            type="email"
                                            id="email"
                                            class="form-control"
                                            name="email"
                                            placeholder="nama@email.com"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Jabatan -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="jabatan">Jabatan</label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-sm input-group-merge">
                                        <span class="input-group-text">
                                            <i class="bx bx-briefcase"></i>
                                        </span>
                                        <select id="jabatan" class="form-select" name="jabatan" required>
                                            <option value="">-- Pilih Jabatan --</option>
                                            <option value="admin">Admin</option>
                                            <option value="kepala_madrasah">Kepala Madrasah</option>
                                            <option value="wakil">Wakil</option>
                                            <option value="guru">Guru</option>
                                            <option value="kaur">Kaur</option>
                                            <option value="tu">TU</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="password">Password</label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-sm input-group-merge">
                                        <span class="input-group-text">
                                            <i class="bx bx-lock"></i>
                                        </span>
                                        <input
                                            type="password"
                                            id="password"
                                            class="form-control"
                                            name="password"
                                            placeholder="Password"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="password_confirmation">Konfirmasi Password</label>
                                <div class="col-sm-10">
                                    <div class="input-group input-group-sm input-group-merge">
                                        <span class="input-group-text">
                                            <i class="bx bx-lock-alt"></i>
                                        </span>
                                        <input
                                            type="password"
                                            id="password_confirmation"
                                            class="form-control"
                                            name="password_confirmation"
                                            placeholder="Ulangi Password"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol -->
                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="bx bx-save me-1"></i> Tambah
                                    </button>
                                    {{-- <a href="javascript:history.back()" class="btn btn-sm btn-secondary ms-2">
                                        <i class="bx bx-arrow-back me-1"></i> Kembali
                                    </a> --}}
                                </div>
                            </div>
                        </form>

                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
