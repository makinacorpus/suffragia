<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Qg
 *
 * @ORM\Table(name="qg")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QgRepository")
 */
class Qg extends Concrete
{


    /**
     * @Assert\NotBlank(message="Vous devez préciser votre townHall.")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TownHall",inversedBy="qgs",cascade={"persist"})
    * @ORM\JoinColumn(name="townHall_id", referencedColumnName="id", nullable=false)
     */
    private $townHall;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Desk", mappedBy="qg",cascade={"persist", "remove"})
     */
    private $deskx;

    /**
     * @var string
     *
     * @ORM\Column(name="localisation", type="string", length=255)
     */
    private $localisation;



    /**
     * @return Qg
     * @param string $nom
     * @param TownHall $townHall
     */
    public function __construct()
    {
        parent::__construct();
        $this->deskx= new ArrayCollection();
        return $this;
    }



    /**
     * @return TownHall
     */
    public function getTownHall()
    {
        return $this->townHall;
    }

    /**
     * @param TownHall $townHall
     * @return Qg
     */
    public function setTownHall($townHall)
    {
        $this->townHall = $townHall;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getDeskx()
    {
        return $this->deskx->toArray();
    }
    /**
     * @return Desk[]
     */
    public function getDesks()
    {
        return $this->deskx->toArray();
    }
    /**
     * @param Collection $deskx
     * @return Qg
     */
    public function setDeskx($deskx)
    {
        $this->deskx = $deskx;
        return $this;
    }

    /**
     * @param Desk $desk
     * @return Qg
     */
    public function addDesk($desk)
    {
        $this->deskx[] = $desk;
        return $this;
    }


    /**
     * @return string
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * @param string $localisation
     * @return Qg
     */
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;
        return $this;
    }


}

