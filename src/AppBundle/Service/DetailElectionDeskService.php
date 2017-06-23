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
use Symfony\Component\Finder\Exception\AccessDeniedException;

class DetailElectionDeskService
{

    /**
     * DetailElectionDeskService constructor.
     */
    public function __construct(PackVoteService $packVoteService,DeskService $deskService,PackHoraireService $packHoraire,ElectionService $electionService)
    {
        $this->packVoteService = $packVoteService;
        $this->deskService = $deskService;
        $this->packHoraireService = $packHoraire;
        $this->electionService = $electionService;
    }


    /**
     * @param Desk $desk
     * @param Election $election
     * @return DetailElectionDesk
     */
    public function getDetailElectionDesk(Desk $desk,Election $election){
        $detailElectionDeskService = $this;
        $detailsElectionDesk = $this->electionService->getDetailsElectionDesk($election);
        $detailElectionDesks = $detailElectionDeskService->filterByDesk($detailsElectionDesk,$desk);
        if(count($detailElectionDesks)!=1){
            $detailEM = $desk->getTownHall()->getDetailsElectionTownHall();
            foreach ($detailEM as $item) {
                $this->electionService->registerTownHallToElection($item->getElection(),$item->getTownHall());
            }
            $detailElectionDeskService = $this;
            $detailsElectionDesk = $this->electionService->getDetailsElectionDesk($election);
            $detailElectionDesks = $detailElectionDeskService->filterByDesk($detailsElectionDesk,$desk);
            if(count($detailElectionDesks)!=1) {
                throw new Exception("erreur interne, il faut 1 DEB pour cette action");
            }
        }
        $detailElectionDesk = $detailElectionDesks[0];
        return $detailElectionDesk;
    }







    /**
     * @param DetailElectionDesk[]
     * @param Desk
     * @return Desk[]
     */
    public function getDesk($details){
        $desks = array();
        foreach ($details as $d){
                $desks[] = $d->getDesk();
        }
        return $desks;
    }


    /**
     * @param DetailElectionDesk[] $details
     * @param Desk
     * @return DetailElectionDesk[]
     */
    public function filterByDesk($details,Desk $desk){
       $detailsElectionDesk = array();
       foreach ($details as $d){
           if($d->getDesk()===$desk){
               $detailsElectionDesk[] = $d;
           }
       }
       return $detailsElectionDesk;
    }


    public function filterByBoss($details){
        $bosss = array();
        foreach ($details as $detail) {
            if (!$detail->getDesk()->isSubDesk()) {
                $bosss[] = $detail;
            }
        }
        return $bosss;
    }


    public function filterBySubDesk($details){
        $subs = array();
        foreach ($details as $detail) {
            if ($detail->getDesk()->isSubDesk()) {
                $subs[] = $detail;
            }
        }
        return $subs;
    }

    /**
     * @param $details
     * @param $boss
     * find a boss
     */
    public function findBoss($bosss,$detail){
        foreach ($bosss as $d){
            if($d->getDesk() === $detail->getDesk()->getBossDesk()){
                return $d;
            }
        }
    }

    public function concatBosssWithSubDesks($details){
        $deskxBoss = $this->deskService->filterByBossDesk($this->getDesk($details));
        $bosss = $this->filterByBoss($details);
        $subs = $this->filterBySubDesk($details);
        foreach ($subs as $sub){

            $key  = array_search($this->findBoss($bosss,$sub),$bosss);
            if($key !== false) {
                $bosss[$key]->setPacksVote($this->packVoteService->mergeByCandidate($bosss[$key]->getPacksVote(),$sub->getPacksVote(),$bosss[$key]));
            }
            else{
                throw new AccessDeniedException();
            }
        }
        return $bosss;
    }




