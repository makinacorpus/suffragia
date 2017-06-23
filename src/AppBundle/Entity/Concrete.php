<?php
/**
 * Created by PhpStorm.
 * User: tux
 * Date: 12/05/17
 * Time: 16:26
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
class Concrete extends Entity
{



    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255,nullable=false)
     */
    protected $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Entity
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getNom(){
        return $this->getName();
    }

    public function setNom($nom){
        $this->setName($nom);
    }

    function __construct()
    {
        parent::__construct();
        return $this->name;
    }

    public function __toString()
    {
        return $this->name;
    }
}