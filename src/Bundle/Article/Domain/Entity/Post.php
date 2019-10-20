<?php

namespace App\Bundle\Article\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use php7rails\domain\BaseEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

}
