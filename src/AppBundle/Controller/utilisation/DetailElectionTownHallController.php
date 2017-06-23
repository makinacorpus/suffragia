<?php

namespace AppBundle\Controller\utilisation;
use AppBundle\Entity\Detail;
use AppBundle\Entity\DetailElectionDesk;
use AppBundle\Entity\DetailElectionTownHall;
use AppBundle\Entity\TownHall;
use AppBundle\Service\DetailElectionTownHallService;
use AppBundle\Service\SecurityService;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Desk;
use AppBundle\Entity\Candidat;
use AppBundle\Entity\Election;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\PackVote;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
/**
 * DeskController controller.
 *
 */
class DetailElectionTownHallController extends Controller
{




    /**
     * @param TownHall $townHall
     * @param Election $election
     * @return DetailElectionTownHall
     * get a DetailElectionTownHall with a townHall and a election
     */
    protected function getDetailElectionTownHall(TownHall $townHall,Election $election){
        $detailElectionTownHallService = $this->container->get('DetailElectionTownHallService');
        $detailsElectionTownHall = $election->getDetailsElectionTownHall();
        $detailsElectionTownHall = $detailElectionTownHallService->filterByTownHall($detailsElectionTownHall,$townHall);
        if(count($detailsElectionTownHall)!=1){
            throw new Exception("erreur, la mairie n'est pas abonnée à cette election");
        }
        $detailElectionTownHall = $detailsElectionTownHall[0];
        return $detailElectionTownHall;
    }


    /**
     * @param DetailElectionTownHall $detailElectionTownHall
     * @return bool
     * @throws \Exception
     * test if you can use a detailElectionTownHall

     */
    protected function testGrantedUse(DetailElectionTownHall $detailElectionTownHall){
        /** @var SecurityService $object */
        $securityService = $this->get('SecurityService');
        if($securityService->isGrantedUse($this->getUser(),$detailElectionTownHall)){
            return true;
        }
        else{
            throw new \Exception("Ce bureau n'a pas/plus le droit de voter pour cette élection");
        }
    }


    /**
     * @param DetailElectionTownHall $detailElectionTownHall
     * @return bool
     * @throws \Exception
     * test if you can see a detailElectionTownHall
     */
    protected function testGrantedView(DetailElectionTownHall $detailElectionTownHall){
        /** @var SecurityService $object */
        $securityService = $this->get('SecurityService');

        if($securityService->isGrantedView($this->getUser(),$detailElectionTownHall)){
            return true;
        }
        else{
            throw new \Exception("Vous n'avez pas le droit de voir le detail de cette mairie");
        }
    }

    /**
     * @param TownHall $townHall
     * @param Election $election
     * @param Integer $state
     * @ParamConverter("townHall", class="AppBundle:TownHall", options={"desk", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     *set a state for a detailElectionTownHall
     */

    public function newStateAction(TownHall $townHall,Election $election,$state){
        /** @var DetailElectionTownHallService $detailElectionTownHallService */
        $detailElectionTownHallService = $this->container->get('DetailElectionTownHallService');
        $detailElectionTownHall = $this->getDetailElectionTownHall($townHall,$election);
        $this->testGrantedUse($detailElectionTownHall);

        $newState = DetailElectionTownHall::getAvailableTypes()[$state];

        if($newState == DetailElectionTownHall::STATE_FINICHED){
            $json = json_encode($detailElectionTownHallService->getRaw($detailElectionTownHall));
            $detailElectionTownHall->setBackup($json);
        }
        else{
            $detailElectionTownHall->setBackup("");

        }
        $detailElectionTownHall->setState(DetailElectionTownHall::getAvailableTypes()[$state]);
        $em = $this->getDoctrine()->getManager();

        $em->persist($detailElectionTownHall);
        $em->flush();
        $rep = array("state"=>DetailElectionTownHall::getAvailableTypes()[$state]);
        return new JsonResponse($rep);
    }





    /**
     * @param TownHall $townHall
     * @param Election $election
     * @ParamConverter("townHall", class="AppBundle:TownHall", options={"desk", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     *set a state for a detailElectionTownHall
     */
    public function newStateFormAction(TownHall $townHall,Election $election)
    {

        $detailElectionTownHall = $this->getDetailElectionTownHall($townHall,$election);
        $this->testGrantedView($detailElectionTownHall);
    return $this->render(':utilisation/Qg:manageEM.html.twig', array('detail'=>$detailElectionTownHall,'election'=>$election,'townHall'=>$townHall,'msg'=>""));
    }




}
