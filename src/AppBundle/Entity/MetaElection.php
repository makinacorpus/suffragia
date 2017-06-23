<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * MetaElection
 *
 * @ORM\Table(name="meta_election")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MetaElectionRepository")
 */
/*
 * A general detail for a election
 */
class MetaElection extends Concrete
{

    const TYPE_MUNICIPALE = "MUNICIPALE";
    const TYPE_DEPARTEMENT = "DEPARTEMENT";
    const TYPE_REGIONALE = "REGIONALE";
    const TYPE_PRESIDENTIELLE = "PRESIDENTIELLE";
    const TYPE_LEGISLATIVE = "LEGISLATIVE";
    const TYPE_SENATORIALE = "SENATORIALE";
    const TYPE_EUROPEENE = "EUROPEENE";
    const TYPE_OTHER = "AUTRE";

    /**
     * @var Collection Election
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Election",mappedBy="metaElection",cascade = {"remove","persist"})
     */
    private $elections;


    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="datetime",nullable=false)
     */
    private $date;


    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * MetaElection constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setType(self::TYPE_LEGISLATIVE);

    }


    /**
     * Set elections
     *
     * @param Collection $elections
     *
     * @return MetaElection
     */
    public function setElections($elections)
    {
        $this->elections = $elections;

        return $this;
    }

    /**
     * Get elections
     *
     * @return Election[]
     */
    public function getElections()
    {
        return $this->elections->toArray();
    }


    public function addElection($election){
        $this->elections[]  =$election;
    }


    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return MetaElection
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @param string $type
     * @return MetaElection
     */
    public function setType($type)
    {
        if(!in_array($type,self::getAvailableTypes())){
            throw new \Exception("erreur de type");
        }
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array<string>
     */
    public static function getAvailableTypes()
    {
        return [
            self::TYPE_DEPARTEMENT,
            self::TYPE_EUROPEENE,
            self::TYPE_LEGISLATIVE,
            self::TYPE_MUNICIPALE,
            self::TYPE_OTHER
        ];
    }


    public static function getIndexOfState($state)
    {
        if (in_array($state, self::getAvailableTypes())) {
            return array_search($state,self::getAvailableTypes());
        }
        throw new \Exception("error, the state not exist");
    }

}

