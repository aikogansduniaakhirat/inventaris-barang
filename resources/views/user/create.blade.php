@extends('layouts.app')
@section('title', 'Tambah User')
@section('page-title', 'Tambah User')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Tambah User</h1>
        <p class="page-header-sub">Daftarkan akun pengguna baru</p>
    </div>
    <a href="{{ route('user.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <form action="{{ route('user.store') }}" method="POST" id="formCreateUser">
            @csrf
            <div class="card">
                <div class="card-header"><i class="bi bi-person-plus me-2 text-primary"></i>Informasi User</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap"
                                   class="form-control @error('nama_lengkap') is-invalid @enderror"
                                   value="{{ old('nama_lengkap') }}" required placeholder="Nama lengkap">
                            @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="staff" {{ old('role','staff')=='staff'?'selected':'' }}>Staff</option>
                                <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Administrator</option>
                            </select>
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="Biarkan kosong untuk auto-generate"
                                   pattern="[a-z0-9._]+" title="Username hanya boleh huruf kecil, angka, titik, dan underscore.">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div id="usernameHint" class="form-text text-muted" style="font-size:0.8rem;">
                                <i class="bi bi-magic me-1 text-primary"></i>
                                Kosongkan untuk auto-generate. Format staff: <code id="usernamePreview">namapertama.staff</code>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telepon</label>
                            <input type="tel" name="telepon" inputmode="numeric" pattern="[0-9+\-\s()]{6,20}" maxlength="20" class="form-control telepon-input @error('telepon') is-invalid @enderror" value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx" title="Hanya angka, spasi, +, -, () yang diperbolehkan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required minlength="8">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex gap-2 justify-content-end">
                    <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan User</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const namaInput     = document.getElementById('nama_lengkap');
const roleSelect    = document.getElementById('role');
const usernameInput = document.getElementById('name');
const preview       = document.getElementById('usernamePreview');

function generateUsernamePreview() {
    const nama = namaInput.value.trim();
    const role = roleSelect.value;
    if (!nama) {
        preview.textContent = 'namapertama.' + role;
        return;
    }
    // Ambil kata pertama, bersihkan, lowercase
    const namaDepan = nama.split(' ')[0].toLowerCase().replace(/[^a-z0-9]/g, '');
    const generated = namaDepan + '.' + role;
    preview.textContent = generated || 'namapertama.' + role;

    // Auto-fill username hanya jika user belum mengetik manual
    if (!usernameInput.dataset.manuallyEdited) {
        usernameInput.value = generated;
    }
}

// Auto-generate preview saat nama atau role berubah
namaInput.addEventListener('input', generateUsernamePreview);
roleSelect.addEventListener('change', generateUsernamePreview);

// Tandai jika user mengedit username secara manual
usernameInput.addEventListener('input', function() {
    this.dataset.manuallyEdited = 'true';
});

// Reset flag jika user hapus isi username
usernameInput.addEventListener('change', function() {
    if (!this.value) delete this.dataset.manuallyEdited;
});

// Init on load
generateUsernamePreview();
</script>

{{-- Strip karakter non-angka dari input telepon real-time --}}
@include('partials.telepon-handler')
@endpush
@endsection
