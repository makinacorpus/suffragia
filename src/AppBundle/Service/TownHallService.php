<?php



namespace AppBundle\Service;

use AppBundle\Entity\Desk;
use AppBundle\Entity\Candidat;
use AppBundle\Entity\Election;
use AppBundle\Entity\TownHall;
use AppBundle\Entity\Qg;

class TownHallService
{


    /**
     * @return Desk[]
     */
   public function getDeskx(TownHall $townHall){
        $deskx= array();
        foreach ($townHall->getQgs() as $qg){
            foreach ($qg->getDeskx() as $desk){
                $deskx[] = $desk;

            }
        }
        return $deskx;
   }

    /*
     * @return Election[]
     */
    public function getElections(TownHall $townHall){
       $details = $townHall->getDetailsElectionTownHall();
       $elections = array();
       foreach ($details as $d){
           $elections[] = $d->getElection();
       }

    }

}