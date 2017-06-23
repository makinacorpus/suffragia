<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * DetailElectionTownHall
 *
 * @ORM\Table(name="detail_election_townHall")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DetailElectionTownHallRepository")
 */
/*
 *  * a townhall + 1 election

 */
class DetailElectionTownHall extends Entity implements \JsonSerializable
{

    const STATE_PUBLIC = "public";
    const STATE_PRIVATE = "privée";
    const STATE_FINICHED = "terminée";



    /**
     * @var Election
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Election",inversedBy="detailsElectionTownHall",cascade={"persist"})
     * @ORM\JoinColumn(name="election", referencedColumnName="id", nullable=false)
     */
    private $election;

    /**
     * @var TownHall
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TownHall",inversedBy="detailsElectionTownHall",cascade={"persist"})
     * @ORM\JoinColumn(name="townHall", referencedColumnName="id", nullable=false)
     */
    private $townHall;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_close", type="boolean")
     */
    private $isClose;


    /**
     * @var bool
     *
     * @ORM\Column(name="is_valid", type="boolean")
     */
    private $isValid;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $state;

    /**
     * @var string
     * @ORM\Column(name="backup", type="text", length=65535, nullable=false)
     */
    private $backup;

    /**
     * @var ArrayCollection $detailsElectionDesk
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DetailElectionDesk",mappedBy="detailElectionTownHall",cascade={"persist","remove"})
     */
    private $detailsElectionDesk;

    /**
     * @var int
     *
     * @ORM\Column(name="nbParticipants", type="integer", nullable=true)
     */
    private $nbParticipants;

    /**
     * DetailElectionTownHall constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->isClose = false;
        $this->isValid = false;
        $this->setState(DetailElectionTownHall::STATE_PRIVATE);
        $this->detailsElectionDesk = new ArrayCollection();
        $this->backup = "";
    }

    /**
     * @return string
     */
    public function getBackup()
    {
        return $this->backup;
    }

    /**
     * @param string $backup
     * @return DetailElectionTownHall
     */
    public function setBackup($backup)
    {
        $this->backup = $backup;
        return $this;
    }




    /**
     * @return bool
     */
    public function isIsClose()
    {
        return $this->isClose;
    }

    /**
     * @param bool $isClose
     * @return DetailElectionTownHall
     */
    public function setIsClose($isClose)
    {
        $this->isClose = $isClose;
        return $this;
    }


    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return array<string>
     */
    public static function getAvailableTypes()
    {
        return [
            self::STATE_PRIVATE,
            self::STATE_PUBLIC,
            self::STATE_FINICHED
        ];
    }

    /**
     * @param string $state
     * @return DetailElectionTownHall
     */
    public function setState($state)
    {
        if(in_array($state,self::getAvailableTypes())) {
            $this->state = $state;
            return $this;
        }
        //throw new \Exception("erreur de type");
    }

    public static function getIndexOfState($state)
    {
        if (in_array($state, self::getAvailableTypes())) {
            return array_search($state,self::getAvailableTypes());
        }
        throw new \Exception("error, the state not exist");
    }

    /**
     * @return bool
     */
    public function isIsValid()
    {
        return $this->isValid;
    }

    /**
     * @param bool $isValid
     * @return DetailElectionTownHall
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;
        return $this;
    }


    /**
     * @return Election
     */
    public function getElection()
    {
        return $this->election;
    }

    /**
     * @param Election $election
     * @return DetailElectionTownHall
     */
    public function setElection($election)
    {
        $this->election = $election;
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
     * @return DetailElectionTownHall
     */
    public function setTownHall($townHall)
    {
        $this->townHall = $townHall;
        return $this;
    }

    /**
     * @return DetailElectionDesk[]
     */
    public function getDetailsElectionDesk()
    {
        return $this->detailsElectionDesk->toArray();
    }


    /**
     * @param array $detailsDesk
     * @return DetailElectionTownHall
     */
    public function setDetailsElectionDesk($detailsDesk)
    {

        $this->detailsElectionDesk = $detailsDesk;
        return $this;
    }

    /**
     * @param DetailElectionDesk $detailsDesk
     * @return DetailElectionTownHall
     */
    public function addDetailElectionDesk(DetailElectionDesk $detailsDesk)
    {
        $this->detailsElectionDesk[] = $detailsDesk;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbParticipants()
    {
        return $this->nbParticipants;
    }

    /**
     * @param int $nbParticipants
     * @return DetailElectionTownHall
     */
    public function setNbParticipants($nbParticipants)
    {
        $this->nbParticipants = $nbParticipants;
        return $this;
    }

 public function __toString()
 {
     return "election".$this->getElection()." townHall: ".$this->getTownHall()->getName();
 }

    /*
* Specify data which should be serialized to JSON
* @link http://php.net/manual/en/jsonserializable.jsonserialize.php
* @return mixed data which can be serialized by <b>json_encode</b>,
* which is a value of any type other than a resource.
* @since 5.4.0
*/
    function jsonSerialize()
    {
        $complete = array();
        foreach ($this->getCandidates() as $c){

            $resume = array();



            $resume["id"] = $c->getId();
            $resume["nom"] = $c->getNom();
            $resume["nbVote"] = $c->getData();
            $resume["balise"] = "nbVoteHundred".$c->getId();

            $complete[$c->getName()] = $resume;
        }


        return $complete;
    }

}

