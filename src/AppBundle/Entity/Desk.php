<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Desk
 *
 * @ORM\Table(name="desk")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeskRepository")
 */
class Desk extends Concrete
{


    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DetailElectionDesk", mappedBy="desk",cascade={"remove"},)
     */
    private $detailsElectionDesk;

    /**
     * @var Desk
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Desk",inversedBy="subOffices",cascade={"persist"})
     */
    private $bossDesk;


    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Desk", mappedBy="bossDesk",cascade={"persist","remove"},)
     */
    private $subOffices;



    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Qg",inversedBy="deskx",cascade={"persist"})
     * @ORM\JoinColumn(name="qg_id", referencedColumnName="id", nullable=false)
     */
    private $qg;


    /**
     * @var int
     * @ORM\Column(type="integer",unique=false,nullable=true)
     *
     */
    private $deskNumber;

    /**
     * @return Desk
     */
    public function __construct()
    {
        parent::__construct();
        $this->detailsDesk = new ArrayCollection();
        $this->bossDesk=null;
        $this->deskNumber=0;
        return $this;

    }

    /**
     * @return Desk
     */
    public function getBossDesk()
    {
        return $this->bossDesk;
    }

    /**
     * @param Desk $bossDesk
     * @return Desk
     */
    public function setBossDesk($bossDesk)
    {
        $this->bossDesk = $bossDesk;
        return $this;
    }

    /**
     * @return int
     */
    public function getDeskNumber()
    {
        return $this->deskNumber;
    }

    /**
     * @param int $deskNumber
     * @return Desk
     */
    public function setDeskNumber($deskNumber)
    {
        $this->deskNumber = $deskNumber;
        return $this;
    }




    /**
     * @return mixed
     */
    public function getSubOffices()
    {
        $subs = array();
        foreach ($this->subOffices as $sub){
            if($sub->getId() != $this->getId()){
                $subs[] = $sub;
            }

        }

        return $subs;
    }

    /**
     * @param mixed $subOffices
     * @return Desk
     */
    public function setSubOffices($subOffices)
    {
        $this->subOffices = $subOffices;
        return $this;
    }

    /**
 * @param mixed $subOffices
 * @return Desk
 */
    public function addSubOffices($subOffices)
    {

        $this->subOffices[] = $subOffices;
        return $this;
    }


    public function isSubDesk(){
        if($this->bossDesk != null){
            return true;
        }
    }

    /**
     * @return Collection
     */
    public function getDetailsElectionDesk()
    {
        return $this->detailsElectionDesk;
    }

    /**
     * @param Collection $detailsDesk
    * @return Desk
     */

    public function setDetailsElectionDesk($detailsDesk)
    {
        $this->detailsElectionDesk = $detailsDesk;
        return $this;
    }


    /**
     * @return Qg
     */
    public function getQg()
    {
        return $this->qg;

    }

    /**
     * @param Qg $qg
     * @return Desk
     */
    public function setQg($qg)
    {
        $this->qg = $qg;
        return $this;
    }



    public function getTownHall(){
        return $this->getQg()->getTownHall();
    }


}

