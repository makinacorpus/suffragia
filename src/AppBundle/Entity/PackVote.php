<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dompdf\Exception;

/**
 * PackVote
 *
 * @ORM\Table(name="PackVote")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PackVoteRepository")
 */
class PackVote extends Pack
{


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Candidat",inversedBy="packsVote",cascade={"persist"})
     * @ORM\JoinColumn(name="candidat", referencedColumnName="id", nullable=false)
     */
    private $candidat;

    /**
     * @var int
     * @ORM\Column(type="integer")
     *
     */
    private $nbVotes;

    /**
     * @var DetailElectionDesk
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DetailElectionDesk",inversedBy="packsVote",cascade={"persist"})
     * @ORM\JoinColumn(name="detailElectionDesk", referencedColumnName="id",nullable=false)
     */
    private $detailElectionDesk;



    /**
     * PackVote constructor.
     * @param Candidat $candidat
     * @param DetailElectionDesk $detailElectionDesk
     * @param $numberVote
     */
    public function __construct(DetailElectionDesk $detailElectionDesk,Candidat $candidat)
    {
        parent::__construct();
        $this->candidat= $candidat;
        $this->detailElectionDesk = $detailElectionDesk;
        $this->nbVotes= 0;
    }

    /**
     * @return DetailElectionDesk
     */
    public function getDetailElectionDesk(){

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
    public function setDetailElectionDesk(DetailElectionDesk $detailElectionDesk)
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
     * @return Candidat
     */
    public function getCandidat()
    {
        return $this->candidat;
    }

    /**
     * @param mixed $candidat
     * @return PackVote
     */
    public function setCandidat($candidat)
    {
        $this->candidat = $candidat;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbVotes()
    {
        return $this->nbVotes;
    }

    /**
     * @param int $nbVotes
     * @return PackVote
     */
    public function setNbVotes($nbVotes,$inform = true)
    {
        if($inform) {
            $this->getDetailElectionDesk()->informVote($this->getCandidat(), -($this->getNbVotes() - $nbVotes));
        }
        $this->nbVotes = $nbVotes;

        return $this;
    }


    public function __toString()
    {
        return "packVote, desk ".$this->getDesk()." candidat ".$this->getCandidat();
    }

}

