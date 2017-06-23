<?php

namespace AppBundle\Controller\utilisation;
use AppBundle\Entity\DetailElectionDesk;
use AppBundle\Entity\DetailElectionTownHall;
use AppBundle\Service\DetailElectionDeskService;
use AppBundle\Service\SecurityService;
use Symfony\Component\Config\Definition\Exception\Exception;
use AppBundle\Entity\Desk;
use AppBundle\Entity\Election;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * DetailElectionDeskController controller.
 *
 */
class DetailElectionDeskController extends Controller
{
    /**
     * DetailElectionDeskController constructor.
     */
    public function __construct()
    {
    }



    /**
     * @param DetailElectionDesk $detailElectionDesk
     * @throws
     * @return boolean
     * test if you can use a detailElectionDesk
     *
     */
    protected function testGrantedUse(DetailElectionDesk $detailElectionDesk){
        /** @var SecurityService $object */
        $securityService = $this->get('SecurityService');
        if($securityService->isGrantedUse($this->getUser(),$detailElectionDesk)){
            return true;
        }
        else{
            throw new AccessDeniedHttpException("Ce bureau n'a pas/plus le droit de voter pour cette élection");
        }
    }




    /**
     * @param DetailElectionDesk $detailElectionDesk
     * @throws
     * @return boolean
     * test if you can see a detailElectionDesk
     */
    protected function testGrantedView(DetailElectionDesk $detailElectionDesk){
        /** @var SecurityService $object */
        $securityService = $this->get('SecurityService');
        if($securityService->isGrantedView($this->getUser(),$detailElectionDesk)){
            return true;
        }
        else{
            throw new AccessDeniedHttpException("Vous n'avez pas le droit de voir le detail de ce bureau ! ");
        }
    }

    private function getDetailElectionDesk(Desk $desk,Election $election){
        /** @var DetailElectionDeskService $object */
        $detailElectionDeskService = $this->get('DetailElectionDeskService');
        $detail = $detailElectionDeskService->getDetailElectionDesk($desk,$election);
        $this->testGrantedView($detail);


        return $detail;
    }

    /**
     * @param Desk $desk
     * @param Election $election
     * @param Integer $nbSignature
     * @ParamConverter("desk", class="AppBundle:Desk", options={"desk", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     *
     *Allows you to enter the number of signatures during an election
     */
    public function newNbSignatureAction(Desk $desk,Election $election,$nbsignature){
        /** @var DetailElectionDeskService $object */
        $detailElectionDeskService = $this->get('DetailElectionDeskService');
        $detailElectionDesk = $detailElectionDeskService->getDetailElectionDesk($desk,$election);
        $this->testGrantedUse($detailElectionDesk);
        $detailElectionDesk->setNbSignature($nbsignature);
        $em = $this->getDoctrine()->getManager();
        $em->persist($detailElectionDesk);
        $em->flush();
        return new JsonResponse(array("choix_nbSignature"=>$nbsignature,"alert"=>"nombre de signature enregistrés"));

    }


    /**
     * @param DetailElectionDesk $detailEb
     * @ParamConverter("detailEB", class="AppBundle:DetailElectionDesk", options={"detailEB", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     * View the status of the current pending package
     */
    public function getJsonPackHundredAction(DetailElectionDesk $detailEB,$index= 0 ){
        $detailElectionDesk = $detailEB;
        $this->testGrantedView($detailElectionDesk);
        if($index > count($detailElectionDesk->getPacksHundred())){
            $index = count($detailElectionDesk->getPacksHundred());
        }
        return new JsonResponse($detailElectionDesk->getLastPacksHundred());
    }




