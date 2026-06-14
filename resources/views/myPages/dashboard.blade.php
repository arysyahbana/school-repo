@extends('app')
@section('title', 'Dashboard')
@section('content')

<div class="container-fluid flex-grow-1 container-p-y">

  {{-- ── Welcome Banner ── --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="d-flex align-items-end row">
          <div class="col-sm-7">
            <div class="card-body">
              <h5 class="card-title text-primary">Selamat Datang, {{ Auth::user()->name }}! 👋</h5>
              <p class="mb-2">
                Semoga aktivitas hari ini berjalan lancar dan bermanfaat.
              </p>
              <span class="badge bg-label-primary">
                {{ ucfirst(str_replace('_', ' ', Auth::user()->jabatan)) }}
              </span>
            </div>
          </div>
          <div class="col-sm-5 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-4">
              <img
                src="{{ asset('dist/assets/img/illustrations/man-with-laptop-light.png') }}"
                height="140"
                alt="Dashboard"
                data-app-dark-img="illustrations/man-with-laptop-dark.png"
                data-app-light-img="illustrations/man-with-laptop-light.png"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ── Stat Cards ── --}}
  <div class="row mb-4">

    @if(in_array(Auth::user()->jabatan, ['admin', 'kepala_madrasah']))

      <div class="col-6 col-md-3 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-primary">
                  <i class="bx bx-group"></i>
                </span>
              </div>
            </div>
            <span class="fw-semibold d-block mb-1">Total Pengguna</span>
            <h3 class="card-title mb-0 text-primary">{{ number_format($stats['total_users']) }}</h3>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-info">
                  <i class="bx bx-file"></i>
                </span>
              </div>
            </div>
            <span class="fw-semibold d-block mb-1">Total Arsip</span>
            <h3 class="card-title mb-0 text-info">{{ number_format($stats['total_files']) }}</h3>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-warning">
                  <i class="bx bx-folder"></i>
                </span>
              </div>
            </div>
            <span class="fw-semibold d-block mb-1">Total Folder</span>
            <h3 class="card-title mb-0 text-warning">{{ number_format($stats['total_folders']) }}</h3>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-danger">
                  <i class="bx bx-send"></i>
                </span>
              </div>
            </div>
            <span class="fw-semibold d-block mb-1">Total Pengiriman</span>
            <h3 class="card-title mb-0 text-danger">{{ number_format($stats['total_kirim']) }}</h3>
          </div>
        </div>
      </div>

    @else

      <div class="col-6 col-md-3 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-primary">
                  <i class="bx bx-file"></i>
                </span>
              </div>
            </div>
            <span class="fw-semibold d-block mb-1">Arsip Saya</span>
            <h3 class="card-title mb-0 text-primary">{{ number_format($stats['total_files']) }}</h3>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-warning">
                  <i class="bx bx-folder"></i>
                </span>
              </div>
            </div>
            <span class="fw-semibold d-block mb-1">Folder Saya</span>
            <h3 class="card-title mb-0 text-warning">{{ number_format($stats['total_folders']) }}</h3>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-info">
                  <i class="bx bx-inbox"></i>
                </span>
              </div>
            </div>
            <span class="fw-semibold d-block mb-1">Arsip Masuk</span>
            <h3 class="card-title mb-0 text-info">{{ number_format($stats['arsip_masuk']) }}</h3>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="card-title d-flex align-items-start justify-content-between">
              <div class="avatar flex-shrink-0">
                <span class="avatar-initial rounded bg-label-danger">
                  <i class="bx bx-send"></i>
                </span>
              </div>
            </div>
            <span class="fw-semibold d-block mb-1">Arsip Dikirim</span>
            <h3 class="card-title mb-0 text-danger">{{ number_format($stats['arsip_dikirim']) }}</h3>
          </div>
        </div>
      </div>

    @endif
  </div>

  {{-- ── Content Row ── --}}
  <div class="row">

    {{-- Arsip Terbaru --}}
    <div class="col-12 col-lg-5 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0 me-2">Arsip Terbaru</h5>
          <a href="#" class="text-primary small fw-semibold">Lihat Semua</a>
        </div>
        <div class="card-body">
          <ul class="p-0 m-0">
            @forelse($latestFiles as $file)
              @php
                $ext = strtolower(pathinfo($file->name ?? 'file.txt', PATHINFO_EXTENSION));
                $iconMap = ['pdf'=>'bxs-file-pdf','doc'=>'bxs-file-doc','docx'=>'bxs-file-doc','xls'=>'bxs-spreadsheet','xlsx'=>'bxs-spreadsheet','jpg'=>'bx-image','png'=>'bx-image'];
                $icon = $iconMap[$ext] ?? 'bx-file';
              @endphp
              <li class="d-flex mb-3 pb-1 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="avatar flex-shrink-0 me-3">
                  <span class="avatar-initial rounded bg-label-primary">
                    <i class="bx {{ $icon }}"></i>
                  </span>
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                  <div class="me-2 overflow-hidden">
                    <h6 class="mb-0 text-truncate" style="max-width:200px">{{ $file->name ?? '—' }}</h6>
                    <small class="text-muted">
                      {{ $file->sender ? 'Dari: ' . $file->sender->name : 'Unggahan sendiri' }}
                    </small>
                  </div>
                  <small class="text-muted text-nowrap">{{ $file->created_at->diffForHumans() }}</small>
                </div>
              </li>
            @empty
              <li class="text-center text-muted py-4">
                <i class="bx bx-folder-open d-block mb-1" style="font-size:1.8rem;opacity:.4"></i>
                Belum ada arsip.
              </li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>

    {{-- Histori Kirim --}}
    <div class="col-12 col-md-6 col-lg-4 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0 me-2">Histori Kirim</h5>
        </div>
        <div class="card-body">
          <ul class="p-0 m-0">
            @forelse($latestSends as $send)
              <li class="d-flex mb-3 pb-1 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="avatar flex-shrink-0 me-3">
                  <span class="avatar-initial rounded bg-label-warning">
                    <i class="bx bx-envelope"></i>
                  </span>
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                  <div class="me-2 overflow-hidden">
                    <h6 class="mb-0 text-truncate" style="max-width:160px">{{ $send->name ?? '—' }}</h6>
                    <small class="text-muted">{{ $send->sender->name ?? 'Sistem' }}</small>
                  </div>
                  <small class="text-muted text-nowrap">{{ $send->created_at->diffForHumans() }}</small>
                </div>
              </li>
            @empty
              <li class="text-center text-muted py-4">
                <i class="bx bx-paper-plane d-block mb-1" style="font-size:1.8rem;opacity:.4"></i>
                Belum ada histori pengiriman.
              </li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>

    {{-- Kolom Kanan --}}
    <div class="col-12 col-md-6 col-lg-3 mb-4">

      {{-- Distribusi Jabatan (admin only) --}}
      @if(in_array(Auth::user()->jabatan, ['admin','kepala_madrasah']) && $usersByRole->isNotEmpty())
        @php $totalUsers = $usersByRole->sum('total'); @endphp
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="card-title m-0">Distribusi Jabatan</h5>
            <small class="text-muted">{{ $totalUsers }} total pengguna</small>
          </div>
          <div class="card-body">
            @php
              $roleColors = ['admin'=>'bg-primary','kepala_madrasah'=>'bg-info','guru'=>'bg-success','staff'=>'bg-warning'];
            @endphp
            @foreach($usersByRole as $role)
              @php
                $pct = $totalUsers > 0 ? round($role->total / $totalUsers * 100) : 0;
                $color = $roleColors[$role->jabatan] ?? 'bg-secondary';
              @endphp
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                  <small class="fw-semibold">{{ ucfirst(str_replace('_',' ',$role->jabatan)) }}</small>
                  <small class="text-muted">{{ $role->total }} ({{ $pct }}%)</small>
                </div>
                <div class="progress" style="height:6px">
                  <div class="progress-bar {{ $color }}" style="width:{{ $pct }}%" role="progressbar"></div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      {{-- Aktivitas Terkini --}}
      <div class="card">
        <div class="card-header">
          <h5 class="card-title m-0">Aktivitas Terkini</h5>
        </div>
        <div class="card-body">
          <ul class="p-0 m-0">
            @forelse($activities->take(6) as $act)
              <li class="d-flex mb-3 pb-1 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="avatar flex-shrink-0 me-3">
                  <span class="avatar-initial rounded bg-label-secondary">
                    <i class="bx bx-file-blank"></i>
                  </span>
                </div>
                <div>
                  <h6 class="mb-0 small text-truncate" style="max-width:140px">{{ $act->name ?? '—' }}</h6>
                  <small class="text-muted">
                    {{ $act->user->name ?? '—' }}
                    @if($act->sender) ← {{ $act->sender->name }} @endif
                  </small><br>
                  <small class="text-muted">{{ $act->created_at->diffForHumans() }}</small>
                </div>
              </li>
            @empty
              <li class="text-center text-muted py-4">
                <i class="bx bx-broadcast d-block mb-1" style="font-size:1.8rem;opacity:.4"></i>
                Belum ada aktivitas.
              </li>
            @endforelse
          </ul>
        </div>
      </div>

    </div>
  </div>

</div>
@endsection