    /**
     * @param DetailElectionDesk[]
     * @param TownHall
     * @return DetailElectionDesk[]
     */
    public function filterByTownHall($details,TownHall $townHall){
        $detailsElectionDesk = [];
        foreach ($details as $d){
            if($d->getDesk()->getQg()->getTownHall()===$townHall){
                $detailsElectionDesk[] = $d;
            }
        }
        return $detailsElectionDesk;
    }


    /**
     * @param DetailElectionDesk[]
     * @param Desk
     * @return int
     */
    public function getSumNbSignature($details){
        $somme = 0;
        foreach ($details as $p){
            $somme += $p->getNbSignature();
        }
        return $somme;
    }


    /**
     * @param DetailElectionDesk[]
     * @param Desk
     * @return PackVote[]
     */
    public function getPacksVote($details){
        if(!is_array($details)){
            $details =array($details);
        }
        $packs = array();
        foreach ($details as $p){
            $packs= array_merge($packs,$p->getPacksVote());
        }
        return $packs;
    }

    /**
     * @param DetailElectionDesk[]
     * @param Desk
     * @return PackHoraire[]
     */
    public function getPacksHoraire($details){
        if(!is_array($details)){
            $details =array($details);
        }
        $packs = array();
        foreach ($details as $p){
            $packs= array_merge($packs,$p->getPacksHoraire());
        }
        return $packs;
    }


    /**
     * @param DetailElectionDesk[]
     * @param Desk
     * @return PackVote[]
     */
    public function getPackVote($details){
        if(!is_array($details)){
            $details =array($details);
        }
        $packs = array();
        foreach ($details as $p){
            $packs= array_merge($packs,$p->getPacksVote());
        }
        return $packs;
    }


    /**
     * @param DetailElectionDesk[]
     * @param Desk
     * @return boolean
     */
    public function isValid($details){
        $valid = true;
        foreach ($details as $p){
            if($p->isValid()==false){
                $valid=false;
            }
        }
        return $valid;
    }

    /*
     * For convert a DetailElectionDesk in JSON
     */
    public function toJson(DetailElectionDesk $detailElectionDesk){
        return json_encode($this->toArray($detailElectionDesk));
    }

    public function toArray(DetailElectionDesk $detailElectionDesk){
        $resume = array();
        $resume["nameDesk"] = array($detailElectionDesk->getDesk()->getName(),"string");
        $resume["nbRegistred"] = array($detailElectionDesk->getNbRegistered(),"integer");
        $resume["nbVoter"] = array($this->packVoteService->getNbVotes($detailElectionDesk->getPacksVote()),"integer");
        $resume["nbNul"] = array($this->packVoteService->getNbVotes($this->packVoteService->filterByNul($detailElectionDesk->getPacksVote())),"integer");
        $resume["nbValid"] = array($this->packVoteService->getNbVotes($this->packVoteService->filterByValablementExprimes($detailElectionDesk->getPacksVote())),"integer");;
        $participation = array();
        $participationTotal = 0;
        for($hour=0;$hour<25;$hour++){
            $participationHour = $this->packHoraireService->getNbParticipation($this->packHoraireService->filterByHour($detailElectionDesk->getPacksHoraire(),$hour));
            $participation[$hour] = $participationHour;
            $participationTotal+= $participationHour;
        }
        $nbSignature = (0 == $detailElectionDesk->getNbSignature() ) ? 0 : $detailElectionDesk->getNbSignature();
        $resume["participation"] = array(($this->packVoteService->getNbVotes($detailElectionDesk->getPacksVote())/$nbSignature)*100,"%");
        $resume["participationByHour"] = array($participation,"array");
        $this->detailElectionDesk = $detailElectionDesk;
        $candidats = $this->packVoteService->getCandidates($detailElectionDesk->getPacksVote());
        $resume["voteByCandidate"] = array(array_map($candidats,"getVoteByCandidat"),"array");
    }


    private function getVoteByCandidat($candidat){
        return array($candidat,$this->packVoteService->getNbVotes($this->packVoteService->filterByCandidat($this->detailElectionDesk->getPacksVote(),$candidat)));
    }

}