<?php

namespace App\Reposirtories;

interface RepositoryInterface
{
    public function baseQuery($relations = []);
    public function getById($id);
    public function store($requestParam);
    public function update($id, $requestParam);
    public function delete($requestParam);
}
