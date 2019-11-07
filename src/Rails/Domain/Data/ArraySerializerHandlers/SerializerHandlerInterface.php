<?php


namespace App\Rails\Domain\Data\ArraySerializerHandlers;

interface SerializerHandlerInterface
{

    public function encode($object);

}