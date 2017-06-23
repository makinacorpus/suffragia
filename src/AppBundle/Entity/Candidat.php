<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Candidat
 *
 * @ORM\Table(name="candidat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CandidatRepository")
 */
class Candidat extends Concrete
{



    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PackVote", mappedBy="candidat",cascade={"remove"})
     */
    private $packsVote;


    /**
     * @var int
     * @ORM\Column(type="integer",nullable=true)
     *
     */
    private $rank;




    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PackVoteTmp", mappedBy="candidat",cascade={"remove"})
     */
    private $packsVoteTmp;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Parti",cascade={"persist"})
     */
    private $parti;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Election",inversedBy="candidats",cascade={"persist"})
     * @ORM\JoinColumn(name="election", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank(message="merci de choisir une élection")
     */
    private $election;

    /**
     * @var boolean $blanc
     * @ORM\Column(name="blanc", type="boolean")
     */
    protected $blanc;

    /**
     * @var boolean $blanc
     * @ORM\Column(name="nul", type="boolean")
     */
    protected $nul;

    protected $nbVotes=0;


    /**
     * Candidat constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->nbVotes=0;
        $this->blanc= false;
        $this->nul=false;
        $this->rank = 0;
    }

    /**
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     * @return Candidat
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
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
     * @return Candidat
     */
    public function setNbVotes($nbVotes)
    {
        $this->nbVotes = $nbVotes;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBlanc()
    {
        return $this->blanc;
    }

    /**
     * @param bool $blanc
     * @return Candidat
     */
    public function setBlanc($blanc)
    {
        $this->blanc = $blanc;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNul()
    {
        return $this->nul;
    }

    /**
     * @param bool $nul
     * @return Candidat
     */
    public function setNul($nul)
    {
        $this->nul = $nul;
        return $this;
    }



    /**
     * @return Parti
     */
    public function getParti()
    {
        return $this->parti;
    }

    /**
     * @param mixed $parti
     * @return Candidat
     */
    public function setParti($parti)
    {
        $this->parti = $parti;
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
     * @param Election
     * @return Candidat
     */
    public function setElection($election)
    {
        $this->election = $election;
        return $this;
    }



    public function __toString()
    {
       return $this->getName();
    }


}

