<?php

namespace AppBundle\Controller\utilisation;

use AppBundle\Entity\Desk;
use AppBundle\Entity\Candidat;
use AppBundle\Entity\DetailElectionTownHall;
use AppBundle\Entity\Election;
use AppBundle\Entity\MetaElection;
use AppBundle\Entity\TownHall;
use AppBundle\Service\DetailElectionTownHallService;
use AppBundle\Service\MetaElectionService;
use AppBundle\Service\PackVoteService;
use AppBundle\Service\SecurityService;
use Ob\HighchartsBundle\ObHighchartsBundle;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * VisiteurRController controller.
 *
 */
class VisiteurRController extends Controller
{
    /**
     *Acceuil.
     *
     */
    public function indexAction($msg = "")
    {
        $session = $this->get('session');
        $election = $session->get('election');
        $townHall = $session->get('townHall');
        if(count($election)>0 && count($townHall)>0){
            $metaElectionService = $this->container->get('MetaElectionService');
            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:MetaElection');
            $meta = $repository->find($election);
            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:TownHall');
            $town = $repository->find($townHall);
            if($meta != null and $townHall != null) {//in case where election/townhall id have change
                $electionPotentiel = $metaElectionService->getElectionByTownHall($meta, $town);
                if ($electionPotentiel != null) {
                    return $this->redirectToRoute('visiteur_indexSelection', array('election' => $election, 'townHall' => $townHall));
                }
            }

        }
        $repositoryPackVote = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:TownHall');
        // On récupère l'entité correspondante à l'id $id
        $townHalls = $repositoryPackVote->findAll();
        $repositoryPackVote = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:MetaElection');
        // On récupère l'entité correspondante à l'id $id
        $elections = $repositoryPackVote->findAll();
        return $this->render(':utilisation/Visiteur:index.html.twig',array('townHalls'=>$townHalls,"elections"=>$elections,"msg"=>$msg));
    }

    /**
     * @param TownHall $townHall
     * @param MetaElection $election
     * @ParamConverter("townHall", class="AppBundle:TownHall", options={"townHall", "id"})
     * @ParamConverter("election", class="AppBundle:MetaElection", options={"election", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexSelectionAction(TownHall $townHall,MetaElection $election)
    {

        $session = $this->get('session');

        $session->set('election', $election->getId());
        $session->set('townHall', $townHall->getId());



        $repositoryPackVote = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:TownHall');
        // On récupère l'entité correspondante à l'id $id
        $townHalls = $repositoryPackVote->findAll();
        $repositoryPackVote = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:MetaElection');
        // On récupère l'entité correspondante à l'id $id
        $elections = $repositoryPackVote->findAll();

        $metaElectionService = $this->container->get('MetaElectionService');
        $electionPotentiel = $metaElectionService->getElectionByTownHall($election,$townHall);
        if($electionPotentiel === null){
            return $this->redirectToRoute('visiteur_indexMsg', array("msg"=>"cette election n'est pas possible pour votre mairie"));
        }

        return $this->render(':utilisation/Visiteur:indexSelection.html.twig',array('election'=>$electionPotentiel,'townHall'=>$townHall,'townHalls'=>$townHalls,"elections"=>$elections));
    }


    public function showResultatAction(){
        $repositoryPackVote = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:TownHall');
        // On récupère l'entité correspondante à l'id $id
        $townHalls = $repositoryPackVote->findAll();

        $repositoryPackVote = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Candidat');
        // On récupère l'entité correspondante à l'id $id
        $listeCandidats = $repositoryPackVote->findAll();
        return $this->render(':utilisation/Visiteur:afficherResultatVote.html.twig', array('msg'=>"",'listeCandidats'=>$listeCandidats,"townHalls"=>$townHalls));
    }





    /**
     * @param TownHall $townHall
     * @param Election $election
     * @ParamConverter("townHall", class="AppBundle:TownHall", options={"townHall", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showResultatDetailAction(TownHall $townHall,Election $election,$mode){
        $detailElectionDeskService = $this->container->get('DetailElectionDeskService');
        $detailElectionTownHallService = $this->container->get('DetailElectionTownHallService');
        $packVoteService = $this->container->get('PackVoteService');
        $townHallService = $this->container->get('TownHallService');
        $deskService = $this->container->get('DeskService');
        $electionService = $this->container->get('ElectionService');
        /** @var SecurityService $securityService */
        $securityService = $this->container->get('SecurityService');

        $detailsEM= $election->getDetailsElectionTownHall();

        $detailEMService = $this->container->get('DetailElectionTownHallService');
        $detailsEM = $detailEMService->filterByTownHall($detailsEM,$townHall);
        if(count($detailsEM)<1){
            throw new Exception("votre townHall n'est pas abonnée à cette élection");
        }
        $detailEM = $detailsEM[0];

        if($detailEM->getBackup() !== ""){
            return $this->redirectToRoute('visiteur_showVoteDetailhtml',array("election"=>$election->getId(),"townHall"=>$townHall->getId()));
        }


        if(!$securityService->isGrantedView($this->getUser(),$detailEM)){
            return $this->render(':utilisation/Visiteur:afficherResultatVote.html.twig',array("authorized"=>false));
        }
        $authorized = true;


