<x-app-layout>
    <x-slot name="title">Manajemen Pengguna</x-slot>
    <x-slot name="subtitle">Kelola akun & hak akses pengguna sistem</x-slot>

    <div x-data="userManagement()">
        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="mb-6 grid gap-5 xl:grid-cols-[1.3fr_1fr]">
            <div class="glass-card">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-primary-600">Access Control</p>
                        <h2 class="mt-2 text-3xl font-extrabold text-slate-900">Manajemen Pengguna</h2>
                        <p class="mt-2 text-sm text-slate-500">Halaman ini sekarang terhubung ke MySQL. Tambah, edit, dan hapus pengguna akan mengubah tabel `users` secara langsung.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <input type="text" x-model="search" placeholder="Cari pengguna…" class="form-input !py-2.5 !pl-9 text-xs w-52">
                            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <x-button variant="primary" id="btn-add-user" @click="openModal('add')">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            Tambah Pengguna
                        </x-button>
                    </div>
                </div>
            </div>

            <div class="glass-card">
                <p class="text-sm font-semibold text-slate-800">Hak Akses Hari Ini</p>
                <div class="mt-5 space-y-3">
                    @foreach([
                        ['Login aktif', $stats['aktif']],
                        ['Role admin', $stats['admin']],
                        ['Role Tekpol', $users->where('role', 'Tekpol')->count()],
                    ] as [$label, $count])
                        <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-white/70 px-4 py-3">
                            <span class="text-sm text-slate-600">{{ $label }}</span>
                            <span class="text-lg font-extrabold text-slate-800">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
            @foreach([
                ['Total', $stats['total'], 'text-primary-600'],
                ['Aktif', $stats['aktif'], 'text-emerald-600'],
                ['Nonaktif', $stats['nonaktif'], 'text-red-500'],
                ['Admin', $stats['admin'], 'text-purple-600'],
            ] as [$label, $value, $tone])
                <div class="rounded-2xl border border-slate-100 bg-white p-4 text-center shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl">
                    <p class="text-2xl font-extrabold {{ $tone }}">{{ $value }}</p>
                    <p class="mt-0.5 text-xs font-medium text-slate-500">{{ $label }}</p>
                </div>
            @endforeach
        </div>

        <x-card>
            <div class="table-shell overflow-x-auto scrollbar-thin">
                <table class="data-table" id="user-table">
                    <thead>
                        <tr>
                            <th class="w-10">#</th>
                            <th>Pengguna</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Instansi</th>
                            <th>Status</th>
                            <th>Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $i => $u)
                            <tr class="user-row" data-search="{{ strtolower($u->name.' '.$u->email.' '.$u->role) }}">
                                <td class="font-mono text-xs text-slate-400">{{ $i + 1 }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl text-xs font-bold text-white"
                                             style="background: linear-gradient(135deg, hsl({{ ($i * 47 + 230) % 360 }},70%,55%), hsl({{ ($i * 47 + 270) % 360 }},70%,45%))">
                                            {{ strtoupper(substr($u->name, 0, 1).substr(strrchr($u->name, ' ') ?: $u->name, 1, 1)) }}
                                        </div>
                                        <span class="font-medium text-slate-700">{{ $u->name }}</span>
                                    </div>
                                </td>
                                <td class="text-xs text-slate-500">{{ $u->email }}</td>
                                <td>
                                    @php
                                        $roleClass = match($u->role) {
                                            'Super Admin' => 'bg-purple-100 text-purple-700',
                                            'Admin' => 'bg-primary-100 text-primary-700',
                                            'Tekpol' => 'bg-cyan-100 text-cyan-700',
                                            'Verifikator' => 'bg-amber-100 text-amber-700',
                                            default => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp
                                    <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $roleClass }}">{{ $u->role }}</span>
                                </td>
                                <td class="text-xs text-slate-600">{{ $u->instansi ?: '-' }}</td>
                                <td><x-badge value="{{ $u->status }}"/></td>
                                <td class="text-xs text-slate-400">{{ optional($u->created_at)->format('Y-m-d') }}</td>
                                <td>
                                    <div class="flex items-center gap-1">
                                        <button
                                            @click='openModal("edit", {{ Illuminate\Support\Js::from([
                                                "id" => $u->id,
                                                "name" => $u->name,
                                                "email" => $u->email,
                                                "role" => $u->role,
                                                "status" => $u->status,
                                                "instansi" => $u->instansi,
                                            ]) }})'
                                            class="btn-icon text-amber-500 hover:text-amber-700"
                                            title="Edit">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button @click="confirmDelete({{ $u->id }}, {{ Illuminate\Support\Js::from($u->name) }})" class="btn-icon text-red-500 hover:text-red-700" title="Hapus">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-400">Belum ada data pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5 border-t border-slate-100 pt-4">
                <p class="text-xs text-slate-500">Menampilkan {{ $users->count() }} dari <span class="font-semibold">{{ $users->count() }}</span> pengguna</p>
            </div>
        </x-card>

        <x-modal show="modalOpen" title="Form Pengguna" size="md" close="modalOpen = false">
            <form id="user-form" :action="formAction" method="POST">
                @csrf
                <template x-if="modalMode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="mb-5 rounded-2xl bg-slate-50/80 px-4 py-3">
                    <p class="text-sm font-semibold text-slate-800" x-text="modalTitle"></p>
                    <p class="mt-1 text-xs text-slate-400">Role `Tekpol` sudah tersedia dan bisa dipilih di form ini.</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group col-span-2">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" x-model="form.name" name="name" class="form-input" placeholder="Nama lengkap pengguna">
                    </div>
                    <div class="form-group col-span-2">
                        <label class="form-label">Email</label>
                        <input type="email" x-model="form.email" name="email" class="form-input" placeholder="email@instansi.go.id">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select x-model="form.role" name="role" class="form-input">
                            <option>Super Admin</option>
                            <option>Admin</option>
                            <option>Tekpol</option>
                            <option>Verifikator</option>
                            <option>Pemohon</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select x-model="form.status" name="status" class="form-input">
                            <option>aktif</option>
                            <option>nonaktif</option>
                            <option>pending</option>
                        </select>
                    </div>
                    <div class="form-group col-span-2">
                        <label class="form-label">Instansi</label>
                        <input type="text" x-model="form.instansi" name="instansi" class="form-input" placeholder="Nama instansi / dinas">
                    </div>
                    <template x-if="modalMode === 'add'">
                        <div class="form-group col-span-2">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-input" placeholder="Minimal 8 karakter">
                        </div>
                    </template>
                    <template x-if="modalMode === 'edit'">
                        <div class="form-group col-span-2">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-input" placeholder="Kosongkan jika tidak diubah">
                        </div>
                    </template>
                </div>
            </form>
            <x-slot name="footer">
                <x-button variant="secondary" @click="modalOpen = false">Batal</x-button>
                <x-button variant="primary" type="submit" form="user-form">
                    <span x-text="modalMode === 'add' ? 'Tambah Pengguna' : 'Simpan Perubahan'"></span>
                </x-button>
            </x-slot>
        </x-modal>

        <div x-show="deleteOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="deleteOpen = false"></div>
            <div class="relative z-10 w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-2xl"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-100">
                    <svg class="h-7 w-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="mb-1 text-lg font-bold text-slate-800">Hapus Pengguna?</h3>
                <p class="mb-6 text-sm text-slate-500">Tindakan ini tidak dapat dibatalkan. <span class="font-semibold" x-text="deleteName"></span> akan dihapus permanen.</p>
                <form :action="deleteAction" method="POST" class="flex gap-3">
                    @csrf
                    @method('DELETE')
                    <x-button variant="secondary" class="flex-1 justify-center" @click="deleteOpen = false">Batal</x-button>
                    <x-button variant="danger" type="submit" class="flex-1 justify-center">Hapus</x-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
