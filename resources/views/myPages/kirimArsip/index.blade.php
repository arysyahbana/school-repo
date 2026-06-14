@extends('app')
@section('title', 'Kirim Arsip')

@section('content')
<style>
    .table-responsive {
        min-height: 200px;
    }
</style>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3 px-5">
        <h5 class="mb-0">Histori Kirim Arsip</h5>
        <form method="GET" action="{{ route('kirim-arsip.index') }}">
            <div class="input-group shadow-sm">

                <span class="input-group-text">
                    <i class="bx bx-search"></i>
                </span>

                <input type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari nama arsip..."
                    value="{{ request('search') }}">

                @if(request('search'))
                    <a href="{{ route('kirim-arsip.index') }}"
                    class="btn btn-outline-secondary">
                        Reset
                    </a>
                @endif

            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive px-4">
            <a href="{{ route('kirim-arsip.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus me-1"></i>
                Kirim Arsip Baru
            </a>
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Arsip</th>
                        <th>Jenis</th>
                        <th>Penerima</th>
                        <th>Tanggal Kirim</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($arsips as $arsip)
                        <tr>

                            <td>
                                {{ $arsips->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $arsip->name }}
                                </div>
                            </td>

                            <td>
                                @if($arsip->storage_type == 'local')
                                    <span class="badge bg-label-primary">
                                        File Upload
                                    </span>
                                @else
                                    <span class="badge bg-label-success">
                                        Google Drive
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="badge bg-label-info">
                                    {{ $arsip->total_penerima }} Orang
                                </span>
                            </td>

                            <td>
                                {{ $arsip->created_at->format('d M Y H:i') }}
                            </td>

                            <td>
                                <span class="badge bg-success">
                                    Terkirim
                                </span>
                            </td>

                            <td>
                                <div class="dropdown">
                                    <button
                                        class="btn btn-sm"
                                        data-bs-toggle="dropdown">

                                        <i class="bx bx-dots-vertical-rounded"></i>

                                    </button>

                                    <ul class="dropdown-menu">

                                        <li>
                                            <a
                                                href="{{ route('kirim-arsip.show', $arsip->send_batch_id) }}"
                                                class="dropdown-item">

                                                <i class="bx bx-show me-2"></i>
                                                Detail

                                            </a>
                                        </li>

                                        <li>
                                            <button
                                                class="dropdown-item text-danger btn-delete-batch"
                                                data-url="{{ route('kirim-arsip.destroy', $arsip->send_batch_id) }}"
                                                data-name="{{ $arsip->name }}">

                                                <i class="bx bx-trash me-2"></i>
                                                Hapus

                                            </button>
                                        </li>

                                    </ul>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                class="text-center text-muted py-5">

                                <i class="bx bx-folder-open fs-2 d-block mb-2"></i>

                                Belum ada arsip yang dikirim.

                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer px-5">
        {{ $arsips->onEachSide(1)->links() }}
    </div>
    </div>
</div>

{{-- Modal Hapus Batch --}}
<div class="modal fade"
     id="deleteBatchModal"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <form method="POST"
              id="deleteBatchForm">

            @csrf
            @method('DELETE')

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Hapus Arsip
                    </h5>
                </div>

                <div class="modal-body">

                    <p class="mb-0">

                        Arsip ini akan dihapus dari
                        <strong>seluruh penerima</strong>.

                    </p>

                    <p class="text-danger mt-2 mb-0">

                        Tindakan ini tidak dapat dibatalkan.

                    </p>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit"
                            class="btn btn-danger">

                        Ya, Hapus

                    </button>

                </div>

            </div>

        </form>

    </div>
</div>

<script>
document.querySelectorAll('.btn-delete-batch')
    .forEach(btn => {

        btn.addEventListener('click', function() {

            document.getElementById(
                'deleteBatchForm'
            ).action = this.dataset.url;

            new bootstrap.Modal(
                document.getElementById(
                    'deleteBatchModal'
                )
            ).show();

        });

    });
</script>
@endsection
