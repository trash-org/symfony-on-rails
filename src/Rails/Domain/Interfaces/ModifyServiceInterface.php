<?php

namespace App\Rails\Domain\Interfaces;

interface ModifyServiceInterface
{

    public function create($data);

    public function updateById($id, $data);

    public function deleteById($id);

}