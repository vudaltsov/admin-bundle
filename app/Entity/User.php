<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table("`user`")
 */
class User
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var int
     */
    public $id = 0;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\Length(min=2)
     */
    public $text = '';
}
