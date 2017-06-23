<?php

namespace AppBundle\Controller\utilisation;

use AppBundle\Entity\Candidat;
use AppBundle\Entity\Detail;
use AppBundle\Entity\DetailElectionDesk;
use AppBundle\Entity\DetailElectionTownHall;
use AppBundle\Entity\Election;
use AppBundle\Entity\PackVote;
use AppBundle\Service\DetailElectionDeskService;
use AppBundle\Service\PackVoteService;
use AppBundle\Service\SecurityService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Desk;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Packvote controller.
 *
 */
class PackVoteController extends Controller
{



    /**
     * @param DetailElectionDesk $detailElectionDesk
     * @throws
     * @return boolean
     * test if you can use a detailElectionDesk
     *
     */
    protected function testGrantedView(DetailElectionDesk $detailElectionDesk){
        /** @var SecurityService $object */
        $securityService = $this->get('SecurityService');
        if($securityService->isGrantedView($this->getUser(),$detailElectionDesk)){
            return true;
        }
        else{
            throw new AccessDeniedHttpException("Ce bureau n'a pas/plus le droit de voter pour cette élection");
        }
    }



    /**
     * @param Desk $desk
     * @ParamConverter("desk", class="AppBundle:Desk", options={"desk", "id"})
     * @param Candidat $candidat
     * @ParamConverter("candidat", class="AppBundle:Candidat", options={"candidat", "id"})
     * @param $number
     * @return JsonResponse
     * @throws \Exception
     *Allows to directly enter a number of votes for a candidate
     */
    public function voteDirectAction(Desk $desk, Candidat $candidat,$number)
    {
        /** @var SecurityService $securityService */
        $securityService = $this->container->get('SecurityService');

        /** @var PackVoteService $packVoteService */
        $packVoteService = $this->container->get('PackVoteService');

        /** @var DetailElectionDeskService $detailElectionDeskService */
        $detailElectionDeskService = $this->container->get('DetailElectionDeskService');

        $election = $candidat->getElection();
        $detailElectionDesk = $detailElectionDeskService->getDetailElectionDesk($desk,$election);

        if(!$securityService->isGrantedUse($this->getUser(),$detailElectionDesk)){
            throw new AccessDeniedHttpException("Pas le bon bureau ou l'election est fini".$detailElectionDesk->getState());
        }

        $number+=0;
        if (!is_int($number)) {
            throw new Exception("Un nombre est demandé");
        }

        $detail = $detailElectionDesk;
        //$detail->setPacksVote(new ArrayCollection());
        $packVote = $packVoteService->filterByCandidat($detail->getPacksVote(), $candidat);
        $em = $this->get('doctrine')->getManager();
        if (count($packVote) == 0) {
            $packVote = new PackVote($detail,$candidat);
            $detail->addPackVote($packVote);
            $packVote->setNbVotes($number);
            $em->persist($packVote);
            $em->persist($detail);
            $em->flush();

        } elseif (count($packVote) == 1) {
            $packVote = $packVote[0];
            $packVote->setNbVotes($packVote->getNbVotes() +$number);
        }
        elseif (count($packVote) >1){
            throw new \Exception("erreur interne, trop de packVote");
        }
        $em->persist($packVote);
        $em->persist($detail);
        $em->flush();

        $candidats = $election->getCandidats();

        $rep = array();
        $somme=0;
        foreach ($candidats as $candidat) {
            $nb =$packVoteService->getNbVotesByCandidat($detail->getpacksVote(), $candidat);
            $rep[] = array("balise"=>"nbVoteCandidat".$candidat->getId(),"id" => $candidat->getId(), "nom" => $candidat->getNom(), "nbVote" =>$nb);
            $somme+=$nb;
        }
        $rep[] = array("balise"=>"nbVoteTotal","id" => -1, "nom" => "total", "nbVote" =>$somme);
        return new JsonResponse($rep);
    }






