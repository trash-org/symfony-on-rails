<?php

namespace App\Rails\Domain\Interfaces;

interface CrudRepositoryInterface extends ReadAllServiceInterface, ReadOneServiceInterface, ModifyServiceInterface, RelationConfigInterface
{



}