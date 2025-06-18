<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\RunningText;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class RunningTextManager extends Component
{
    use WithPagination;
 
    public $text_id;
    public $content;
    public $is_active = true;
    public $isModalOpen = false;

    protected $rules = [
        'content' => 'required|min:10',
        'is_active' => 'required|boolean',
    ];
    
    public function render()
    {
        $texts = RunningText::latest()->paginate(5);
        return view('livewire.admin.running-text-manager', [
            'texts' => $texts,
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
        $this->text_id = null;
        $this->content = '';
        $this->is_active = true;
    }

    public function store()
    {
        $this->validate();

        RunningText::updateOrCreate(['id' => $this->text_id], [
            'content' => $this->content,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', $this->text_id ? 'Teks berhasil diperbarui.' : 'Teks berhasil ditambahkan.');

        $this->closeModal();
        $this->resetForm();
    }

    public function edit($id)
    {
        $text = RunningText::findOrFail($id);
        $this->text_id = $id;
        $this->content = $text->content;
        $this->is_active = $text->is_active;
        $this->openModal();
    }

    public function delete($id)
    {
        RunningText::find($id)->delete();
        session()->flash('message', 'Teks berhasil dihapus.');
    }
}