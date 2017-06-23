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

class CandidatService
{

    /*
     * sort candidat by nbVote in data attribute
     * @param Candidat[]
     * @return  Candidat[]
     */
    public function sort($candidats){
        return usort($candidats,"cmp");

    }

    private function cmp($a, $b)
    {
        return strcmp($a->getData(), $b->getData());
    }

    /**
     * @return  Candidat[]
     * @param Candidat[]
     */
     public function filterByCandidatesValid($candidates){
        $resume = array();
         /* @var Candidat[] $candidates */
        foreach ($candidates as $c){
            if($c->isBlanc() or $c->isNul()){}
            else{
                $resume[] = $c;
            }
        }
        return $resume;

    }

}