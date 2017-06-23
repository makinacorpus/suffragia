<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PackHoraire
 *
 * @ORM\Table(name="PackHoraire")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PackHoraireRepository")
 */
class PackHoraire extends Pack
{

    function __construct()
    {
        parent::__construct();

    }


    /**
     * @var DetailElectionDesk
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DetailElectionDesk",inversedBy="packsHoraire",cascade={"persist"})
     * @ORM\JoinColumn(name="detailElectionDesk", referencedColumnName="id",nullable=false)
     */
    private $detailElectionDesk;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateTime", type="datetime")
     */
    private $dateTime;

    /**
     * @var int
     *
     * @ORM\Column(name="nbVotant", type="integer", nullable=true)
     */
    private $nbVotant;


    /**
     * @return DetailElectionDesk
     */
    public function getDetailElectionDesk()
    {
        return $this->detailElectionDesk;
    }

    public function getDesk(){
        return $this->getDetailElectionDesk()->getDesk();
    }

    public function setDesk($b){
        return $this->getDetailElectionDesk()->setDesk($b);
    }

    /**
     * @param DetailElectionDesk $detailElectionDesk
     * @return Pack
     */
    public function setDetailElectionDesk($detailElectionDesk)
    {
        $this->detailElectionDesk = $detailElectionDesk;
        return $this;
    }
    public function getElection(){
        return $this->getDetailElectionDesk()->getDetailElectionTownHall()->getElection();
    }

    public function setElection($b){
        return $this->getDetailElectionDesk()->getDetailElectionTownHall()->setElection($b);
    }


    /**
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     * @return PackHoraire
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
        return $this;
    }



    /**
     * @return int
     */
    public function getNbVotant()
    {
        return $this->nbVotant;
    }

    /**
     * @param int $nbVotant
     * @return PackHoraire
     */
    public function setNbVotant($nbVotant)
    {
        $this->nbVotant = $nbVotant;
        return $this;
    }



    public function __toString()
    {
        return "Participation de ".$this->nbVotant." le ".$this->dateTime->format("d/m/Y H:i:s");
    }
}

