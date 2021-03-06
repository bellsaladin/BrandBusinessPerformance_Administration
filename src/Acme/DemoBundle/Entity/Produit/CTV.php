<?php

namespace Acme\DemoBundle\Entity\Produit;

use Doctrine\ORM\Mapping as ORM;
use Acme\DemoBundle\Entity\Produit;

/**
 * @ORM\Table(name="produit")
 * @ORM\Entity
 */
class CTV extends Produit
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    public function __construct()
    {
        parent::__construct();
        $this->setEntityType('CTV');
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'CTV';
    }
}
