<?php

namespace AppBundle\Controller\utilisation;

use AppBundle\Entity\Desk;
use AppBundle\Entity\DetailElectionDesk;
use AppBundle\Entity\Election;
use AppBundle\Entity\TownHall;
use AppBundle\Entity\PackHoraire;
use AppBundle\Service\DetailElectionDeskService;
use AppBundle\Service\SecurityService;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Packhoraire controller.
 *
 */
class PackHoraireController extends Controller
{

    /**
     * @param Request $request
     * @param Desk $desk
     * @param Election $election
     * @param Election $election
     * @ParamConverter("desk", class="AppBundle:Desk",options={"desk": "id"})
     * @ParamConverter("election", class="AppBundle:Election",options={"election": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *Recovers the last participation
     */
    public function getLastParticipationAction(Desk $desk,Election $election){
        $packHoraireService = $this->container->get('PackHoraireService');

        /** @var DetailElectionDeskService $detailElectionDeskService */
        $detailElectionDeskService = $this->container->get('DetailElectionDeskService');
        $detailED = $detailElectionDeskService->getDetailElectionDesk($desk,$election);
        $packHoraires = $detailED->getPacksHoraire();



        $packHoraires = $packHoraireService->sortByDate($packHoraires);
        if(count($packHoraires)==0){
            $msg="aucune participation rentrée";
        }
        else{
            $msg = "dernière participation: ". end($packHoraires)->getdateTime()->format("d/m/Y H:i:s");
        }
        $rep[] = array("getParticipation"=>$msg);
        return new JsonResponse($rep);
    }


    /**
     * @param Request $request
     * @param Desk $desk
     * @param Election $election
     * @param Election $election
     * @ParamConverter("desk", class="AppBundle:Desk",options={"desk": "id"})
     * @ParamConverter("election", class="AppBundle:Election",options={"election": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *Informs participation at the moment
     */
    public function newNowAction(Desk $desk,Election $election,$nb){



        $packHoraireService = $this->container->get('PackHoraireService');

        /** @var DetailElectionDeskService $detailElectionDeskService */
        $detailElectionDeskService = $this->container->get('DetailElectionDeskService');
        $detailED = $detailElectionDeskService->getDetailElectionDesk($desk,$election);

        /** @var SecurityService $securityService */
        if(!$securityService->isGrantedEdit($this->getUser(),$detailED)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("Vous n'avez pas le droit de faire cette action");
        }

        $msg="";
        if(count($detailED->getPacksHoraire())!=0){
            $packs = $detailED->getPacksHoraire();
            $packs = $packHoraireService->sortbyDate($packs);
            $last= end($packs)->getDateTime();
            $now = new \DateTime();
            if($now->format("D-H")===$last->format("D-H")){
                $msg = "participation deja rentrée à ".$last->format("d/m/Y H:i:s");
            }
        }
        if($msg===""){
            $ph = new PackHoraire();
            $ph->setDateTime(new \DateTime());
            $ph->setDetailElectionDesk($detailED);
            $detailED->addPackHoraire($ph);
            $ph->setNbVotant($nb);
            $em = $this->getDoctrine()->getManager();
            $em->persist($detailED);
            $em->persist($ph);
            $em->flush();
            $msg = "participation rentrée à ".(new \DateTime())->format("d/m/Y H:i:s");
        }
        return new JsonResponse(array("getParticipation"=>$msg,"alert"=>$msg));

    }





}
