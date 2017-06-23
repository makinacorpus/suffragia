<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dompdf\Exception;

/**
 * PackVote
 *
 * @ORM\Table(name="PackVoteTmp")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PackVoteTmpRepository")
 */
class PackVoteTmp extends Pack
{


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Candidat",inversedBy="packsVoteTmp",cascade={"persist"})
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
     * @var PackHundred
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PackHundred",inversedBy="packsVoteTmp",cascade={"persist"})
     * @ORM\JoinColumn(name="packHundred", referencedColumnName="id",nullable=false)
     */
    private $packHundred;

    /**
     * PackVote constructor.
     * @param Candidat $candidat
     * @param DetailElectionDesk $detailElectionDesk
     * @param $numberVote
     */
    public function __construct(PackHundred $packHundred,Candidat $candidat,$numberVote = 0)
    {
        parent::__construct();
        $this->candidat= $candidat;
        $this->setNbVotes($numberVote,false);
        $this->packHundred = $packHundred;
    }

    /**
     * @return PackHundred
     */
    public function getPackHundred()
    {
        return $this->packHundred;
    }

    /**
     * @param PackHundred $packHundred
     * @return PackVote
     */
    public function setPackHundred($packHundred)
    {
        $this->packHundred = $packHundred;
        return $this;
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
     * @return mixed
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

