@extends('layouts.app')
@section('title', 'Anggota & pengguna')
@section('content')
<section class="tb-module-page">
    <div class="tb-page-heading"><div><div class="tb-eyebrow">ADMINISTRASI AKUN</div><h1>Anggota & pengguna</h1><p>Daftarkan akun baru dan atur peran serta status akses pengguna.</p></div><span class="tb-card-chip">{{ $users->total() }} akun</span></div>
    <div class="tb-card tb-form-card mb-5"><div class="tb-card-header"><div><h2>Buat akun pengguna</h2><p>Tambahkan anggota atau petugas ke sistem perpustakaan.</p></div></div>
        <form method="POST" action="{{ route('admin.users.store') }}" class="tb-user-create-form">@csrf
            <label class="tb-form-field"><span>Nama lengkap</span><input class="input-field" name="name" placeholder="Nama pengguna" required></label>
            <label class="tb-form-field"><span>Alamat email</span><input class="input-field" type="email" name="email" placeholder="nama@email.id" required></label>
            <label class="tb-form-field"><span>Peran</span><select class="input-field" name="role"><option value="staff">Petugas</option><option value="member">Anggota</option><option value="admin">Admin</option></select></label>
            <label class="tb-form-field"><span>Kata sandi</span><input class="input-field" name="password" type="password" placeholder="Min. 10 karakter" required></label>
            <div class="tb-user-submit"><button class="tb-primary-button" type="submit"><span>＋</span> Buat akun</button></div>
        </form>
    </div>
    <div class="tb-card"><div class="tb-card-header"><div><h2>Daftar pengguna</h2><p>Perubahan peran dan status berlaku setelah disimpan.</p></div></div>
        <div class="tb-table-wrap"><table class="tb-table"><thead><tr><th>NAMA</th><th>EMAIL</th><th>PERAN</th><th>STATUS</th><th>AKSI</th></tr></thead><tbody>
            @forelse($users as $user)
                <tr><td><div class="tb-member-cell"><span class="tb-mini-avatar">{{ strtoupper(substr($user->name,0,1)) }}</span><span><strong>{{ $user->name }}</strong><small>ID pengguna #{{ $user->id }}</small></span></div></td><td>{{ $user->email }}</td>
                    <td><form id="u{{ $user->id }}" method="POST" action="{{ route('admin.users.update',$user) }}">@csrf @method('PATCH')</form><select form="u{{ $user->id }}" class="input-field tb-table-select" name="role"><option value="admin" @selected($user->role==='admin')>Admin</option><option value="staff" @selected($user->role==='staff')>Petugas</option><option value="member" @selected($user->role==='member')>Anggota</option></select></td>
                    <td><select form="u{{ $user->id }}" class="input-field tb-table-select" name="is_active"><option value="1" @selected($user->is_active)>Aktif</option><option value="0" @selected(!$user->is_active)>Nonaktif</option></select></td><td><button form="u{{ $user->id }}" class="tb-action-link" type="submit">Simpan perubahan</button></td></tr>
            @empty<tr><td colspan="5" class="tb-empty">Belum ada akun pengguna.</td></tr>@endforelse
        </tbody></table></div><div class="tb-card-pagination">{{ $users->links() }}</div>
    </div>
</section>
@endsection
