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

class FactoryService
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
    public function generateCandidate($name = null, Election $election = null)
    {
        $name = ($name === null) ? $this->generateRandomString() : $name;
        $election = ($election === null) ? $this->generateElection() : $election;
        $candidate = new Candidat();
        $candidate->setNom($name);
        $candidate->setElection($election);
        $election->addCandidat($candidate);
        $this->em->persist($candidate);
        $this->em->flush($candidate);
        return $candidate;
    }

    /**
     * @param string $name
     * @param TownHall $townHall
     * @return Qg
     */
    public function generateQg($name = null, TownHall $townHall = null)
    {
        $name = ($name === null) ? $this->generateRandomString() : $name;
        $townHall = ($townHall === null) ? $this->generateTownHall() : $townHall;
        $qg = new Qg();
        $qg->setTownHall($townHall);
        $qg->setNom($this->generateRandomString());
        $qg->setLocalisation($this->generateRandomString());
        $townHall->addQg($qg);
        $this->em->persist($qg);
        $this->em->persist($townHall);

        $this->em->flush();
        return $qg;
    }


    /**
     * @param string $name
     * @param \DateTime $dateTime
     * @return Election
     */
    public function generateElection($name = null, \DateTime $dateTime = null,MetaElection $meta = null)
    {
        $name = ($name === null) ? $this->generateRandomString() : $name;
        $dateTime = ($dateTime === null) ? new \DateTime() : $dateTime;
        $election = new Election();
        $election->setNom($name);
        $this->em->persist($election);
        $this->em->flush($election);
        return $election;
    }

    /**
     * @param string $name
     * @param \DateTime $dateTime
     * @return MetaElection
     */
    public function generateMetaElection($name = null, \DateTime $dateTime = null)
    {
        $name = ($name === null) ? $this->generateRandomString() : $name;
        $dateTime = ($dateTime === null) ? new \DateTime() : $dateTime;
        $election = new MetaElection();
        $election->setType(MetaElection::TYPE_OTHER);
        $election->setNom($name);
        $election->setDate($dateTime);
        $this->em->persist($election);
        $this->em->flush($election);
        return $election;
    }



    /**
     * @param string $name
     * @return TownHall
     */
    public function generateTownHall($name = null)
    {
        $name = ($name === null) ? $this->generateRandomString() : $name;
        $townHall = new TownHall();
        $townHall->setNom($name);
        $townHall->setLogo("mon logo");
        $townHall->setCodePostal(rand(0,99999));
        $this->em->persist($townHall);
        $this->em->flush($townHall);
        return $townHall;
    }


    /**
     * @param string $name
     * @param TownHall $townHall
     * @return DetailElectionTownHall
     */
    public function generateDetailElectionTownHall(TownHall $townHall = null, Election $election = null)
    {
        $townHall = ($townHall === null) ? $this->generateTownHall() : $townHall;
        $election = ($election === null) ? $this->generateElection() : $election;
        $detailElectionTownHall = new DetailElectionTownHall();
        $detailElectionTownHall->setTownHall($townHall);
        $detailElectionTownHall->setElection($election);
        $townHall->addDetailElectionTownHall($detailElectionTownHall);
        $election->addDetailsElectionTownHall($detailElectionTownHall);



        $this->em->persist($detailElectionTownHall);
        $this->em->persist($election);
        $this->em->persist($townHall);

        $this->em->flush();
        return $detailElectionTownHall;
    }

    /**
     * @param string $name
     * @param Qg $qg
     * @return Desk
     */
    public function generateDesk($name = null, Qg $qg = null)
    {
        $name = ($name === null) ? $this->generateRandomString() : $name;
        if($qg === null){
            $townHall = $this->generateTownHall();
            $qg = $this->generateQg($townHall);
        }
        $desk = new Desk();
        $desk->setNom($name);
        $desk->setQg($qg);
        $qg->addDesk($desk);
        $this->em->persist($desk);
        $this->em->persist($qg);

        $this->em->flush();
        return $desk;
    }


    /**
     * @param DetailElectionTownHall $detailElectionTownHall
     * @return DetailElectionDesk
     */
    public function generateDetailElectionDesk(DetailElectionTownHall $detailElectionTownHall = null, Desk $desk = null, Qg $qg = null)
    {
        $detailElectionTownHall = ($detailElectionTownHall === null) ? $this->generateDetailElectionTownHall() : $detailElectionTownHall;
        if ($qg === null) {
            if (count($detailElectionTownHall->getTownHall()->getQgs()) > 0) {
                $qg = $detailElectionTownHall->getTownHall()->getQgs()[1];
            } else {
                $qg = $this->generateQG(null,$detailElectionTownHall->getTownHall());
                $detailElectionTownHall->getTownHall()->addQg($qg);
            }
        }
        $desk = ($desk === null) ? $this->generateDesk(null, $qg) : $desk;

        $detailElectionDesk = new DetailElectionDesk();
        $detailElectionDesk->setDetailElectionTownHall($detailElectionTownHall);
        $detailElectionDesk->setDesk($desk);
        $detailElectionTownHall->addDetailElectionDesk($detailElectionDesk);
        $this->em->persist($detailElectionDesk);
        $this->em->persist($detailElectionTownHall);
        $this->em->flush();
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



