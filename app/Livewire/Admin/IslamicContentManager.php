<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\IslamicContent;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class IslamicContentManager extends Component
{
    use WithPagination;

    public $content_id;
    public $type = 'quran';
    public $text_ar;
    public $text_id;
    public $source;
    public $is_active = true;
    public $isModalOpen = false;

    protected $rules = [
        'type' => 'required|in:quran,hadith',
        'text_id' => 'required|string',
        'text_ar' => 'nullable|string',
        'source' => 'required|string|max:255',
        'is_active' => 'required|boolean',
    ];

    public function render()
    {
        $contents = IslamicContent::latest()->paginate(5);
        return view('livewire.admin.islamic-content-manager', [
            'contents' => $contents
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
        $this->content_id = null;
        $this->type = 'quran';
        $this->text_ar = '';
        $this->text_id = '';
        $this->source = '';
        $this->is_active = true;
    }

    public function store()
    {
        $this->validate();

        IslamicContent::updateOrCreate(['id' => $this->content_id], [
            'type' => $this->type,
            'text_ar' => $this->text_ar,
            'text_id' => $this->text_id,
            'source' => $this->source,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', $this->content_id ? 'Konten berhasil diperbarui.' : 'Konten berhasil ditambahkan.');

        $this->closeModal();
        $this->resetForm();
    }

    public function edit($id)
    {
        $content = IslamicContent::findOrFail($id);
        $this->content_id = $id;
        $this->type = $content->type;
        $this->text_ar = $content->text_ar;
        $this->text_id = $content->text_id;
        $this->source = $content->source;
        $this->is_active = $content->is_active;
        $this->openModal();
    }

    public function delete($id)
    {
        IslamicContent::find($id)->delete();
        session()->flash('message', 'Konten berhasil dihapus.');
    }
}