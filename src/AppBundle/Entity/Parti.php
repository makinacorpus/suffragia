<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parti
 *
 * @ORM\Table(name="parti")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PartiRepository")
 */
class Parti extends Concrete
{

    /**
     * Parti constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->color = "#0F3642";

    }

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255,nullable=true)
     */
    protected $color;

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return Parti
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }



}

