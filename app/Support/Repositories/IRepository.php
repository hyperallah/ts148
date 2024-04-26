<?php

namespace App\Support\Repositories;

interface IRepository
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
}
