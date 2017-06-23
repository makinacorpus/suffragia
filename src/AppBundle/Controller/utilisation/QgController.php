<?php

namespace AppBundle\Controller\utilisation;

use AppBundle\Entity\Desk;
use AppBundle\Entity\DetailElectionTownHall;
use AppBundle\Entity\Election;
use AppBundle\Entity\Qg;
use AppBundle\Service\DetailElectionTownHallService;
use AppBundle\Service\SecurityService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * QgController controller.
 *
 */
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class QgController extends Controller
{


    /**
     * @param DetailElectionTownHall $detailElectionTownHall
     * @return bool
     *
     */
    protected function testGrantedUse(DetailElectionTownHall $detailElectionTownHall){
        /** @var SecurityService $object */
        $securityService = $this->get('SecurityService');
        if($securityService->isGrantedUse($this->getUser(),$detailElectionTownHall)){
            return true;
        }
        else{
            throw new AccessDeniedHttpException("vous n'avez pas le droit d'agir pour ce bureau");
        }
    }


    /**
     * @param Qg $qg
     * @ParamConverter("qg", class="AppBundle:Qg", options={"qg", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @ParamConverter("desk", class="AppBundle:Desk", options={"desk", "id"}, isOptional="true")
     * @return \Symfony\Component\HttpFoundation\Response
     * Allows to complete the votes of the different offices
     */
    public function validVoteAction(Qg $qg,Election $election,Desk $desk = null,$mode=0)
    {


        /** @var DetailElectionTownHallService $detailElectionTownHallService */

        $detailElectionTownHallService = $this->container->get('DetailElectionTownHallService');
        $detailElectionTownHall = $detailElectionTownHallService->getDetailElectionTownHall($qg->getTownHall(),$election);
        /** @var SecurityService $object */
        $securityService = $this->get('SecurityService');
        if(!$securityService->isGrantedView($this->getUser(),$detailElectionTownHall)){
            throw new AccessDeniedHttpException("vous n'avez pas le droit d'agir pour ce qg pour cette election");
        }



        $packVoteService = $this->container->get('PackVoteService');
        $elections = $detailElectionTownHallService->getElections($qg->getTownHall()->getDetailsElectionTownHall());
        $electionService = $this->container->get('ElectionService');
        $detailElectionDeskService = $this->container->get('DetailElectionDeskService');

        $nbVotesTotal=null;


        $details = $electionService->getDetailsElectionDesk($election);
        $detail = ($detailElectionDeskService->filterByDesk($details,$desk)[0]);
        $packVotes = $detailElectionDeskService->getPacksVote($detail);
        $nbVotesTotal = $packVoteService->getNbVotes($packVotes);

        $candidats = $election->getCandidats();
        foreach ($candidats as $candidat){
            $nb=  $packVoteService->getNbVotesbyCandidat($detail->getPacksVote(),$candidat);
            $candidat->setData($nb);//<==> $c->setnbVotes for a election and a desk
            $candidat->setNbVotes($nb);
        }
        return $this->render(':utilisation/Qg:detailFinElection.html.twig', array("candidats"=>$candidats,'nbVotesTotal'=>$nbVotesTotal,'mode'=>$mode,"detail"=>$detail,"election"=>$election,"desk"=>$desk,'qg'=>$qg,'elections' => $elections,'msg'=>""));
    }





}
