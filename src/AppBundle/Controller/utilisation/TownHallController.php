<?php

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

/**
 * QgRController controller.
 *
 */
class TownHallController extends Controller
{


}
