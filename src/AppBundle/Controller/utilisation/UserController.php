<?php

namespace AppBundle\Controller\utilisation;

use AppBundle\Entity\Desk;
use AppBundle\Entity\Candidat;
use AppBundle\Entity\Election;
use AppBundle\Entity\MetaElection;
use AppBundle\Entity\TownHall;
use AppBundle\Entity\Qg;
use AppBundle\Entity\User;
use AppBundle\Service\MetaElectionService;
use AppBundle\Service\SecurityService;
use AppBundle\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * User controller.
 *
 */
class UserController extends Controller
{

    /**
     * @param MetaElection $metaElection
     * @ParamConverter("metaElection", class="AppBundle:MetaElection",options={"metaElections": "id"},isOptional="true")
     **/
    public function actionAction(MetaElection $metaElection = null)
    {

        /** @var User $user */

        $user = $this->getUser();
        $msg = "";
        $doctrine = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:MetaElection');
        $metaElections = $doctrine->findAll();

        /** @var UserService $userService */
        $userService = $this->container->get('UserService');
        $townHall = $userService->getTownHall($this->getUser());

        if($townHall === null){
            $msg = "Vous n'avez pas de mairie associée";
        }


        $election = null;
        if($townHall !== null and $metaElection != null) {
            /** @var MetaElectionService $metaElectionService */
            $metaElectionService = $this->container->get('MetaElectionService');
            $electionPotentiel = $metaElectionService->getElectionByTownHall($metaElection, $townHall);
            if ($electionPotentiel === null) {
                $msg = "votre mairie n'est pas abonnée a cette élection";
            } else {
                $election = $electionPotentiel;
            }
        }

        return $this->render(':utilisation/User:action.html.twig',array("msg"=>$msg,'user'=>$user,'metaElections'=>$metaElections,'election'=>$election));
    }

    /*
    public function possessionAction()
    {
        $repositoryPackVote = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:TownHall');
        // On récupère l'entité correspondante à l'id $id
        $townHalls = $repositoryPackVote->findAll();

        return $this->render(':utilisation/User:possession.html.twig',array('townHalls'=>$townHalls));
    }
/*/


    public function profileAction(){

        return $this->render(':utilisation/User:profile.html.twig',array());


    }




    public function manageRightAction(){


        $userManager = $this->get('fos_user.user_manager');
        $users = $this->belongingFilterUser($userManager->findUsers());

        $repository2 = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Qg')
        ;
        $qgs =$this->belongingFilterQg( $repository2->findAll());

        $repository2 = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Desk')
        ;
        $deskx =$this->belongingFilterDesk( $repository2->findAll());

        $repository2 = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:TownHall')
        ;
        $townHalls = $this->belongingFilterTownHall($repository2->findAll());

       return $this->render('utilisation/User/manageRight.twig',array('deskx'=>$deskx,"qgs"=>$qgs,'users'=>$users,'townHalls'=>$townHalls));
    }





    /**
     * @param Request $request
     * @param User $user
     * @ParamConverter("user", class="AppBundle:User",options={"user": "id"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     */
    public function removeRightQgAction(User $user)
    {
        $userService =  $this->get('UserService');
        $userService->removeRightQg($user);
        $rep = array("roles"=>$user->getRoles());
        return new JsonResponse($rep);
    }



    /**
     * @param Request $request
     * @param User $user
     * @ParamConverter("user", class="AppBundle:User",options={"user": "id"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     */
    public function removeRightTouristeAction(User $user)
    {
        $userService =  $this->get('UserService');
        $userService->removeRightTouriste($user);
        $rep = array("roles"=>$user->getRoles());
        return new JsonResponse($rep);
    }


    /**
     * @param Request $request
     * @param User $user
     * @ParamConverter("user", class="AppBundle:User",options={"user": "id"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     */
    public function removeRightTownHallAction(User $user)
    {
        $userService =  $this->get('UserService');
        $userService->removeRightTownHall($user);
        $rep = array("roles"=>$user->getRoles());
        return new JsonResponse($rep);
    }
    
    /**
     * @param Request $request
     * @param User $user
     * @ParamConverter("user", class="AppBundle:User",options={"user": "id"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     */
    public function removeRightDeskAction(User $user)
    {
        $userService =  $this->get('UserService');
        $userService->removeRightDesk($user);
        $rep = array("roles"=>$user->getRoles());
        return new JsonResponse($rep);
    }

    /**
     * @param Request $request
     * @param User $user
     * @ParamConverter("user", class="AppBundle:User",options={"user": "id"})
     * @param Qg $qg
     * @ParamConverter("qg", class="AppBundle:Qg",options={"qg": "id"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     */
    public function manageRightQgAction(User $user,QG $qg){

        $userService =  $this->get('UserService');
        $userService->manageRightQg($user,$qg);
        $rep = array("roles"=>$user->getRoles());
        return new JsonResponse($rep);

    }

    /**
     * @param Request $request
     * @param User $user
     * @ParamConverter("user", class="AppBundle:User",options={"user": "id"})
     * @param TownHall $townHall
     * @ParamConverter("townHall", class="AppBundle:TownHall",options={"townHall": "id"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     */
    public function manageRightTownHallAction(User $user,TownHall $townHall){
        $userService =  $this->get('UserService');
        $userService->manageRightTownHall($user,$townHall);
        $rep = array("roles"=>$user->getRoles());
        return new JsonResponse($rep);

    }




    /**
     * @param Request $request
     * @param User $user
     * @ParamConverter("user", class="AppBundle:User",options={"user": "id"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     */
    public function manageRightTouristeAction(User $user){
        $userService =  $this->get('UserService');
        $userService->manageRightTouriste($user);
        $rep = array("roles"=>$user->getRoles());
        return new JsonResponse($rep);

    }



    /**
     * @param Request $request
     * @param User $user
     * @ParamConverter("user", class="AppBundle:User",options={"user": "id"})
     * @param Desk $desk
     * @ParamConverter("desk", class="AppBundle:Desk",options={"desk": "id"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function manageRightDeskAction(User $user,Desk $desk){
        $userService =  $this->get('UserService');
        $userService->manageRightDesk($user,$desk);
        $rep = array("roles"=>$user->getRoles());
        return new JsonResponse($rep);
    }

    /**
     * @param Request $request
     * @param User $user
     * @ParamConverter("user", class="AppBundle:User",options={"user": "id"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeUserAction(User $user){
        $userService =  $this->get('UserService');
        $userService->removeUser($user);
        $rep = array("ok");
        return new JsonResponse($rep);
    }



    private function belongingFilterUser($users2)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        $users = array();
        foreach ($users2 as $u) {
            if($service->isGrantedEdit($this->getUser(),$u)){
                $users[] = $u;
            }
        }
        return $users;
    }

    /**
     * @param Qg[] $qgs
     * @return array
     *
     */
    private function belongingFilterQg($qgs)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        $items = array();
        foreach ($qgs as $qg) {
            if($service->isGrantedEdit($this->getUser(),$qg)){
                $items[] = $qg;
            }
        }
        return $items;
    }

    private function belongingFilterDesk($desks)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        $items = array();
        foreach ($desks as $desk) {
            if($service->isGrantedEdit($this->getUser(),$desk)){
                $items[] = $desk;
            }
        }
        return $items;
    }

    public function belongingFilterTownHall($townHalls){
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        $items = array();
        foreach ($townHalls as $townHall) {
            if($service->isGrantedEdit($this->getUser(),$townHall)){
                $items[] = $townHall;
            }
        }
        return $items;
    }

}

