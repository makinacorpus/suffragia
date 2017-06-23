<?php

namespace AppBundle\Controller\gestion;

use AppBundle\Entity\Desk;
use AppBundle\Entity\DetailElectionDesk;
use AppBundle\Entity\Election;
use AppBundle\Entity\PackHoraire;
use AppBundle\Service\DetailElectionDeskService;
use AppBundle\Service\SecurityService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
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
     * @ParamConverter("desk", class="AppBundle:Desk",options={"desk": "id"})
     * @ParamConverter("election", class="AppBundle:Election",options={"election": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function newBisAction(Desk $desk,Election $election,$nb)
    {



        $packHoraireService = $this->container->get('PackHoraireService');

        /** @var DetailElectionDeskService $detailElectionDeskService */
        $detailElectionDeskService = $this->container->get('DetailElectionDeskService');
        $detailED = $detailElectionDeskService->getDetailElectionDesk($desk,$election);

        /** @var SecurityService $securityService */
        if(!$securityService->isGrantedEdit($this->getUser(),$detailED)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("Vous n'avez pas le droit de faire cette action");
        }

        $nb= intval($nb);
        if($nb==0){
            throw new \TypeError("mauvais type, int voulu");

        }
        $repository2 = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:PackHoraire')
        ;
        $packs = $repository2->findAll();
        $existe = false;
        foreach ($packs as $p){
            if($p->getDesk()=== $desk and $p->getElection()==$election and $p->getDateTime()->format("M:D:H") === (new \DateTime())->format("M:D:H")){
                $msg = "Vous avez deja entrée une participation pour cette heure de ".$p->getNbVotant()." votants le ".$p->getDateTime()->format("d/m/Y H:i:s");
                $existe=true;
            }
        }
        if(!$existe) {
            $h = new PackHoraire();
            $h->setDesk($desk);
            $h->setElection($election);
            $h->setNbVotant($nb);
            $h->setDateTime(new \DateTime());
            $date = new \DateTime();
            $msg = "Participation de ".$nb." participants le ".$date->format(DATE_W3C);
            $em = $this->getDoctrine()->getManager();
            $em->persist($h);
            $em->flush();
        }
        $rep[] = array("alert"=>$msg);
        return new JsonResponse($rep);
    }



    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $packHoraires = $em->getRepository('AppBundle:PackHoraire')->findAll();
        $liste = array();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        foreach ($packHoraires as $p){
            if($p->getDetailElectionDesk()->getDesk() === $user->getDeskx()[0]){
                $liste[] = $p;
            }
        }
        return $this->render(':gestion/packhoraire:index.html.twig', array(
            'packHoraires' => $liste,
        ));
    }



    /**
     * @param Request $request
     * @param PackHoraire $packHoraire
     * @ParamConverter("packHoraire", class="AppBundle:PackHoraire",options={"desk": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function showAction(PackHoraire $packHoraire)
    {
        $deleteForm = $this->createDeleteForm($packHoraire);

        return $this->render(':gestion/packhoraire/show.html.twig', array(
            'packHoraire' => $packHoraire,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param PackHoraire $packHoraire
     * @ParamConverter("packHoraire", class="AppBundle:PackHoraire",options={"desk": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function editAction(Request $request, PackHoraire $packHoraire)
    {
        $packHoraireService = $this->container->get('PackHoraireService');

        /** @var DetailElectionDeskService $detailElectionDeskService */
        $detailElectionDeskService = $this->container->get('DetailElectionDeskService');
        $detailED = $detailElectionDeskService->getDetailElectionDesk($packHoraire->getDesk(),$packHoraire->getElection());

        /** @var SecurityService $securityService */
        if(!$securityService->isGrantedEdit($this->getUser(),$detailED)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("Vous n'avez pas le droit de faire cette action");
        }

        $deleteForm = $this->createDeleteForm($packHoraire);
        $editForm = $this->createForm('AppBundle\Form\PackHoraireType', $packHoraire);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('packhoraire_edit', array('id' => $packHoraire->getId()));
        }

        return $this->render(':gestion/packhoraire:edit.html.twig', array(
            'packHoraire' => $packHoraire,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param PackHoraire $packHoraire
     * @ParamConverter("packHoraire", class="AppBundle:PackHoraire",options={"desk": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function deleteAction(Request $request, PackHoraire $packHoraire)
    {
        $packHoraireService = $this->container->get('PackHoraireService');

        /** @var DetailElectionDeskService $detailElectionDeskService */
        $detailElectionDeskService = $this->container->get('DetailElectionDeskService');
        $detailED = $detailElectionDeskService->getDetailElectionDesk($packHoraire->getDesk(),$packHoraire->getElection());

        /** @var SecurityService $securityService */
        if(!$securityService->isGrantedEdit($this->getUser(),$detailED)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("Vous n'avez pas le droit de faire cette action");
        }
        $form = $this->createDeleteForm($packHoraire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($packHoraire);
            $em->flush();
        }

        return $this->redirectToRoute('packhoraire_index');
    }


    /**
     * @param Request $request
     * @param PackHoraire $packHoraire
     * @ParamConverter("packHoraire", class="AppBundle:PackHoraire",options={"desk": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    private function createDeleteForm(PackHoraire $packHoraire)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('packhoraire_delete', array('id' => $packHoraire->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

}
