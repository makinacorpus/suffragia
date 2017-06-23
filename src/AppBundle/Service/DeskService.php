<?php



namespace AppBundle\Service;

use AppBundle\Entity\Desk;
use AppBundle\Entity\Candidat;
use AppBundle\Entity\DetailElectionDesk;
use AppBundle\Entity\Election;
use AppBundle\Entity\TownHall;
use AppBundle\Entity\PackHoraire;
use AppBundle\Entity\PackVote;
use Symfony\Component\Config\Definition\Exception\Exception;

class DeskService
{

    /**
     * @param Desk[]
     * @return Desk[]
     */
    public function filterByBossDesk($desks){
        $bosss = array();
        foreach ($desks as $desk){
            if($desk->getBossDesk() == null){
                $bosss[] = $desk;
            }
        }

       return $bosss;
    }



}