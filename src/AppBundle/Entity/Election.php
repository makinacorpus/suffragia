<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Election
 *
 * @ORM\Table(name="election")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ElectionRepository")
 **/
/*
 * A election for one or more townHall
 */
class Election extends Concrete
{



    public function filterByTownHall(TownHall $townHall){
        $details = new ArrayCollection();
        foreach ($this->getDetailsElectionTownHall() as $t){
            if($t->getTownHall() === $townHall){
                $details[] = $t;
            }
        }
        $this->setDetailsElectionTownHall($details);
        return $this;
    }


    /**
     * @var MetaElection $metaElection
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MetaElection",inversedBy="elections",cascade={"persist"})
     * @ORM\JoinColumn(name="metaElection", referencedColumnName="id", nullable=false)
     */
    private $metaElection;


    /**
     * @var Collection DetailElectionTownHall
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DetailElectionTownHall",mappedBy="election",cascade = {"remove","persist"})
     */
    private $detailsElectionTownHall;

    /**
     * @var Collection Candidat
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Candidat",mappedBy="election",cascade = {"remove","persist"})
     */
    private $candidats;


    /**
     * @var boolean
     * @ORM\Column(name="close", type="boolean", nullable=true)
     */
    private $finished;

    /**
     * @var string
     * @ORM\Column(name="backup", type="string", length=10000,nullable=true)
     */
    private $json;


    /*
     * @var Collection TownHall
     */
    public $townHalls;


    /**
     * Election constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->detailsElectionTownHall = new ArrayCollection();
        $this->candidats = new ArrayCollection();
        $this->setFinished(false);


    }

    /**
     * @return mixed
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @param mixed $json
     * @return Election
     */
    public function setJson($json)
    {
        $this->json = $json;
        return $this;
    }



    /**
     * @return MetaElection
     */
    public function getMetaElection()
    {
        return $this->metaElection;
    }

    /**
     * @param mixed $metaElection
     * @return Election
     */
    public function setMetaElection($metaElection)
    {
        $this->metaElection = $metaElection;
        return $this;
    }





    /**
     * @return bool
     */
    public function isFinished()
    {
        return $this->finished;
    }

    /**
     * @param bool $finished
     * @return Election
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;
        return $this;
    }

    public function getDate() {

        return $this->metaElection->getDate();
    }


    /**
     * @return DetailElectionTownHall[]
     */
    public function getDetailsElectionTownHall()
    {
        return $this->detailsElectionTownHall->toArray();
    }


    /**
     * @param TownHall
     * @return DetailElectionTownHall
     */
    public function getDetailsElectionTownHallbyTownHall($townHall)
    {
        foreach ($this->getDetailsElectionTownHall() as $detail){
            if($detail->getDesk()===$townHall){
                return $detail;
            }
        }
        return null;
    }

    /**
     * @param Collection $detailsElectionTownHall
     * @return Election
     */
    public function setDetailsElectionTownHall($detailsElectionTownHall)
    {
        $this->detailsElectionTownHall = $detailsElectionTownHall;
        return $this;
    }

    /**
     * @param DetailElectionTownHall
     * @return Election
     */
    public function addDetailsElectionTownHall(DetailElectionTownHall $detailElectionTownHall)
    {
        $this->detailsElectionTownHall[] = $detailElectionTownHall;
        return $this;
    }


    public function removeDetailsElectionTownHallFromTownHall(TownHall $t){
        $good = array();
        foreach ($this->getDetailsElectionTownHall() as $d){
            if($d->getTownHall() !== $t){
                $good[] = $d;
            }
        }

        $this->setDetailsElectionTownHall($good);
    }


    /**
     * @return Candidat[]
     */
    public function getCandidats()
    {
        return $this->candidats->toArray();
    }


     function cmpCandidates(Candidat $a,Candidat $b)
    {
        return $a->getRank() >$b->getRank();
    }


    /**
     * @param Collection $candidats
     * @return Election
     */
    public function setCandidats($candidats)
    {
        $this->candidats = $candidats;
        return $this;
    }

    /**
     * @param Candidat
     * @return Election
     */
    public function addCandidat(Candidat $candidat){
        $this->candidats[] = $candidat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTownHalls()
    {
        $townHalls = array();
        foreach ($this->getDetailsElectionTownHall() as $detail){
            $townHalls[] = $detail->getTownHall();
        }
        return $townHalls;
    }

    /**
     * @return mixed
     */
    public function getTmpTownHalls()
    {

        return $this->townHalls;
    }


    /**
     * @param mixed $townHalls
     * @return Election
     */
    public function setTownHalls($townHalls)
    {
        $this->townHalls = $townHalls;
        return $this;
    }


    public function getType(){
        return $this->metaElection->getType();
    }

    public function __toString()
    {
        return $this->metaElection->getName();


    }


}

