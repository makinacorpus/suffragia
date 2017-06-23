<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TownHall
 *
 * @ORM\Table(name="townHall")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TownHallRepository")
 */
class TownHall extends Concrete
{



    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Qg", mappedBy="townHall", cascade={"persist", "remove"},)
     */
    private $qgs;


    /*
     * Pour chaque election où une townHall participe il y a un detailTownHall
     */
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DetailElectionTownHall", mappedBy="townHall",cascade={"persist", "remove"})
     */
    private $detailsElectionTownHall;


    /**
     * @var string
     * @ORM\Column(name="logo", type="text", length=65535,nullable=false)
     * @Assert\NotBlank(message="Please, upload the logo as a png file.")
     * */
    private $logo;



    /**
     * @var int
     * @ORM\Column(type="integer",unique=true)
     *
     */
    private $codePostal;



    /**
     * @var int
     * @ORM\Column(type="integer",unique=false,nullable=true)
     *
     */
    private $circonscription;

    /**
     * @var int
     * @ORM\Column(type="integer",unique=false,nullable=true)
     *
     */
    private $numeroDepartement;


    /**
     * @return TownHall
     */
    public function __construct()
    {
        parent::__construct();
        $this->qgs = new ArrayCollection();
        $this->detailsElectionTownHall = new ArrayCollection();
        $this->codePostal=0;
        $this->circonscription = 0;
        $this->numeroDepartement=0;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * @param int $codePostal
     * @return TownHall
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;
        return $this;
    }

    /**
     * @return int
     */
    public function getCirconscription()
    {
        return $this->circonscription;
    }

    /**
     * @param int $circonscription
     * @return TownHall
     */
    public function setCirconscription($circonscription)
    {
        $this->circonscription = $circonscription;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumeroDepartement()
    {
        return $this->numeroDepartement;
    }

    /**
     * @param int $numeroDepartement
     * @return TownHall
     */
    public function setNumeroDepartement($numeroDepartement)
    {
        $this->numeroDepartement = $numeroDepartement;
        return $this;
    }





    /**
     * @return array
     */
    public function getQgs()
    {
        return $this->qgs->toArray();
    }

    /**
     * @return TownHall
     * @param Collection $qgs
     */
    public function setQgs($qgs)
    {
        $this->qgs = $qgs;
        return $this;
    }

    /**
     * @return TownHall
     * @param Qg $qg
     */
    public function addQg($qg){
        $this->qgs[] = $qg;
        return $this;
    }

    /**
     * @return TownHall
     * @param Qg $qg
     */
    public function removeQg($qg){
        if(($key = array_search($qg, $this->getQgs(), true)) !== FALSE) {
            unset($this->getQgs()[$key]);
        }
    }

    /**
     * @return DetailElectionTownHall[]
     */
    public function getDetailsElectionTownHall()
    {
        return $this->detailsElectionTownHall->toArray();
    }

    /**
     * @return TownHall
     * @param Collection $detailsElectionTownHall
     */
    public function setDetailsElectionTownHall($detailsElectionTownHall)
    {
        $this->detailsElectionTownHall = $detailsElectionTownHall;
        return $this;
    }

    /**
     * @return TownHall
     * @param DetailElectionTownHall $detailElectionTownHall
     */
    public function addDetailElectionTownHall($detailElectionTownHall){
        $this->detailsElectionTownHall[] = $detailElectionTownHall;
        return $this;
    }


    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     *@return TownHall
     * @param string $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
        return $this;

    }


}
