<?php



namespace AppBundle\Service;

use AppBundle\Entity\Desk;
use AppBundle\Entity\Candidat;
use AppBundle\Entity\DetailElectionDesk;
use AppBundle\Entity\DetailElectionTownHall;
use AppBundle\Entity\Election;
use AppBundle\Entity\MetaElection;
use AppBundle\Entity\TownHall;
use AppBundle\Entity\PackHoraire;
use Doctrine\ORM\EntityManager;

class MetaElectionService
{


    public function getElectionByTownHall(MetaElection $meta,TownHall $townHall){
        foreach ($meta->getElections() as $election){
            foreach ($election->getDetailsElectionTownHall() as $detailElectionTownHall){
                if($detailElectionTownHall->getTownHall() === $townHall){
                    return $election;
                }
            }
        }
        return null;

    }

}