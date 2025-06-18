<div>
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Manajemen Galeri Media</h1>
                <p class="mt-2 text-sm text-gray-700">Kelola foto dan video yang akan tampil di layar TV.</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <button wire:click="create()" type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                    Tambah Media Baru
                </button>
            </div>
        </div>
        
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative my-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <div class="mt-8 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @forelse($media as $item)
                <div class="relative group border rounded-lg overflow-hidden shadow-lg">
                    @if($item->type == 'image')
                        <img src="{{ Storage::url($item->file_path) }}" alt="Gallery Image" class="h-48 w-full object-cover">
                    @else
                        <div class="h-48 w-full bg-black flex items-center justify-center">
                             <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.55a1 1 0 011.45.89v2.22a1 1 0 01-1.45.89L15 12M4 6h11a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"></path></svg>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center p-2">
                        <p class="text-white text-xs text-center truncate w-full">{{ basename($item->file_path) }}</p>
                        <div class="mt-2">
                            <button wire:click="toggleStatus({{ $item->id }})" class="text-xs {{ $item->is_active ? 'bg-green-500' : 'bg-gray-500' }} text-white px-2 py-1 rounded">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                            <button wire:click="delete({{ $item->id }})" onclick="return confirm('Anda yakin ingin menghapus media ini?')" class="text-xs bg-red-600 text-white px-2 py-1 rounded">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500">Tidak ada media di galeri.</p>
            @endforelse
        </div>
         <div class="mt-8">
            {{ $media->links() }}
        </div>
    </div>

    @if($isModalOpen)
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="store">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Media Baru</h3>
                        <div class="mt-4">
                            <label for="file_upload" class="block text-sm font-medium text-gray-700">Pilih File (JPG, PNG, MP4)</label>
                            <input type="file" wire:model="file_upload" id="file_upload" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                             <div wire:loading wire:target="file_upload" class="text-sm text-gray-500 mt-2">Uploading...</div>
                            @error('file_upload') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mt-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">Tipe Media</label>
                            <select wire:model.defer="type" id="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 rounded-md">
                                <option value="image">Gambar</option>
                                <option value="video">Video</option>
                            </select>
                        </div>
                        <div class="mt-4">
                             <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                            <select wire:model.defer="is_active" id="is_active" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 rounded-md">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>