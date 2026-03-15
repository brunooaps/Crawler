<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public function mount()
    {
        $this->sortField = 'id';
        $this->sortDirection = 'asc';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $sortField = $this->sortField ?: 'id';
        $sortDirection = $this->sortDirection ?: 'asc';
        
        return view('livewire.product-list', [
            'products' => Product::with('images')
                ->where('title', 'like', '%' . $this->search . '%')
                ->orderBy($sortField, $sortDirection)
                ->paginate(25),
        ]);
    }
}
