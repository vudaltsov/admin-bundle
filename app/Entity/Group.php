<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table("`group`")
 */
class Group
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
     * @ORM\Column(type="string")
     */
    public $title = '';

    public function __toString()
    {
        return $this->title;
    }
}
