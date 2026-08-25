@extends('layouts.app')
@section('title', 'Edit User: '.$user->display_name)
@section('page-title', 'Edit User')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Edit User</h1>
        <p class="page-header-sub">{{ $user->display_name }}</p>
    </div>
    <a href="{{ route('user.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <form action="{{ route('user.update', $user) }}" method="POST">
            @csrf @method('PUT')
            <div class="card">
                <div class="card-header"><i class="bi bi-person-gear me-2 text-primary"></i>Edit Informasi User</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                            <small class="text-muted">Username tidak dapat diubah</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                   value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                            @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telepon</label>
                            <input type="tel" name="telepon" inputmode="numeric" pattern="[0-9+\-\s()]{6,20}" maxlength="20" class="form-control" value="{{ old('telepon', $user->telepon) }}" placeholder="08xxxxxxxxxx" title="Hanya angka, spasi, +, -, () yang diperbolehkan">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" {{ $user->id_users === auth()->user()->id_users ? 'disabled' : '' }}>
                                <option value="staff" {{ old('role',$user->role)=='staff'?'selected':'' }}>Staff</option>
                                <option value="admin" {{ old('role',$user->role)=='admin'?'selected':'' }}>Administrator</option>
                            </select>
                            @if($user->id_users === auth()->user()->id_users)
                            <input type="hidden" name="role" value="{{ $user->role }}">
                            <small class="text-muted">Tidak dapat mengubah role sendiri</small>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                                       value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                       {{ $user->id_users === auth()->user()->id_users ? 'disabled' : '' }}>
                                <label class="form-check-label" for="isActive">Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex gap-2 justify-content-end">
                    <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
