@extends('layouts.app')

@section('title', 'Aturan Ambang Batas Poin Sanksi')
@section('page_title', 'Konfigurasi Aturan Sanksi & Surat Peringatan (SP)')

@section('content')
<div class="py-6 space-y-6" x-data="{ editModal: false, editData: {} }">

    <!-- Header Description -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <h3 class="font-extrabold text-slate-800 text-base">Tingkatan Sanksi & Ambang Batas Poin (Thresholds)</h3>
        <p class="text-xs text-slate-500 mt-1">
            Sistem secara otomatis mendeteksi siswa yang melewati batas akumulasi poin berikut untuk memberikan peringatan dan menyarankan penerbitan Surat Panggilan Orang Tua.
        </p>
    </div>

    <!-- Threshold Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($thresholds as $th)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-3 py-1 rounded-xl text-xs font-black
                            @if($th->min_points >= 75) bg-rose-100 text-rose-800
                            @elseif($th->min_points >= 50) bg-amber-100 text-amber-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            &ge; {{ $th->min_points }} Poin
                        </span>

                        @if($th->requires_summons)
                            <span class="px-2.5 py-0.5 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-[10px] font-bold">
                                Butuh SP Ortu
                            </span>
                        @endif
                    </div>

                    <h4 class="font-bold text-slate-800 text-sm mb-1.5">{{ $th->action_name }}</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $th->description }}</p>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex justify-end">
                    <button type="button" @click="editData = { id: {{ $th->id }}, min_points: {{ $th->min_points }}, action_name: '{{ addslashes($th->action_name) }}', description: '{{ addslashes($th->description) }}', requires_summons: {{ $th->requires_summons ? 'true' : 'false' }} }; editModal = true;"
                            class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition-colors flex items-center gap-1.5">
                        <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                        <span>Ubah Aturan</span>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Edit Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="editModal = false" class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-md w-full p-6 space-y-4">
            <h3 class="font-extrabold text-slate-800 text-base">Ubah Ambang Batas Sanksi</h3>

            <form :action="'{{ url('master/thresholds') }}/' + editData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Minimal Poin Pelanggaran</label>
                    <input type="number" name="min_points" x-model="editData.min_points" required min="1" max="500" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-rose-700">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Sanksi / Tindakan</label>
                    <input type="text" name="action_name" x-model="editData.action_name" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Prosedur Sanksi</label>
                    <textarea name="description" x-model="editData.description" rows="3" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800"></textarea>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                        <input type="checkbox" name="requires_summons" value="1" x-model="editData.requires_summons" class="rounded text-emerald-600">
                        <span>Wajib terbitkan Surat Panggilan Orang Tua</span>
                    </label>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-navy-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl">Simpan Aturan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
