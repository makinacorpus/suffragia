<?php
/**
 * Created by PhpStorm.
 * User: tux
 * Date: 10/06/17
 * Time: 02:46
 */

namespace AppBundle\Controller\utilisation;

use AppBundle\Entity\Concrete;
use AppBundle\Entity\Desk;
use AppBundle\Entity\DetailElectionDesk;
use AppBundle\Entity\DetailElectionTownHall;
use AppBundle\Entity\TownHall;
use AppBundle\Entity\Qg;
use AppBundle\Entity\User;

use AppBundle\Entity\Candidat;
use AppBundle\Entity\Detail;
use AppBundle\Entity\Election;
use AppBundle\Entity\PackVote;
use AppBundle\Entity\Parti;
use AppBundle\Service\DeskService;
use AppBundle\Service\DetailElectionDeskService;
use AppBundle\Service\DetailElectionTownHallService;
use AppBundle\Service\ElectionService;
use AppBundle\Service\PackHoraireService;
use AppBundle\Service\PackVoteService;
use AppBundle\Service\SecurityService;
use AppBundle\Service\TownHallService;
use Dompdf\Dompdf;
use PHPExcel_Writer_OpenDocument;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ExportController extends Controller
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
     * @param DetailElectionDesk $detailElectionDesk
     * @throws
     * @return boolean
     * test if you can use a detailElectionDesk
     *
     */
    protected function testGrantedView(DetailElectionTownHall $detailElectionTownHall){
        /** @var SecurityService $securityService */
        $securityService = $this->get('SecurityService');
        if($securityService->isGrantedView($this->getUser(),$detailElectionTownHall)){
            return true;
        }
        else{
            throw new \Exception("Ce bureau n'a pas/plus le droit de voir cette ressource");
        }
    }


    /**
     * @param TownHall $townHall
     * @param Election $election
     * @ParamConverter("townHall", class="AppBundle:TownHall", options={"townHall", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function csvAction(Election $election,TownHall $townHall)
    {
        $file = $this->export($election,$townHall);
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($file, 'CSV');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $election->getName().'-file.csv'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        return $response;

    }



    /**
     * @param TownHall $townHall
     * @param Election $election
     * @ParamConverter("townHall", class="AppBundle:TownHall", options={"townHall", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function excelAction(Election $election,TownHall $townHall)
    {
        $file = $this->export($election,$townHall);
        // create the writer
        $writer = $this->get('phpexcel')->createWriter($file, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'stream-file.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        return $response;


    }


    /**
     * @param TownHall $townHall
     * @param Election $election
     * @ParamConverter("townHall", class="AppBundle:TownHall", options={"townHall", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function jsonAction(Election $election,TownHall $townHall)
    {
        $service = $this->container->get('DetailElectionTownHallService');;
        $detail = $service->filterByTownHall($election->getDetailsElectionTownHall(),$townHall)[0];
        $this->testGrantedView($detail);
        return new JsonResponse($this->raw($detail));
    }

    /**
     * @param TownHall $townHall
     * @param Election $election
     * @ParamConverter("townHall", class="AppBundle:TownHall", options={"townHall", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function htmlAction(Election $election,TownHall $townHall)
    {

        $service = $this->container->get('DetailElectionTownHallService');;
        $detail = $service->filterByTownHall($election->getDetailsElectionTownHall(),$townHall)[0];
        $this->testGrantedView($detail);

        if($detail->getBackup() == ""){
            $raw = $this->raw($detail);
        }
        else{
            $raw = json_decode($detail->getBackup());
        }
        return $this->render(':utilisation/Visiteur:affichagehtml.html.twig', array('detailEM'=>$detail,'raw'=>$raw,"election"=>$election,"townHall"=>$townHall,"alpha"=>range('a','z')));
    }


    public function raw(DetailElectionTownHall $detailElectionTownHall){
        $service = $this->container->get('DetailElectionTownHallService');;

        $resume = $service->getRaw($detailElectionTownHall);
        return $resume;
    }



    /**
     * @param TownHall $townHall
     * @param Election $election
     * @ParamConverter("townHall", class="AppBundle:TownHall", options={"townHall", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @return \PHPExcel
     *
     */
    public function export(Election $election,TownHall $townHall)
    {
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator($this->getUser()->getUserName())->setTitle("Export de l election".$election->getNom()."- townHall ".$townHall->getNom());
        $phpExcelObject->setActiveSheetIndex(0);
        $alpha = array("A","B","C","D","E","F","G","H","I","J");
        $service = $this->container->get('DetailElectionTownHallService');;
        $detail = $service->filterByTownHall($election->getDetailsElectionTownHall(),$townHall)[0];
        $this->testGrantedView($detail);

        $raw= $this->raw($detail);
        $numberLine=0;
        foreach ($raw as $ligne ){
            $numColone=0;
            foreach ($ligne as $col){
                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue($alpha[$numColone].$numberLine, $col);
                $numColone++;
            }
            $numberLine++;
        }
        return $phpExcelObject;
    }


    /**
     * @param TownHall $townHall
     * @param Election $election
     * @ParamConverter("townHall", class="AppBundle:TownHall", options={"desk", "id"})
     * @ParamConverter("election", class="AppBundle:Election", options={"election", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     *set a state for a detailElectionTownHall
     */
    public function jsonBrutAction(TownHall $townHall,Election $election)
    {
        $detailElectionTownHall = $this->getDetailElectionTownHall($townHall,$election);
        $this->testGrantedView($detailElectionTownHall);
        /** @var DetailElectionTownHallService $detailElectionTownHallService */
        $detailElectionTownHallService =  $this->container->get('DetailElectionTownHallService');
        return $detailElectionTownHallService->getJsonBrut($detailElectionTownHall);
    }



}