        $deskx = $townHallService->getDeskx($townHall);
        $deskx = $deskService->filterByBossDesk($deskx);
        $candidats = $election->getCandidats();
        $detailsEB = $electionService->getDetailsElectionDesk($election);
        $detailsEB = $detailElectionDeskService->filterByTownHall($detailsEB,$townHall);
        $detailsEB = $detailElectionDeskService->concatBosssWithSubDesks($detailsEB);
        $totalAttendu = $detailElectionDeskService->getSumNbSignature($detailsEB);
        $votesBis= $detailElectionDeskService->getPackVote($detailsEB);
        $total =  $packVoteService->getNbVotes($votesBis);

        $totalValablementExprime = $packVoteService->getNbVotes($packVoteService->filterByValablementExprimes($votesBis));
        $totalNonValablementExprime = $packVoteService->getNbVotes($packVoteService->filterByNonValablementExprimes($votesBis));
        usort($candidats,function($a, $b) {
            return ($a->getRank()> $b->getRank());
        });
        $candidatesPart1 = array();
        $candidatesPart2 = array();
        $half = round(count($candidats)/2,0);

        for ($i=0;$i<$half;$i++){
            $candidatesPart1[] = $candidats[$i];
        }
        for ($i=$half;$i<count($candidats);$i++){
            $candidatesPart2[] = $candidats[$i];
        }

        $candidatesLeaders = $packVoteService->getCandidatesSortedByVotes($packVoteService->filterByValablementExprimes($votesBis));


        $leader2 = (count($candidatesLeaders)>1) ? $candidatesLeaders[1] : null;
        foreach ($candidats as $c){
            $c->setNbVotes($packVoteService->getNbVotes($packVoteService->filterByCandidat($detailElectionDeskService->getPackVote($detailsEB),$c)));
        }
        return $this->render(':utilisation/Visiteur:afficherResultatVote.html.twig', array("authorized"=>$authorized,"leader2"=>$leader2,"candidatesLeaders"=>$candidatesLeaders,"detailElectionDeskService"=>$detailElectionDeskService,"candidatesPart1"=>$candidatesPart1,"candidatesPart2"=>$candidatesPart2,'totalNonValablementExprime'=>$totalNonValablementExprime,'totalValablementExprime'=>$totalValablementExprime,'detailEM'=>$detailEM,'total'=>$total,"totalAttendu"=>$totalAttendu,'deskx'=>$deskx,"candidats"=>$candidats,"votes"=>$votesBis,"townHall"=>$townHall,"election"=>$election,'mode'=>$mode));

    }


    function inArray($array,$item){
        foreach ($array as $i){
            if($i=== $item){
                return true;
            }
        }
        return false;
    }


    /*
     * Permet de voir la participation
     */
    /**
     * @param TownHall $townHall
     * @param Election $election
     * @ParamConverter("townHall", class="AppBundle:TownHall", options={"townHall", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showParticipationVoteAction(TownHall $townHall,Election $election,$mode){
        $election->filterByTownHall($townHall);
        $detailElectionDeskService = $this->container->get('DetailElectionDeskService');
        $detailElectionTownHallService = $this->container->get('DetailElectionTownHallService');
        $packVoteService = $this->container->get('PackVoteService');
        $townHallService = $this->container->get('TownHallService');
        $deskService = $this->container->get('DeskService');
        $electionService = $this->container->get('ElectionService');

        $detailsEM= $election->getDetailsElectionTownHall();

        $detailEMService = $this->container->get('DetailElectionTownHallService');
        $detailsEM = $detailEMService->filterByTownHall($detailsEM,$townHall);



        if(count($detailsEM)<1){
            throw new Exception("votre townHall n'est pas abonnée à cette élection");
        }

        $detailEM = $detailsEM[0];

        if($detailEM->getBackup() !== ""){
            return $this->redirectToRoute('visiteur_showVoteDetailhtml',array("election"=>$election->getId(),"townHall"=>$townHall->getId()));
        }
        $packHoraireService = $this->container->get('PackHoraireService');
        $townHallService = $this->container->get('TownHallService');
        $electionService = $this->container->get('ElectionService');


        $deskx = $townHallService->getDeskx($townHall);
        $packsHoraire = $electionService->getPacksHoraire($election);
        if($mode==2) {
            foreach ($deskx as $b) {
                for ($i = 0; $i < 24; $i++) {
                    $b->setData($b->getData() . "," . $packHoraireService->getParticipationByDeskByHour($packsHoraire, $b, $i));
                }
            }
        }
        if($mode==1) {
            $deskTotal = new Desk();
            $deskTotal->setNom("Somme des deskx");
            for ($i = 0; $i < 24; $i++) {
                $sommeParHeure = 0;
                foreach ($deskx as $b) {
                    $sommeParHeure+=$packHoraireService->getParticipationByDeskByHour($packsHoraire, $b, $i);
                }
                $deskTotal->setData($deskTotal->getData().",".$sommeParHeure);
            }
            $deskx = array($deskTotal);

        }

        return $this->render(':utilisation/Visiteur:voirParticipation.html.twig', array(
            'deskx' => $deskx,'msg'=>"","townHall"=>$townHall,"election"=>$election
        ));
    }


    public function getPdfShowResultatDetail(){

    }

public function deniedAction(){
    return $this->render('AppBundle:Visiteur:denied.html.twig');
}



}