    /**
     * @param Desk $desk
     * @param Election $election
     * @param Integer $nbinscrit
     * @ParamConverter("desk", class="AppBundle:Desk", options={"desk", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     *Allows you to enter the number of persons registered during an election
     */
    public function newNbInscritAction(Desk $desk,Election $election,$nbinscrit){
        $detailElectionDesk = $this->getDetailElectionDesk($desk,$election);
        $this->testGrantedUse($detailElectionDesk);
        $detailElectionDesk->setNbRegistered($nbinscrit);
        $em = $this->getDoctrine()->getManager();
        $em->persist($detailElectionDesk);
        $em->flush();
        return new JsonResponse(array("choix_nbInscrit"=>$detailElectionDesk->getNbRegistered(),"alert"=>"nombre d'inscrits enregistrés"));
    }

    /**
     * @param Desk $desk
     * @param Election $election
     * @param Integer $state
     * @ParamConverter("desk", class="AppBundle:Desk", options={"desk", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws
     *Allows you to change the state of an office during an election
     */
    public function newEtatAction(Desk $desk,Election $election,$state){
        $detailElectionDesk = $this->getDetailElectionDesk($desk,$election);

        /** @var SecurityService $object */
        $securityService = $this->get('SecurityService');
        if(!$securityService->isGrantedUse($this->getUser(),$detailElectionDesk->getDesk()->getQg())){
            throw new AccessDeniedHttpException("Vous n'avez pas le droit Qg pour ce bureau");
        }
        $detailElectionDesk->setState(DetailElectionDesk::getAvailableTypes()[$state]);
        $em = $this->getDoctrine()->getManager();

        $em->persist($detailElectionDesk);
        $em->flush();
        $finish = true;

        foreach ($detailElectionDesk->getDetailElectionTownHall()->getDetailsElectionDesk() as $detail){
            if(!$detail->getState()===DetailElectionDesk::STATE_VALID){
                $finish = false;
            }
        }
        if($finish){
            $detailElectionDesk->getDetailElectionTownHall()->setState(DetailElectionTownHall::STATE_FINICHED);
        }
        else{
            if($detailElectionDesk->getDetailElectionTownHall()->getState() === DetailElectionTownHall::STATE_FINICHED){
                $detailElectionDesk->getDetailElectionTownHall()->setState(DetailElectionTownHall::STATE_PUBLIC);
            }
        }

      $em->persist($detailElectionDesk->getDetailElectionTownHall());
        $em->flush();

        $rep = array("state"=>DetailElectionDesk::getAvailableTypes()[$state]);
        return new JsonResponse($rep);
    }



    /**
     * @param Desk $desk
     * @param Election $election
     * @ParamConverter("desk", class="AppBundle:Desk", options={"desk", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     *See the number of signatures in this office for this election
     */
    public function getNbSignatureAction(Desk $desk,Election $election){
        $detailElectionDesk = $this->getDetailElectionDesk($desk,$election);
        $this->testGrantedView($detailElectionDesk);
        $nbsignature = $detailElectionDesk->getNbSignature();
        return new JsonResponse(array("choix_nbSignature"=>$nbsignature));

    }




    /**
     * @param Desk $desk
     * @param Election $election
     * @ParamConverter("desk", class="AppBundle:Desk", options={"desk", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     *when the desk want to be validate
     */
    public function requestValidAction(Desk $desk,Election $election){
        $detailElectionDesk = $this->getDetailElectionDesk($desk,$election);
        $this->testGrantedView($detailElectionDesk);
        if($detailElectionDesk->getState() === DetailElectionDesk::STATE_PROGRESS){
            $detailElectionDesk->setState(DetailElectionDesk::STATE_RequestVALID);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($detailElectionDesk);
        $em->flush();
        return new JsonResponse(array("alert"=>"merci de recharger la page"));

    }



    /**
     * @param Desk $desk
     * @param Election $election
     * @ParamConverter("desk", class="AppBundle:Desk", options={"desk", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     *See the number of people registered in this office for this election
     */
    public function getNbInscritAction(Desk $desk,Election $election){
        $detailElectionDesk = $this->getDetailElectionDesk($desk,$election);
        $this->testGrantedView($detailElectionDesk);
        $nbinscrit = $detailElectionDesk->getNbRegistered();
        return new JsonResponse(array("choix_nbInscrit"=>$nbinscrit));

    }

}