function userManagement() {
    return {
        search: '',
        modalOpen: false,
        modalMode: 'add',
        modalTitle: 'Tambah Pengguna',
        deleteOpen: false,
        formAction: '{{ route('users.store') }}',
        deleteAction: '',
        deleteName: '',
        form: { name: '', email: '', role: 'Tekpol', status: 'aktif', instansi: '' },

        init() {
            this.$watch('search', () => {
                const q = this.search.toLowerCase();
                document.querySelectorAll('.user-row').forEach(row => {
                    const show = !q || row.dataset.search.includes(q);
                    row.style.display = show ? '' : 'none';
                    if (show) row.style.animation = 'fade-in 0.2s ease both';
                });
            });
        },

        openModal(mode, user = null) {
            this.modalMode = mode;
            this.modalTitle = mode === 'add' ? 'Tambah Pengguna' : 'Edit Pengguna';
            this.formAction = mode === 'add'
                ? '{{ route('users.store') }}'
                : `{{ url('/users') }}/${user.id}`;
            this.form = mode === 'add'
                ? { name: '', email: '', role: 'Tekpol', status: 'aktif', instansi: '' }
                : {
                    name: user.name,
                    email: user.email,
                    role: user.role,
                    status: user.status,
                    instansi: user.instansi ?? '',
                };
            this.modalOpen = true;
        },

        confirmDelete(id, name) {
            this.deleteAction = `{{ url('/users') }}/${id}`;
            this.deleteName = name;
            this.deleteOpen = true;
        },
    }
}
</script>
@endpush
