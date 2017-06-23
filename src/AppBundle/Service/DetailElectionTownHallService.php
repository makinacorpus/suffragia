<?php



namespace AppBundle\Service;

use AppBundle\Entity\Concrete;
use AppBundle\Entity\Desk;
use AppBundle\Entity\Candidat;
use AppBundle\Entity\DetailElectionDesk;
use AppBundle\Entity\DetailElectionTownHall;
use AppBundle\Entity\Election;
use AppBundle\Entity\Entity;
use AppBundle\Entity\TownHall;
use AppBundle\Entity\PackHoraire;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class DetailElectionTownHallService
{

    /**
     * ElectionService constructor.
     */
    public function __construct(EntityManager $em,ElectionService $electionService,PackVoteService $packVoteService,PackHoraireService $packHoraireService,DetailElectionDeskService $detailElectionDeskService,TownHallService $townHallService,CandidatService $candidatService)
    {
        $this->em = $em;
        $this->electionService = $electionService;
        $this->packVoteService = $packVoteService;
        $this->packHoraireService = $packHoraireService;
        $this->detailElectionDeskService = $detailElectionDeskService;
        $this->townHallService = $townHallService;
        $this->candidatService = $candidatService;
    }


    public function getDetailElectionTownHall(TownHall $townHall,Election $election){
        foreach ($election->getDetailsElectionTownHall() as $detailElectionTownHall){
            if($detailElectionTownHall->getTownHall() === $townHall){
                return $detailElectionTownHall;
            }
        }
        throw new \Exception("cette mairie n'est pas abonné a cette election");
    }




    /**
     * @param DetailElectionTownHall[]
     * @return Election[]
     */
    public function getElections($detailsElectionTownHall){
        $elections = array();
        foreach ($detailsElectionTownHall as $detailElectionTownHall){
            $elections[] = $detailElectionTownHall->getElection();
        }
       return $elections;
    }

    /**
     * @param DetailElectionTownHall[]
     * @return DetailElectionTownHall[]
     */
    public function filterByElections($detailsElectionTownHall,$election){
        $details = array();
        foreach ($detailsElectionTownHall as $detailElectionTownHall){
            if($detailElectionTownHall->getElection()===$election){
                $details[] = $detailElectionTownHall;
            }
        }
        return $details;
    }
    /**
     * @param DetailElectionTownHall[]
     * @return DetailElectionTownHall[]
     */
    public function filterByTownHall($detailsElectionTownHall,$townHall){
        $details = array();
        foreach ($detailsElectionTownHall as $detailElectionTownHall){
            if($detailElectionTownHall->getTownHall()===$townHall){
                $details[] = $detailElectionTownHall;
            }
        }
        return $details;
    }

    function getNameConcrete(Concrete $concrete ){
        return $concrete->getName();
    }

    private function getNbInscrit(DetailElectionDesk $detailElectionDesk){
        return $detailElectionDesk->getNbRegistered();
    }

    private function getNbSignature(DetailElectionDesk $detailElectionDesk){
        return $detailElectionDesk->getNbSignature();
    }

    private function getBulletinsBlanc(DetailElectionDesk $detailElectionDesk){
        $service = $this->packVoteService;
        return $service->getNbVotes($service->filterByBlanc($detailElectionDesk->getPacksVote()));
    }
    private function getBulletinsNul(DetailElectionDesk $detailElectionDesk){
        $service = $this->packVoteService;
        return $service->getNbVotes($service->filterByNul($detailElectionDesk->getPacksVote()));
    }
    private function getBulletinValid(DetailElectionDesk $detailElectionDesk){
        $service = $this->packVoteService;
        return $service->getNbVotes($service->filterByValablementExprimes($detailElectionDesk->getPacksVote()));
    }
    private function getPerticipation(DetailElectionDesk $detailElectionDesk){
        $service = $this->packHoraireService;
        return $service->getNbParticipation($detailElectionDesk->getPacksHoraire());
    }

    private function getVotes(DetailElectionDesk $detailElectionDesk){
        $service = $this->packVoteService;
        return $service->getNbVotes($service->filterByCandidat($detailElectionDesk->getPacksVote(),$this->candidate));
    }
    private function getVotesByDesk(DetailElectionDesk $detailElectionDesk){
        $service = $this->packVoteService;
        return $service->getNbVotes($detailElectionDesk->getPacksVote());
    }

    private function getPerticipationByHour(Election $election){
        $service = $this->packHoraireService;
        $electionService = $this->electionService;

        return $service->getNbParticipation($service->filterByHour($electionService->getPacksHoraire($election),$this->hour));
    }


    public function getJsonBrut(DetailElectionTownHall $detailElectionTownHall){
        $townHall = $detailElectionTownHall->getTownHall();

        $election = $detailElectionTownHall->getElection();
        $detailElectionDeskService = $this->detailElectionDeskService;
        $candidatService = $this->candidatService;
        $townHallService = $this->townHallService;
        $detailElectionTownHallService = $this;
        $packVoteService = $this->packVoteService;
        $electionService = $this->electionService;
        $packHoraireService = $this->packHoraireService;

        $resume = array();
        $candidats = array();
        $packVotes = $electionService->getPacksVote($election);

        $totalValidlyExpressed = $packVoteService->getNbVotes($packVoteService->filterByValablementExprimes($packVotes));
        $totalNul =  $packVoteService->getNbVotes($packVoteService->filterByNul($packVotes));
        $totalBlanc =  $packVoteService->getNbVotes($packVoteService->filterByBlanc($packVotes));
        $signatureNumber = $detailElectionDeskService->getSumNbSignature($detailElectionTownHall->getDetailsElectionDesk());
        $totalVote = $totalValidlyExpressed+$totalBlanc+$totalNul;
        if($signatureNumber==0){
            $advancement = "Nombre de signature non rensegné";
        }
        else{
            $advancement=($totalVote/$signatureNumber)*100;
        }

        $candidatsValide = $candidatService->filterByCandidatesValid($election->getCandidats());

        foreach ($candidatsValide as $c){
            $c->setNbVotes($packVoteService->getNbVotes($packVoteService->filterByValablementExprimes($packVoteService->filterByCandidat($packVotes,$c))));
            if($totalValidlyExpressed==0){
                $c->setData(0);
            }
            else{
                $c->setData(round(($c->getNbVotes()/$totalValidlyExpressed)*100,1));
            }
        }

        foreach ($candidatsValide as $candidat){
            $candidats[$candidat->getId()] = array($candidat->getName(),$candidat->getParti()->getName(),$candidat->getParti()->getColor(),$candidat->getNbVotes(),$candidat->getData(),$candidat->getId());
        }
        $desks = array();


        foreach ($townHallService->getDeskx($townHall) as $desk){

            $votesCandidates = array();
            foreach ($candidatService->filterByCandidatesValid($election->getCandidats()) as $c){
                $packs = $packVoteService->filterByDesk($packVotes,$desk);
                $packs = $packVoteService->filterByCandidat($packs,$c);
                $nbVotes = $packVoteService->getNbVotes($packs);
                $votesCandidates[$c->getId()] = $nbVotes;
            }

            $detailDesk = array($desk->getName(),$desk->getDeskNumber(),$detailElectionDeskService->filterByDesk($detailElectionTownHall->getDetailsElectionDesk(),$desk)[0]->getState());

            $desks[$desk->getDeskNumber()] = array("detailDesk"=>$detailDesk,"detailCandidates"=>$votesCandidates);
        }

        $resume[] = $candidats;
        $resume[] = $desks;
        $divers = array("townHall"=>$townHall->getName(),"election"=>$election->getMetaElection()->getName(),"date"=>$election->getMetaElection()->getDate()->format("Y-m-d"),"type"=>$election->getMetaElection()->getType());
        $divers["totalValidlyExpressed"] = $totalValidlyExpressed;
        $divers["totalNul"] = $totalNul;
        $divers["totalBlanc"] = $totalBlanc;
        if($totalNul+$totalBlanc != 0){
            $tmp = $totalNul+$totalBlanc;
            $divers["totalNulPercentage"] = ($totalNul/$tmp)*100;
            $divers["totalBlancPercentage"] = ($totalBlanc/$tmp)*100;
        }
        else{
            $divers["totalNulPercentage"] = 0;
            $divers["totalBlancPercentage"] =0;
        }



        $divers["signatureNumber"] = $signatureNumber;
        $divers["totalVote"] = $totalVote;
        $divers["advancement"] = round($advancement,0)."%";
        $divers["stateElection"] = $detailElectionTownHall->getState();
        $candidatesLeaders = $packVoteService->getCandidatesSortedByVotes($packVoteService->filterByValablementExprimes($packVotes));


        if(count($candidatesLeaders)>0){
            $a=($packVoteService->getNbVotes($packVoteService->filterByCandidat($packVotes,$candidatesLeaders[0])));
            $b=($packVoteService->getNbVotes($packVotes));
            if($a ===0){
                $divers["leader"] = "inconnu";
            }
            else{
                $divers["leader"] =$candidatesLeaders[0]->getName().", <strong>".round(($a/$b)*100,0)."</strong>%";
            }
        }
        else{
            $divers["leader"] = "inconnu";
        }
        if(count($candidatesLeaders)>1){
            $a=($packVoteService->getNbVotes($packVoteService->filterByCandidat($packVotes,$candidatesLeaders[1])));
            $b=($packVoteService->getNbVotes($packVotes));
            if($a===0){
                $divers["leader"] = "inconnu";
            }
            else{
                $divers["leader2"] =$candidatesLeaders[1]->getName().", <strong>".round(($a/$b)*100,0)."</strong>%";
            }
        }
        else{
            $divers["leader2"] = "inconnu";
        }

        $divers["stateElection"] = $detailElectionTownHall->getState();
        $resume[]= $divers;

        return new JsonResponse($resume);
    }


    public function getRaw(DetailElectionTownHall $detailElectionTownHall){
        $townHall = $detailElectionTownHall->getTownHall();

        $election = $detailElectionTownHall->getElection();
        $detailElectionDeskService = $this->detailElectionDeskService;
        $detailElectionTownHallService = $this;
        $packVoteService = $this->packVoteService;
        $electionService = $this->electionService;
        $packHoraireService = $this->packHoraireService;
        $resume = array();$detailselectionBureau = $detailElectionTownHall->getDetailsElectionDesk();$desks= $detailElectionDeskService->getDesk($detailselectionBureau);

        $head = array($election->getType());
        $head[] = "Nom des bureaux";
        $body = array_map(array($this, 'getNameConcrete'),$desks);
        $head = array_merge($head,$body);
        $head[] = "total";
        $resume[] = $head;

        $head = array($election->getName());
        $head[] ="Nb inscrits";
        $body = array_map(array($this, 'getNbInscrit'),$detailselectionBureau);
        $head = array_merge($head,$body);
        $head[] = array_sum($body);
        $resume[] = $head;

        $head = array($election->getDate()->format("D-M-Y"));
        $head[] ="Nb de signatures";
        $body = array_map(array($this, 'getNbSignature'),$detailselectionBureau);
        $head = array_merge($head,$body);
        $head[] = array_sum($body);
        $resume[] = $head;

        $head = array($detailElectionTownHall->getTownHall()->getName());
        $head[] ="Bulletin blanc";
        $body = array_map(array($this, 'getBulletinsBlanc'),$detailselectionBureau);
        $head = array_merge($head,$body);
        $head[] = array_sum($body);
        $resume[] = $head;

        $head = array("");
        $head[] ="Bulletins et enveloppes annules";
        $body = array_map(array($this, 'getBulletinsNul'),$detailselectionBureau);
        $head = array_merge($head,$body);
        $head[] = array_sum($body);
        $resume[] = $head;

        $head = array("");
        $head[] ="Bulletins et enveloppes annules";
        $body = array_map(array($this, 'getBulletinValid'),$detailselectionBureau);
        $head = array_merge($head,$body);
        $head[] = array_sum($body);
        $resume[] = $head;

        $head = array("");
        $head[] ="Participation";
        $body = array_map(array($this, 'getPerticipation'),$detailselectionBureau);
        $head = array_merge($head,$body);
        if(count($body) != 0){
            $head[] = strval(array_sum($body)/count($body))."%";
        }
        else{
            $head[] = "na";
        }
        $resume[] = $head;

        $resume[] = array("","","","");

        $head = array("Listes/ Partis");
        $head[] ="Binomes de candidats /candidat";
        $body = array_map(array($this, 'getNameConcrete'),$desks);
        $head = array_merge($head,$body);
        $head[] = "Total";
        $head[] = "%";
        $resume[] = $head;

        foreach ($election->getCandidats() as $c){
            $head = array($c->getParti());
            $head[] = $c->getName();
            $this->candidate = $c;
            $body = array_map(array($this, 'getVotes'),$detailselectionBureau);
            $head = array_merge($head,$body);
            $head[] = array_sum($body);
            $valable = $packVoteService->getNbVotes($packVoteService->filterByValablementExprimes($electionService->getPacksVote($election)));
            if($valable !=0 and !$c->isBlanc() and !$c->isNul()){
                $head[] = strval((array_sum($body)/$valable)*100)."%";
            }
            else
                $head[] = "na";
            $resume[] = $head;
        }

        $head = array("");
        $head[] ="Total";
        $body = array_map(array($this, 'getVotesByDesk'),$detailselectionBureau);
        $head = array_merge($head,$body);
        $head[] = array_sum($body);
        $head[] = "";
        $resume[] = $head;

        $resume[] = array("","","","");


        $head = array("Heure");
        $head[] ="Participation globale";
        $head[] ="Nb emargements";
        $body = array_map(array($this, 'getNameConcrete'),$desks);
        $head = array_merge($head,$body);
        $resume[] = $head;

        $nbEmargementTotal = $packHoraireService->getNbParticipation($electionService->getPacksHoraire($election));
        for($i = 7;$i<20;$i++){
            $this->hour = $i;
            $head = array($i);
            $nbEmargement =  strval($this->getPerticipationByHour($election));
            if($nbEmargementTotal !=0){
                $head[] =strval(($nbEmargement/$nbEmargementTotal)*100)."%";
            }
            else{
                $head[] ="na";

            }
            $head[] = $nbEmargement;
            $tmp = array();
            foreach ($desks as $desk){
                $tmp[] = $packHoraireService->getParticipationByDeskByHour($electionService->getPacksHoraire($election),$desk,$i);
            }
            $head = array_merge($head,$tmp);
            $resume[] = $head;
        }
        $head = array("  ","total",$packHoraireService->getNbParticipation($electionService->getPacksHoraire($election)));
        $resume[] = $head;
        return $resume;
    }




}