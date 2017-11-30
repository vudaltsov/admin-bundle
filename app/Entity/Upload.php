<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ruvents\UploadBundle\Entity\AbstractUpload;

/**
 * @ORM\Entity()
 */
class Upload extends AbstractUpload
{
}
