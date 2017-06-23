<?php
/**
 * Created by PhpStorm.
 * User: tux
 * Date: 10/05/17
 * Time: 17:58
 */
namespace AppBundle\Tests\Unit;

use AppBundle\AppBundle;
use AppBundle\Controller\gestion\MetaElectionController;
use AppBundle\Controller\utilisation\DetailElectionDeskController;
use AppBundle\Controller\utilisation\UserController;
use AppBundle\Entity\DetailElectionDesk;
use AppBundle\Entity\MetaElection;
use AppBundle\Entity\TownHall;
use AppBundle\Service\DetailElectionDeskService;
use AppBundle\Service\FactoryService;
use AppBundle\Service\SimpleFactoryService;
use AppBundle\Service\UserService;
use AppBundle\Tests\BaseTest;
use AppBundle\Tests\Unit\BaseUnitTest;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DetailElectionDeskControllerTest extends BaseTest
{

    private $CODE_OK = 200;
    private $CODE_FORBIDDEN = 403;



    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }


    /**
     * @var \AppBundle\Entity\MetaElection
     */
    protected $metaElection = null;

    /**
     * @var \AppBundle\Entity\Election
     */
    protected $election = null;
    /**
     * @var \AppBundle\Entity\Candidat
     */
    protected $candidate = null;




    /**
     * @var \AppBundle\Entity\TownHall
     */
    protected $townHall = null;
    /**
     * @var \AppBundle\Entity\Qg
     */
    protected $qg = null;
    /**
     * @var \AppBundle\Entity\Desk
     */
    protected $desk = null;


    /**
     * @var DetailElectionDeskController
     */
    protected $detailElectionDeskController = null;

    /**
     * @var \AppBundle\Entity\DetailElectionDesk
     */
    protected $detailElectionDesk = null;
    /**
     * @var \AppBundle\Controller\utilisation\UserController
     */
    protected $userController = null;
    /**
     * @var UserService
     */
    protected $userService = null;


    protected $addition = 42;
    protected $state = \AppBundle\Entity\DetailElectionDesk::STATE_VALID;



    public function init()
    {

        if($this->detailElectionDesk === null or 2!=7) {
            $this->generateClient();
            $container = $this->getContainer();
            /** @var SimpleFactoryService $factoryService */
            $factoryService = $container->get('simpleFactoryService');

            /** @var DetailElectionDeskService $detailElectionDeskService */
            $detailElectionDeskService = $container->get('detailelectiondeskservice');

            $electionService = $container->get('ElectionService');

            $this->townHall = $factoryService->generateTownHall("townHallTest");

            $this->metaElection = $factoryService->generateMetaElection("metaElectiontest");
            $this->election = $factoryService->generateElection($this->metaElection,"electiontest");
            $this->candidate = $factoryService->generateCandidate($this->election);
            $this->candidate = $factoryService->generateCandidate($this->election);
            $this->qg = $factoryService->generateQg($this->townHall);
            $this->desk = $factoryService->generateDesk($this->qg);

            $electionService->registerTownHallToElection($this->election,$this->townHall);
            $this->detailElectionDesk = $detailElectionDeskService->getDetailElectionDesk($this->desk,$this->election);

            $this->userController = new UserController();
            $this->userController->setContainer($this->getContainer());
            $userService = $this->getContainer()->get("userservice");
            $userService->setEm($this->getManager());
            $this->userService = $userService;

            $this->getManager()->persist($this->townHall);
            $this->getManager()->flush();

            $this->getManager()->persist($this->election->getMetaElection());
            $this->getManager()->flush();
            //$this->getManager()->flush();
        }

    }

    public function end()
    {
        return;
        $status = 200;$this->removeRole();
        $this->makeUserAdmin();
        $this->generateClient();
        $metaElectionTest = new MetaElectionTest();
        $metaElectionTest->setContainer($this->getContainer());
        $metaElectionTest->setCurrentClient($this->getCurrentClient());
        $metaElectionTest->delete($this->election->getMetaElection());

        $metaElectionTest = new TownHallTest();
        $metaElectionTest->setContainer($this->getContainer());
        $metaElectionTest->setCurrentClient($this->getCurrentClient());
        $metaElectionTest->delete($this->townHall);

        parent::removeUser();
    }


    public function removeRole(){
        $userService =$this->userService;
        $userService->removeRightDesk($this->getCurrentUser());
        $userService->removeRightQg($this->getCurrentUser());
        $userService->removeRightTownHall($this->getCurrentUser());
        $userService->removeRightTouriste($this->getCurrentUser());
        $userService->removeRightAdmin($this->getCurrentUser());
    }

    public function makeUserDesk(){
        $this->userService->manageRightDesk($this->getCurrentUser(),$this->desk);
    }

    public function makeUserAdmin(){
        $this->userService->manageRightAdmin($this->getCurrentUser());
    }

    public function makeUserQg(){
        $this->userService->manageRightQg($this->getCurrentUser(),$this->qg);
    }

    public function makeUserTownHall(){
        $this->userService->manageRightTownHall($this->getCurrentUser(),$this->townHall);
    }

    public function test(){
        $this->init();



        $status = 403;
        $this->removeRole();
        $this->newState($status);
        $this->newNbInscrit($status);
        $this->newNbSignature($status);
        $this->newVoteDirect($status);
         //$this->validVote($status);

        $status = 200;$this->removeRole();
        $this->makeUserDesk();
        $this->generateClient();
        $this->newNbInscrit($status);
        $this->newNbSignature($status);
        $this->newVoteDirect($status);

        $status = 403;$this->newState($status);
         //$status = 403;$this->validVote($status);


        $status = 200;$this->removeRole();
        $this->makeUserQg();
        $this->generateClient();
        $this->newNbInscrit($status);
        $this->newNbSignature($status);
        $this->newVoteDirect($status);
        $status = 200;$this->validVote($status);


        $this->end();
        parent::removeUser();


    }




    public function newNbSignature($status){
        $nb = $this->detailElectionDesk->getNbSignature();
        $this->request("detailEB_nbSignature",array("desk"=>$this->desk->getId(),"election"=>$this->election->getId(),"nbsignature"=>$this->addition),$status);
        $content = $this->request("detailEB_getNbSignature",array("desk"=>$this->desk->getId(),"election"=>$this->election->getId(),"nbinscrit"=>$this->addition),$status);
        $nb2 = (json_decode($content,true))["choix_nbSignature"];
        if($status ===200) {
            $this->assertEquals($nb + $this->addition, $nb2);
        }
        else{
            $this->assertEquals($nb, $nb2);
        }

    }

    public function newNbInscrit($status){
        $nb = $this->detailElectionDesk->getNbRegistered();
        $this->request("detailEB_nbInscrit",array("desk"=>$this->desk->getId(),"election"=>$this->election->getId(),"nbinscrit"=>$this->addition),$status);
        $content = $this->request("detailEB_getNbInscrit",array("desk"=>$this->desk->getId(),"election"=>$this->election->getId(),"nbinscrit"=>$this->addition),$status);
        $nb2 = (json_decode($content,true))["choix_nbInscrit"];
        if($status ===200) {
            $this->assertEquals($nb + $this->addition, $nb2);
        }
        else{
            $this->assertEquals($nb, $nb2);
        }

    }

    public function newVoteDirect($status){


        $content = $this->request("packvote_new_election",array("desk"=>$this->desk->getId(),"election"=>$this->election->getId(),"mode"=>2),$status);

        $content = $this->request("desk_getVoteCandidatDesk",array("desk"=>$this->desk->getId(),"candidat"=>$this->candidate->getId()),$status);


        if($status ===200) {
            $nb = (json_decode($content,true))["nbVote"];
            $this->request("desk_directVote",array("desk"=>$this->desk->getId(),"candidat"=>$this->candidate->getId(),"number"=>($this->addition)),$status);
            $content = $this->request("desk_getVoteCandidatDesk",array("desk"=>$this->desk->getId(),"candidat"=>$this->candidate->getId()),$status);
            $nb2 = (json_decode($content,true))["nbVote"];
            $this->assertEquals($nb+$this->addition,$nb2);
        }

    }



    public function newState($status){
        $this->detailElectionDesk->setState(\AppBundle\Entity\DetailElectionDesk::STATE_CLOSE);

        $this->request("detailEB_state",array("desk"=>$this->desk->getId(),"election"=>$this->election->getId(),"state"=>\AppBundle\Entity\DetailElectionDesk::getIndexOfState(\AppBundle\Entity\DetailElectionDesk::STATE_VALID)),$status);
        if($status ===200) {
            $this->assertEquals($this->detailElectionDesk->getState(),\AppBundle\Entity\DetailElectionDesk::STATE_VALID);
            $this->request("detailEB_state",array("desk"=>$this->desk->getId(),"election"=>$this->election->getId(),"state"=>\AppBundle\Entity\DetailElectionDesk::getIndexOfState(\AppBundle\Entity\DetailElectionDesk::STATE_PROGRESS)),$status);//for modify this next

        }
        else{
            $this->assertEquals($this->detailElectionDesk->getState(),\AppBundle\Entity\DetailElectionDesk::STATE_CLOSE);
        }
        $this->detailElectionDesk->setState(\AppBundle\Entity\DetailElectionDesk::STATE_PROGRESS);
        $this->request("detailEB_state",array("desk"=>$this->desk->getId(),"election"=>$this->election->getId(),"state"=>\AppBundle\Entity\DetailElectionDesk::getIndexOfState(\AppBundle\Entity\DetailElectionDesk::STATE_PROGRESS)),$status);//for modify this next

    }


    public function validVote($status){
        $qg = $this->qg;
        $this->request("qg_utilisation_validVote",array("qg"=>$qg->getId(),"election"=>$this->election->getId()),$status);
    }



}
