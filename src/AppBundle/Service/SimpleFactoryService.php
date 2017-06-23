<?php
/**
 * Created by PhpStorm.
 * User: tux
 * Date: 10/05/17
 * Time: 18:17
 */

namespace AppBundle\Service;


use AppBundle\Entity\Desk;
use AppBundle\Entity\Candidat;
use AppBundle\Entity\DetailElectionDesk;
use AppBundle\Entity\DetailElectionTownHall;
use AppBundle\Entity\Election;
use AppBundle\Entity\MetaElection;
use AppBundle\Entity\TownHall;
use AppBundle\Entity\Qg;

class SimpleFactoryService
{

    protected $em;
    protected  $electionService;

    public function __construct(\Doctrine\ORM\EntityManager $em,ElectionService $electionService)
    {
        $this->em = $em;
        $this->electionService = $electionService;
    }

    /**
     * @param string $name
     * @param Election $election
     * @return Candidat
     */
    public function generateCandidate(Election $election,$name = null)
    {
        $name = ($name === null) ? "candiat".$this->generateRandomString() : $name;
        $candidate = new Candidat();
        $candidate->setNom($name);
        $candidate->setElection($election);
        $election->addCandidat($candidate);
        return $candidate;
    }

    /**
     * @param string $name
     * @param TownHall $townHall
     * @return Qg
     */
    public function generateQg(TownHall $townHall,$name = null)
    {
        $name = ($name === null) ? "qg".$this->generateRandomString() : $name;
        $qg = new Qg();
        $qg->setTownHall($townHall);
        $qg->setNom($this->generateRandomString());
        $qg->setLocalisation($this->generateRandomString());

        $townHall->addQg($qg);
        return $qg;
    }


    /**
     * @param MetaElection $meta
     * @param string $name
     * @return Election
     */
    public function generateElection(MetaElection $meta,$name = null)
    {
        $name = ($name === null) ? "election.".$this->generateRandomString() : $name;
        $election = new Election();
        $election->setNom($name);
        $meta->addElection($election);
        $election->setMetaElection($meta);
        return $election;
    }

    /**
     * @param string $name
     * @param \DateTime $dateTime
     * @return MetaElection
     */
    public function generateMetaElection($name = null, \DateTime $dateTime = null)
    {
        $name = ($name === null) ? "meta".$this->generateRandomString() : $name;
        $dateTime = ($dateTime === null) ? new \DateTime() : $dateTime;
        $election = new MetaElection();
        $election->setType(MetaElection::TYPE_OTHER);
        $election->setNom($name);
        $election->setDate($dateTime);
        return $election;
    }



    /**
     * @param string $name
     * @return TownHall
     */
    public function generateTownHall($name = null)
    {
        $name = ($name === null) ? "townhall".$this->generateRandomString() : $name;
        $townHall = new TownHall();
        $townHall->setNom($name);
        $townHall->setLogo("mon logo");
        $townHall->setCodePostal(rand(0,99999));
        return $townHall;
    }


    /**
     * @param string $name
     * @param TownHall $townHall
     * @return DetailElectionTownHall
     */
    public function generateDetailElectionTownHall(TownHall $townHall,Election $election)
    {
        $detailElectionTownHall = new DetailElectionTownHall();
        $detailElectionTownHall->setTownHall($townHall);
        $detailElectionTownHall->setElection($election);
        $townHall->addDetailElectionTownHall($detailElectionTownHall);
        $election->addDetailsElectionTownHall($detailElectionTownHall);
        return $detailElectionTownHall;
    }

    /**
     * @param string $name
     * @param Qg $qg
     * @return Desk
     */
    public function generateDesk(Qg $qg,$name = null)
    {
        $name = ($name === null) ? "qg".$this->generateRandomString() : $name;
        $desk = new Desk();
        $desk->setNom($name);
        $desk->setQg($qg);
        $qg->addDesk($desk);

        return $desk;
    }


    /**
     * @param DetailElectionTownHall $detailElectionTownHall
     * @return DetailElectionDesk
     */
    public function generateDetailElectionDesk(DetailElectionTownHall $detailElectionTownHall,Desk $desk, Qg $qg)
    {

        $detailElectionDesk = new DetailElectionDesk();
        $detailElectionDesk->setDetailElectionTownHall($detailElectionTownHall);
        $detailElectionDesk->setDesk($desk);
        $detailElectionTownHall->addDetailElectionDesk($detailElectionDesk);
        return $detailElectionDesk;
    }



    /**
     * @param DetailElectionTownHall $detailElectionTownHall
     * @return DetailElectionDesk
     */
    public function generateAutoDetailElectionDesk()
    {
        $townHall = $this->generateTownHall();
        $metaElection = $this->generateMetaElection();
        $election = $this->generateElection($metaElection);
        $detailElectionTownHall = $this->generateDetailElectionTownHall($townHall,$election);
        $qg = $this->generateQg($townHall);
        $desk = $this->generateDesk($qg);
        $detailElectionDesk = new DetailElectionDesk();
        $detailElectionDesk->setDetailElectionTownHall($detailElectionTownHall);
        $detailElectionDesk->setDesk($desk);
        $detailElectionTownHall->addDetailElectionDesk($detailElectionDesk);
        return $detailElectionDesk;
    }



    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


}



