<?php

namespace App\Repositories\Interfaces;

interface RoleRepositoryInterface
{
    public function all($filters = []);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}