<?php

namespace App\Livewire\User;

use App\Imports\UsersImport;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Component;
use Livewire\WithFileUploads;

class ImportExportUsers extends Component
{
    use WithFileUploads;

    public $file;

    public function import()
    {
        $this->validate(['file' => 'required|mimes:xls,xlsx']);
        try {
            Excel::import(new UsersImport, $this->file);
            session()->flash('message', 'Users imported successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function render()
    {
        return view('livewire.user.import-export-users');
    }
}