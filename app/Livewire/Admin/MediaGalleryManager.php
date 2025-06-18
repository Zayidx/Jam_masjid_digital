<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\MediaGallery;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

class MediaGalleryManager extends Component
{
    use WithFileUploads, WithPagination;
   
    public $media_id;
    public $file_upload;
    public $type = 'image';
    public $is_active = true;
    public $isModalOpen = false;

    protected $rules = [
        'file_upload' => 'required|mimes:jpg,jpeg,png,mp4,mov|max:10240', // 10MB Max
        'type' => 'required|in:image,video',
        'is_active' => 'required|boolean',
    ];

    public function render()
    {
        $media = MediaGallery::latest()->paginate(8);
        return view('livewire.admin.media-gallery-manager', [
            'media' => $media,
        ]);
    }
    
    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetForm()
    {
        $this->media_id = null;
        $this->file_upload = null;
        $this->type = 'image';
        $this->is_active = true;
    }

    public function store()
    {
        $this->validate();

        $filePath = $this->file_upload->store('gallery', 'public');

        MediaGallery::create([
            'file_path' => $filePath,
            'type' => $this->type,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Media berhasil ditambahkan.');

        $this->closeModal();
        $this->resetForm();
    }

    public function delete($id)
    {
        $media = MediaGallery::findOrFail($id);
        
        if (Storage::disk('public')->exists($media->file_path)) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();
        session()->flash('message', 'Media berhasil dihapus.');
    }
    
    public function toggleStatus($id)
    {
        $media = MediaGallery::findOrFail($id);
        $media->is_active = !$media->is_active;
        $media->save();
    }
}