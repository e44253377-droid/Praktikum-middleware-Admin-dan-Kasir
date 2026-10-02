<x-app-layout>
    <x-slot name="header">
        <div class="settings-heading">
            <div>
                <span class="settings-eyebrow">ACCOUNT SETTINGS</span>
                <h2>Pengaturan Akun</h2>
                <p>Kelola identitas akun dan keamanan akses CAFFE KITA.</p>
            </div>
            <a href="{{ auth()->user()->isCashier() ? route('transactions.index') : route('dashboard') }}" class="settings-back">← Kembali</a>
        </div>
    </x-slot>

    <div class="settings-page">
        <div class="settings-intro">
            @if ($user->profile_photo)
                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto profil" class="settings-avatar">
            @else
                <div class="settings-avatar settings-avatar-fallback">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            @endif
            <div><strong>{{ $user->name }}</strong><span>{{ ucfirst($user->role) }} &middot; {{ $user->email }}</span></div>
        </div>
        <div class="settings-card">@include('profile.partials.update-profile-information-form')</div>
        <div class="settings-card">@include('profile.partials.update-password-form')</div>
        <div class="settings-card settings-danger">@include('profile.partials.delete-user-form')</div>
    </div>
    <style>
        .settings-heading{display:flex;align-items:center;justify-content:space-between;gap:20px}.settings-heading h2{color:#172033;font-size:22px;font-weight:800;margin:3px 0}.settings-heading p{color:#718096;font-size:13px;margin:0}.settings-eyebrow{color:#16836c;font-size:10px;font-weight:800;letter-spacing:.16em}.settings-back{border:1px solid #16836c;border-radius:8px;color:#16836c;font-size:12px;font-weight:800;padding:9px 13px}.settings-page{background:#f3f6f9;min-height:calc(100vh - 145px);padding:28px max(24px,calc((100% - 800px)/2)) 48px}.settings-intro{display:flex;align-items:center;gap:14px;margin-bottom:18px}.settings-intro strong,.settings-intro span{display:block}.settings-intro strong{color:#172033;font-size:16px}.settings-intro span{color:#8190a1;font-size:12px;margin-top:4px}.settings-avatar{width:54px;height:54px;border-radius:15px;object-fit:cover}.settings-avatar-fallback{display:flex;align-items:center;justify-content:center;background:#dff5ec;color:#16836c;font-size:22px;font-weight:900}.settings-card{background:#fff;border:1px solid #e4eaf0;border-radius:15px;padding:22px;margin-bottom:16px;box-shadow:0 7px 20px #233b530b}.settings-danger{border-color:#f1dede}@media(max-width:600px){.settings-heading{display:block}.settings-back{display:inline-block;margin-top:12px}.settings-page{padding:18px 14px 35px}}
    </style>
</x-app-layout>