    /**
     * @param Desk $desk
     * @ParamConverter("desk", class="AppBundle:Desk", options={"desk", "id"})
     * @param Candidat $candidat
     * @ParamConverter("candidat", class="AppBundle:Candidat", options={"candidat", "id"})
     * @return JsonResponse
     * @throws \Exception
     */
    public function getNbVotesInDeskForCandidateAction(Desk $desk, Candidat $candidat){
        /** @var SecurityService $securityService */
        $securityService = $this->container->get('SecurityService');

        /** @var PackVoteService $packVoteService */
        $packVoteService = $this->container->get('PackVoteService');

        /** @var DetailElectionDeskService $detailElectionDeskService */
        $detailElectionDeskService = $this->container->get('DetailElectionDeskService');

        $election = $candidat->getElection();
        $detailElectionDesk = $detailElectionDeskService->getDetailElectionDesk($desk,$election);


        $rep = 0;
        $detail = $detailElectionDesk;
        if(!$securityService->isGrantedView($this->getUser(),$detailElectionDesk)){
            throw new AccessDeniedHttpException("Pas le bon bureau ou l'election est fini".$detailElectionDesk->getState());
        }


        $packVote = $packVoteService->filterByCandidat($detail->getPacksVote(), $candidat);
        $em = $this->get('doctrine')->getManager();
        if (count($packVote) == 0) {
            if($securityService->isGrantedUse($this->getUser(),$detailElectionDesk)){
                $packVote = new PackVote($detail,$candidat);
                $detail->addPackVote($packVote);
                $packVote->setNbVotes(0);
                $em->persist($packVote);
                $em->persist($detail);
                $em->flush();
            }
            $rep =  0;

        } elseif (count($packVote) == 1) {
            $packVote = $packVote[0];
            $rep = $packVote->getNbVotes();
        }
        return new JsonResponse(array("nbVote"=>$rep));
    }



    /**
     * @param Desk $desk
     * @ParamConverter("desk", class="AppBundle:Desk", options={"desk", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @param Election $election
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function remiseZeroPackVoteAction(Desk $desk,Election $election){



        /** @var DetailElectionDeskService $detailElectionDeskService */
        $detailElectionDeskService = $this->container->get('DetailElectionDeskService');
        $detailEB = $detailElectionDeskService->getDetailElectionDesk($desk,$election);

        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedUse($this->getUser(),$detailEB)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas le droit d'editer ce candidat");
        }

        $detailEB->setPacksVote(new ArrayCollection());
        $detailEB->setPacksHundred(new ArrayCollection());
        $detailEB->setPacksHoraire(new ArrayCollection());
        $em = $this->get('doctrine')->getManager();
        $em->persist($detailEB);
        $em->flush();

        return $this->redirectToRoute('packvote_new_election',array("desk"=>$desk->getId(),"election"=>$election->getId(),"mode"=>1));

    }

    /**
     * @param Desk $desk
     * @ParamConverter("desk", class="AppBundle:Desk", options={"desk", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})

     * @param Election $election
     * @return \Symfony\Component\HttpFoundation\Response
     *
        */
    public function newElectionsAction(Desk $desk,Election $election,$mode)
    {


    /** @var DetailElectionDeskService $detailElectionDeskService */
    $detailElectionDeskService = $this->container->get('DetailElectionDeskService');

    /** @var PackVoteService $packVoteService */
        $packVoteService = $this->container->get('PackVoteService');

    $detailEB = $detailElectionDeskService->getDetailElectionDesk($desk,$election);

    /** @var SecurityService $service */
    $service = $this->container->get('SecurityService');

    $this->testGrantedView($detailEB);
    $listeCandidat = $election->getCandidats();
        usort($listeCandidat,function($a, $b) {
            return ($a->getRank()> $b->getRank());
        });

    $votes = $detailEB->getPacksVote();
    if(count($votes)==0){
    $nbVoteTotal =0;
    }

    else {
    $nbVoteTotal = $packVoteService->getNbVotesbyDesk($votes,$desk);
    }
    $detail =$detailEB;
    foreach ($listeCandidat as $c){
    $nb=  $packVoteService->getNbVotesbyCandidat($detailEB->getPacksVote(),$c);
    $c->setData($nb);//<==> $c->setnbVotes for a election and a desk
    }
    return $this->render(':gestion/packvote:new.html.twig', array('msg'=>"",'detail'=>$detail,'election' => $election,'nbVoteTotal'=>$nbVoteTotal,'listeCandidat' => $listeCandidat,'desk' => $desk,"mode"=>$mode));

    }


}