<?php

namespace App\Repositories;

interface UserInterface
{
    public function getAll();

    public function getById($id);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);

    public function deposit($id, $amount);

    public function withdraw($id, $amount);
}