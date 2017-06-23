<?php



namespace AppBundle\Service;

use AppBundle\Entity\Desk;
use AppBundle\Entity\Candidat;
use AppBundle\Entity\Election;
use AppBundle\Entity\TownHall;
use AppBundle\Entity\PackHoraire;

class PackHoraireService
{

    /**
     * @param PackHoraire[]
     * @return int
     */
    public function getNbParticipation($packs){
        $somme = 0;
        foreach ($packs as $p){
                $somme+=$p->getNbInscrits();

        }
        return $somme;
    }

    /**
     * @param  PackHoraire[]
     * @return PackHoraire[]
     */
    function sortByDate($packsHoraire){

        usort($packsHoraire, function($a, $b) {
                return ($a->getDateTime()> $b->getDateTime());
        });
        return $packsHoraire;
    }

    /**
     * @param PackHoraire[]
     * @param Desk
      * @return PackHoraire[]
      */
    public function FilterByDesk($packsHoraire,Desk $desk){
        $array = array();
        foreach ($packsHoraire  as $p){
            if($p->getDetailElectionDesk()->getDesk() === $desk){
                $array[] = $p;
            }
        }
        return $array;
    }


    /**
      * @return PackHoraire[]
      */
    public function FilterByCandidat($packsHoraire,Candidat $candidat){
        $array = array();
        foreach ($packsHoraire  as $p){
            if($p->getDetailElectionDesk()->getCandidat() === $candidat){
                $array[] = $p;
            }
        }
        return $array;
    }


    /**
     * @return PackHoraire[]
     */
    public function FilterByTownHall($packsHoraires,TownHall $townHall){
        $array = array();
        foreach ($packsHoraires  as $p){
            if($p->getDetailElectionDesk()->getDesk()->getQg()->getTownHall() === $townHall){
                $array[] = $p;
            }
        }
        return $array;
    }

    /**
     * @return PackHoraire[]
     */
    public function filterByHour($packsHoraires,$hour){
        $hour=$hour."";
        $array = array();
        foreach ($packsHoraires  as $p){
            if($p->getDateTime()->format("H")==$hour){
                $array[] = $p;
            }
        }
        return $array;
    }

    /**
     * @return int
     */
    public function getParticipationByDesk($packsHoraires,Desk $desk){
        $somme = 0;
        foreach ($packsHoraires  as $p){
            if($p->getDetailElectionDesk()->getDesk() === $desk){
                $somme+= $p->getNbVotant();
            }
        }
        return $somme;
    }

    /**
     * @return int
     */
    public function getParticipationByDeskByHour($packsHoraires,Desk $desk,$hour){
        $somme = 0;
        foreach ($packsHoraires  as $p){
            if($p->getDetailElectionDesk()->getDesk() === $desk and $p->getDateTime()->format("H")==="".$hour){
                return $p->getNbVotant();
            }


        }
        return 0;
    }
}
