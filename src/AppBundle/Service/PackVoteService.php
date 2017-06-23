<?php



namespace AppBundle\Service;

use AppBundle\Entity\Desk;
use AppBundle\Entity\Candidat;
use AppBundle\Entity\DetailElectionDesk;
use AppBundle\Entity\Election;
use AppBundle\Entity\TownHall;
use AppBundle\Entity\PackVote;
use Symfony\Component\Config\Definition\Exception\Exception;

class PackVoteService
{

    /**
     * @param PackVote[]
     * @return int
     */
    public function getNbVotes($packsVote){
        $somme = 0;
        foreach ($packsVote as $p){
            /* @var PackVote $p */
                $somme+=$p->getNbVotes();

        }
        return $somme;
    }


    /**
     * @param PackVote[]
     * @param PackVote[]
     * @param DetailElectionDesk
     * @return PackVote[]
     */
    public function mergeByCandidate($packsVotes,$packsVotesBis,DetailElectionDesk $detailElectionDesk){
        $all = array();
        $candidates = $this->getCandidates($packsVotes);
        $packsVotes = array_merge($packsVotes,$packsVotesBis);
        foreach ($candidates as $candidate){
            $pvs = $this->filterByCandidat($packsVotes,$candidate);
            $p = new PackVote($detailElectionDesk,$candidate);
            $p->setDetailElectionDesk($detailElectionDesk);
            $p->setCandidat($candidate);
            foreach ($pvs as $pv){
                $p->setNbVotes($p->getNbVotes()+$pv->getNbVotes());
            }
            $all[] = $p;
        }
        return $all;
    }


    /**
     * @param PackVote[]
     * @param Desk
     * @return int
     */
    public function getNbVotesbyDesk($packsVote,Desk $desk){
        $somme = 0;
        foreach ($packsVote as $p){
            if($p->getDetailElectionDesk()->getDesk()===$desk){
                $somme+=$p->getNbVotes();
            }
        }
        return $somme;
    }

    /**
     * @param PackVote[]
     * @param TownHall
     * @return int
     */
    public function getNbVotesbyTownHall($packsVote,TownHall $townHall){
        $somme = 0;
        foreach ($packsVote as $p){
            if($p->getDetailElectionDesk()->getDetailElectionTownHall()->getTownHall()===$townHall){
                $somme+=$p->getNbVotes();
            }
        }
        return $somme;
    }

    /**
     * @param PackVote[]
     * @param Desk
     * @return int
     */
    public function getNbVotesbyCandidat($packsVote,Candidat $candidat){
        $somme = 0;
        foreach ($packsVote as $p){
            if($p->getCandidat()===$candidat){
                $somme+=$p->getNbVotes();
            }
        }
        return $somme;
    }


    public function getCandidates($packsVote){
        $candidates = array();
        foreach ($packsVote as $pack){
            if(!in_array($pack->getCandidat(),$candidates)){
                $candidates[]  = $pack->getCandidat();
            }
        }
        return $candidates;

    }






    /**
     * @param PackVote[]
     * @param Candidat
     * @return Candidat[]
     */
    public function getCandidatesSortedByVotes($packsVote){
        $candidates = $this->getCandidates($packsVote);
        $this->packsVote = $packsVote;
        usort($candidates,function ($a,$b){
            return $this->getNbVotes($this->filterByCandidat($this->packsVote,$a))<$this->getNbVotes($this->filterByCandidat($this->packsVote,$b));
        });
        return $candidates;
    }

    /**
     * @param PackVote[]
     * @param Candidat
     * @return PackVote[]
     */
    public function filterByCandidat($packsVote,Candidat $candidat){
        $packs = array();
        foreach ($packsVote as $p){
            if($p->getCandidat()===$candidat){
                $packs[] = $p;
            }
        }
        return $packs;
    }

    /**
     * @param PackVote[]
     * @param Candidat
     * @return PackVote[]
     */
    public function filterByNokCandidat($packsVote,Candidat $candidat){
        $packs = array();
        foreach ($packsVote as $p){
            if($p->getCandidat()!==$candidat){
                $packs[] = $p;
            }
        }
        return $packs;
    }
    /**
     * @param PackVote[]
     * @param Candidat
     * @return PackVote[]
     */
    public function filterByBlanc($packsVote){
        $packs = array();
        foreach ($packsVote as $p){
            if(strtolower($p->getCandidat()->getNom())=="blanc"){
                $packs[] = $p;
            }
        }
        return $packs;
    }

    /**
     * @param PackVote[]
     * @return PackVote[]
     */
    public function filterByNul($packsVote){
        $packs = array();
        foreach ($packsVote as $p){
            if(strtolower($p->getCandidat()->getNom())=="nul"){
                $packs[] = $p;
            }
        }
        return $packs;
    }

    /**
     * @param PackVote[]
     * @return PackVote[]
     */
    public function filterByValablementExprimes($packsVote){
        $invalid = array_merge($this->filterByNul($packsVote),$this->filterByBlanc($packsVote));
        $result = array_diff($packsVote,$invalid);
        return $result;
    }

    /**
     * @param PackVote[]
     * @return PackVote[]
     */
    public function filterByNonValablementExprimes($packsVote){
        return array_merge($this->filterByNul($packsVote),$this->filterByBlanc($packsVote));
    }
    /**
     * @param PackVote[]
     * @param Desk
     * @return PackVote[]
     */
    public function filterByDesk($packsVote,Desk $desk){
        $packs = array();
        foreach ($packsVote as $p){
            if($p->getDetailElectionDesk()->getDesk()===$desk){
                $packs[] = $p;
            }
        }
        return $packs;
    }



}