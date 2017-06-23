<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Detail
 *
 * @ORM\Table(name="detail_election_desk")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DetailElectionDeskRepository")
 */
/*
 * a desk + 1 election
 */
class DetailElectionDesk extends Entity implements \JsonSerializable
{
    const STATE_PROGRESS = "EN COURS";
    const STATE_CLOSE = "FERMÉ";
    const STATE_VALID = "VALIDÉ";
    const STATE_RequestVALID = "Demande de validation";

    /**
     * @var Desk
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Desk",cascade = {"persist"},inversedBy="detailsElectionDesk")
     * @ORM\JoinColumn(name="desk", referencedColumnName="id", nullable=false)
     */
    private $desk;

    /**
     * @var string
     * @ORM\Column(name="backup", type="string", length=10000, nullable=false)
     */
    private $backup;
    /**
     * @var string
     * @ORM\Column(name="state", type="string", length=255, nullable=false)
     */
    private $state;

    /**
     * @var Collection
     * @ORM\OneToMany("PackVote",targetEntity="AppBundle\Entity\PackVote",mappedBy="detailElectionDesk",cascade = {"persist","remove"})
     */
    private $packsVote;

    /**
     * @var Collection
     * @ORM\OneToMany("PackHundred",targetEntity="AppBundle\Entity\PackHundred",mappedBy="detailElectionDesk",cascade = {"persist","remove"})
     */
    private $packsHundred;


    /**
     * @var Collection
     * @ORM\OneToMany("PackHoraire",targetEntity="AppBundle\Entity\PackHoraire",mappedBy="detailElectionDesk",cascade = {"persist","remove"})
     */
    private $packsHoraire;

    /**
     * @var DetailElectionTownHall
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DetailElectionTownHall", inversedBy="detailsElectionDesk",cascade = {"persist"})
     * @ORM\JoinColumn(name="detail_election_townHall_id", referencedColumnName="id", nullable=false)
     */
    private $detailElectionTownHall;

    /**
     * @var int
     *
     * @ORM\Column(name="nbRegistered", type="integer",nullable=true)
     */
    private $nbRegistered;

    /**
     * @var int
     *
     * @ORM\Column(name="nbSignature", type="integer",nullable=true)
     */
    private $nbSignature;

    /**
     * DetailElectionDesk constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setPacksVote(new ArrayCollection());
        $this->setPacksHoraire(new ArrayCollection());
        $this->setPacksHundred(new ArrayCollection());
        $this->setNbSignature(0);
        $this->setNbRegistered(0);
        $this->backup= "";
        $this->setState(DetailElectionDesk::STATE_PROGRESS);
    }

    /**
     * @param Collection $packsVote
     * @return DetailElectionDesk
     */
    public function setPacksVote($packsVote)
    {
        $this->packsVote = $packsVote;
        return $this;
    }




    /**
     * @return PackHundred
     */
    public function getLastPacksHundred()
    {
        $last = null;
        foreach ($this->packsHundred as $p){
            $last = $p;
        }
        return $last;
    }


    /*
     * @return Collection
     */
    public function getPacksHundred()
    {
        return $this->packsHundred->toArray();
    }

    /**
     * @param Collection $packsHundred
     * @return DetailElectionDesk
     */
    public function setPacksHundred($packsHundred)
    {
        $this->packsHundred = $packsHundred;
        return $this;
    }
    /**
     * @param PackHundred $packsHundred
     * @return DetailElectionDesk
     */
    public function addPacksHundred(PackHundred  $packHundred)
    {
        $this->packsHundred[] = $packHundred;
        return $this;
    }


