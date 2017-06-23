<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PackHundred
 *
 * @ORM\Table(name="pack_hundred")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PackHundredRepository")
 */
class PackHundred extends Entity implements \JsonSerializable
{

    /**
     * @var Collection
     * @ORM\OneToMany("PackVoteTmp",targetEntity="AppBundle\Entity\PackVoteTmp",mappedBy="packHundred",cascade = {"persist","remove"})
     */
    private $packsVoteTmp;


    private $limit = 100;
    /**
     * @var DetailElectionDesk
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DetailElectionDesk",inversedBy="packsHundred",cascade={"persist"})
     * @ORM\JoinColumn(name="detailElectionDesk", referencedColumnName="id",nullable=false)
     */
    private $detailElectionDesk;

    /**
     * @var int
     * @ORM\Column(type="integer")
     *
     */
    private $counter;

    /**
     * PackHundred constructor.
     */
    public function __construct(DetailElectionDesk $detailElectionDesk)
    {
        $this->detailElectionDesk= $detailElectionDesk;
        $this->counter=0;
        $this->packsVoteTmp = new ArrayCollection();
    }

    /**
     * @return DetailElectionDesk
     */
    public function getDetailElectionDesk()
    {
        return $this->detailElectionDesk;
    }

    /**
     * @param DetailElectionDesk $detailElectionDesk
     * @return PackHundred
     */
    public function setDetailElectionDesk(DetailElectionDesk $detailElectionDesk)
    {
        $this->detailElectionDesk = $detailElectionDesk;
        return $this;
    }


    /**
     * @return int
     */
    public function getCounter()
    {
        return $this->counter;
    }

    /**
     * @return PackVoteTmp[]
     */
    private function getPacksVote()
    {

        return $this->packsVoteTmp->toArray();
    }



    /**
     * @param PackVoteTmp $packsVote
     * @return PackHundred
     */
    public function addPackVoteTmp(PackVoteTmp $packVote)
    {
        $this->packsVoteTmp[] = $packVote;
        return $this;
    }

    /**
     * @param int $counter
     * @return PackHundred
     */
    public function add(Candidat $candidat,$counter)
    {

        if($this->isExceedsTheLimit($counter)){
            throw new \Exception("erreur la limite est depassée compteur ".$this->getCounter()." voulue add".$counter );
        }
        $exist = false;
        foreach ($this->getPacksVote() as $p){
            if($p->getCandidat() == $candidat){
                $exist=true;
                $p->setNbVotes($p->getNbVotes()+$counter,false);
            }
        }

        if(!$exist){
            $p = new PackVoteTmp($this,$candidat,$counter);
            $this->addPackVoteTmp($p);
        }

        $this->counter =  $this->counter + $counter;

        return $this;
    }

    public function getMargin(){
        return $this->limit - ($this->counter);
    }

    public function isFiniched(){
        if($this->counter == $this->limit){
            return true;
        }
        return false;
    }

    public function isExceedsTheLimit($number){
        if($this->getCounter()+$number > $this->limit){
            return true;
        }
        return false;
    }

    /**
     * @return Candidat[]
     */
    public function getCandidates()
    {
        $c = array();
        $candidates = $this->getDetailElectionDesk()->getDetailElectionTownHall()->getElection()->getCandidats();
        foreach ($candidates as $candidat){
            $exist = false;
            foreach ($this->getPacksVote() as $p) {
                if($candidat->getId() === $p->getCandidat()->getId()) {
                    $p->getCandidat()->setData($p->getNbVotes());
                    $c[] = $p->getCandidat();
                    $exist=true;
                }
                if(!$exist) {
                    $candidat->setData(0);
                    $c[] = $candidat;
                }
            }
        }

        return $c;
    }
    public function getVotes(){
        $c = array();
        foreach ($this->getPacksVote() as $p) {
            $c[] = $p->getNbVotes();
        }
        return $c;
    }


    public function __toString()
    {
        $rep ="";
        $rep2 = "";
        foreach ($this->getPacksVote() as $p){
            $rep =$rep. $p->getCandidat()->getName().",";
            $rep2= $rep2.$p->getNbVotes().",";
        }
        return "packHundred".$rep."<>".$rep2;
    }

    /**
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

