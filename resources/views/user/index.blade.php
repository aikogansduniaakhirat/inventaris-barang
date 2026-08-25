@extends('layouts.app')
@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Manajemen User</h1>
        <p class="page-header-sub">Kelola akun pengguna sistem inventaris</p>
    </div>
    <a href="{{ route('user.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Tambah User
    </a>
</div>

<!-- Filter -->
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, email, username..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Administrator</option>
                    <option value="staff" {{ request('role')=='staff'?'selected':'' }}>Staff</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search me-1"></i>Cari</button>
                <a href="{{ route('user.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Total <span class="badge bg-primary">{{ $users->total() }}</span> pengguna</div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="topbar-avatar">{{ strtoupper(substr($u->display_name,0,1)) }}</div>
                            <div>
                                <div class="fw-semibold">{{ $u->display_name }}</div>
                                <small class="text-muted">Bergabung {{ $u->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </td>
                    <td><code>{{ $u->name }}</code></td>
                    <td>{{ $u->email }}</td>
                    <td class="text-muted-sm">{{ $u->telepon ?? '-' }}</td>
                    <td><span class="badge bg-{{ $u->role_badge }}">{{ $u->role_label }}</span></td>
                    <td>
                        @if($u->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('user.edit', $u) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn btn-sm btn-outline-warning" title="Reset Password"
                                    onclick="resetPassword({{ $u->id }}, '{{ addslashes($u->display_name) }}')">
                                <i class="bi bi-key"></i>
                            </button>
                            @if($u->id_users !== auth()->user()->id_users)
                            <form action="{{ route('user.destroy', $u) }}" method="POST"
                                  onsubmit="return confirm('Hapus user {{ addslashes($u->display_name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada pengguna</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-body border-top py-3">{{ $users->links() }}</div>
    @endif
</div>

<!-- Modal Reset Password -->
<div class="modal fade" id="modalResetPw" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="formResetPw" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted-sm">Reset password untuk: <strong id="resetUserName"></strong></p>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                    </div>
                    <div>
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function resetPassword(userId, userName) {
    document.getElementById('formResetPw').action = `/user/${userId}/reset-password`;
    document.getElementById('resetUserName').textContent = userName;
    new bootstrap.Modal(document.getElementById('modalResetPw')).show();
}
</script>
@endpush
@endsection