    public function informVote(Candidat $candidat,$numberVote){
        if(count($this->getPacksHundred())===0){
            $p = new PackHundred($this);
            $this->addPacksHundred($p);
            $this->getLastPacksHundred()->add($candidat, $numberVote);
            return;

        }

        if($numberVote>0) {
            if ($this->getLastPacksHundred()->isExceedsTheLimit($numberVote)) {
                $excess = $this->getLastPacksHundred()->getMargin();
                $this->getLastPacksHundred()->add($candidat,$excess);
                $numberVote = $numberVote - $excess;
                $this->addPacksHundred(new PackHundred($this));
                $this->informVote($candidat, $numberVote);
            } else {
                $this->getLastPacksHundred()->add($candidat, $numberVote);
            }
        }
        else{
                $this->getLastPacksHundred()->add($candidat, $numberVote);
        }

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
            self::STATE_CLOSE,
            self::STATE_PROGRESS,
            self::STATE_VALID,
            self::STATE_RequestVALID,
        ];
    }

    public static function getIndexOfState($state)
    {
        if (in_array($state, self::getAvailableTypes())) {
            return array_search($state,self::getAvailableTypes());
        }
        throw new \Exception("error, the state not exist");
    }


    /**
     * @param string $state
     * @return DetailElectionDesk
     */
    public function setState($state)
    {
        if(in_array($state,self::getAvailableTypes())) {
            $this->state = $state;
            return $this;
        }
        throw new \Exception("error, the state not exist");
    }

    /**
     * @return int
     */
    public function getNbSignature()
    {
        return $this->nbSignature;
    }

    /**
     * @param int $nbSignature
     * @return DetailElectionDesk
     */
    public function setNbSignature($nbSignature)
    {
        $this->nbSignature = $nbSignature;
        return $this;
    }


    /**
     * @return Desk
     */
    public function getDesk()
    {
        return $this->desk;
    }

    /**
     * @param Desk $desk
     * @return DetailElectionDesk
     */
    public function setDesk($desk)
    {
        $this->desk = $desk;
        return $this;
    }

    /**
     * @return DetailElectionTownHall
     */
    public function getDetailElectionTownHall()
    {
        return $this->detailElectionTownHall;
    }

    /**
     * @param DetailElectionTownHall $detailElectionTownHall
     * @return DetailElectionDesk
     */
    public function setDetailElectionTownHall(DetailElectionTownHall $detailElectionTownHall)
    {
        $this->detailElectionTownHall = $detailElectionTownHall;
        return $this;
    }

    /**
     * @return int
     * @deprecated
     */
    public function getNbInscrits()
    {
        return $this->getNbRegistered();
    }

    /**
     * @param int $nbInscrits
     * @return DetailElectionDesk
     * @deprecated
     */
    public function setNbInscrits($nbInscrits)
    {
        $this->setNbRegistered($nbInscrits);
        return $this;
    }


    /**
     * @return int
     */
    public function getNbRegistered()
    {
        return $this->nbRegistered;
    }

    /**
     * @param int $registered
     * @return DetailElectionDesk
     */
    public function setNbRegistered($registered)
    {
        $this->nbRegistered = $registered;
        return $this;
    }


    /**
     * @return PackVote[]
     */
    public function getPacksVote()
    {
        return $this->packsVote->toArray();
    }



    /*
      * @param PackVote
      * @return DetailElectionDesk
      */
    public function addPackVote(PackVote $packVote){
        $this->packsVote[] = $packVote;
        return $this;
    }


    /**
     * @return array PackHoraire
     */
    public function getPacksHoraire()
    {
        return $this->packsHoraire->toArray();
    }


    /**
     * @param Collection $packHoraire
     * @return DetailElectionDesk
     */
    public function setPacksHoraire($packsHoraire)
    {
        $this->packsHoraire = $this->arrayToArrayCollection($packsHoraire);
        return $this;
    }

    /*
      * @param PackHoraire
      * @return DetailElectionDesk
      */
    public function addPackHoraire(PackHoraire $packHoraire){
        $this->packsHoraire[] = $packHoraire;
        return $this;
    }

    public function __toString()
    {
        return "detail election desk,desk: ".$this->getDesk()->getName()." dem :".$this->getDetailElectionTownHall();
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
            $resume["NbRegistered"] = $c->getId();
            $resume["id"] = $c->getId();
            $resume["nom"] = $c->getNom();
            $resume["nbVote"] = $c->getData();
            $resume["balise"] = "nbVoteHundred".$c->getId();

            $complete[$c->getName()] = $resume;
        }


        //return $complete;
    }


}

