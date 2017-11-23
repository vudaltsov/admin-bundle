<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table("`user`")
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\Length(min=2)
     */
    public $text;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group")
     *
     * @var Group
     */
    public $group;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     *
     * @var array
     */
    public $simpleArray;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     *
     * @var \DateTimeImmutable
     */
    public $date;

    public function __toString()
    {
        return $this->id.' user';
    }

    /**
     * @ORM\PostLoad()
     */
    public function post()
    {
        $this->simpleArray[0] = new \stdClass();
    }
}
