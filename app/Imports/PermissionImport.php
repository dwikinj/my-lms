<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Spatie\Permission\Models\Permission;

class PermissionImport implements ToModel, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Permission([
            'name' => $row[0],
            'group_name' => $row[1],
        ]);
    }

    public function rules(): array
    {
        return [
            '0' => 'required|string|unique:permissions,name',
            '1' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '0.required' => 'Nama permission wajib diisi.',
            '0.unique' => 'Nama permission (:input) sudah ada di database.',
            '1.required' => 'Grup permission wajib diisi.',
        ];
    }
}