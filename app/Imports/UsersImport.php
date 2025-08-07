<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $user = User::create([
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password'] ?? 'password'),
            'mobile' => $row['mobile'],
            'whatsapp' => $row['whatsapp'],
            'category' => $row['category'],
        ]);

        if (!empty($row['roles'])) {
            $roles = explode(',', $row['roles']);
            $user->syncRoles($roles);
        }

        return $user;
    }
}