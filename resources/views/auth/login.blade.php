@extends('layouts.guest')
@section('title', 'Masuk')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <!-- Logo & Title -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center mb-3"
                 style="width:64px;height:64px;background:linear-gradient(135deg,#4F46E5,#818CF8);border-radius:16px;box-shadow:0 8px 24px rgba(79,70,229,0.35);">
                <i class="bi bi-box-seam-fill text-white fs-2"></i>
            </div>
            <h1 class="fw-extrabold" style="font-size:1.5rem;color:#1E293B;">Inventaris Barang</h1>
            <p class="text-muted mb-0" style="font-size:0.875rem;">Sistem Manajemen Inventaris Sekolah</p>
        </div>

        <!-- Session Status -->
        @if(session('status'))
        <div class="alert alert-info">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold" for="login">Username atau Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                    <input id="login" type="text" name="login" class="form-control @error('login') is-invalid @enderror"
                           value="{{ old('login') }}" required autofocus autocomplete="username"
                           placeholder="budi.staff atau admin@inventaris.sch.id">
                    @error('login')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <label class="form-label fw-semibold" for="password">Password</label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-decoration-none" style="font-size:0.8rem;color:#4F46E5;">Lupa password?</a>
                    @endif
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           required autocomplete="current-password" placeholder="••••••••">
                    <button type="button" class="input-group-text btn" onclick="togglePw()" title="Tampilkan password">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input type="checkbox" id="remember_me" name="remember" class="form-check-input">
                    <label for="remember_me" class="form-check-label text-muted" style="font-size:0.875rem;">Ingat saya</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" style="font-size:1rem;">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Sistem
            </button>
        </form>

        <div class="mt-4 p-3 rounded" style="background:#F8FAFC; border:1px solid #E2E8F0;">
            <p class="text-muted mb-1" style="font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Demo Credentials</p>
            <div class="d-flex gap-3 flex-wrap">
                <div>
                    <span class="text-muted-sm d-block">Username</span>
                    <code style="font-size:0.8rem;">admin</code>
                </div>
                <div>
                    <span class="text-muted-sm d-block">atau Email</span>
                    <code style="font-size:0.8rem;">admin@inventaris.sch.id</code>
                </div>
                <div>
                    <span class="text-muted-sm d-block">Password</span>
                    <code style="font-size:0.8rem;">password</code>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePw() {
    const pw = document.getElementById('password');
    const ic = document.getElementById('eyeIcon');
    if (pw.type === 'password') { pw.type = 'text'; ic.className = 'bi bi-eye-slash'; }
    else { pw.type = 'password'; ic.className = 'bi bi-eye'; }
}
</script>
@endsection
