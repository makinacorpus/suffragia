<?php

namespace AppBundle\Controller\gestion;

use AppBundle\Entity\DetailElectionDesk;
use AppBundle\Entity\DetailElectionTownHall;
use AppBundle\Entity\Election;
use AppBundle\Service\SecurityService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
/**
 * Election controller.
 *
 */
class ElectionController extends Controller
{
    /**
     * Lists all election entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $elections = $em->getRepository('AppBundle:Election')->findAll();

        return $this->render(':gestion/election:index.html.twig', array(
            'elections' => $elections,
        ));
    }

    /**
     * Creates a new election entity.
     *
     */
    public function newAction(Request $request)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedCreate($this->getUser(),Election::class)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas de cree ce bureau, posseder vous un QG ?");
        }



        $election = new Election();
        $form = $this->createForm('AppBundle\Form\ElectionType', $election);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $townHalls = $election->getTmpTownHalls();
            $electionService = $this->container->get('ElectionService');
            foreach ($townHalls as $m) {
                $election = $electionService->registerTownHallToElection($election, $m);
            }
            if(count($election->getTmpTownHalls())>0){
                $electionService->importCandidat($election->getTmpTownHalls()[0],$election);
            }

            $em->persist($election);
            $em->flush($election);
            return $this->redirectToRoute('election_show', array('id' => $election->getId()));
        }

        return $this->render(':gestion/election:new.html.twig', array(
            'election' => $election,
            'form' => $form->createView(),
        ));
    }


    /**
     * @param Request $request
     * @param Election $election
     * @ParamConverter("election", class="AppBundle:Election",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function showAction(Election $election)
    {
        $deleteForm = $this->createDeleteForm($election);

        return $this->render(':gestion/election:show.html.twig', array(
            'election' => $election,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * @param Request $request
     * @param Election $election
     * @ParamConverter("election", class="AppBundle:Election",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function editAction(Request $request, Election $election)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedEdit($this->getUser(),$election)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas le droit d'editer ce bureau");
        }
        $election->setTownHalls($election->getTownHalls());
        $deleteForm = $this->createDeleteForm($election);
        $editForm = $this->createForm('AppBundle\Form\ElectionType', $election);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $election->setTownHalls($editForm->get('townHalls')->getData());
            $details = $em->getRepository('AppBundle:DetailElectionTownHall')->findAll();
            foreach ($details as $d) {
                if($d->getElection() === $election){
                    if(!in_array($d->getTownHall(),$election->getTmpTownHalls())){
                        $election->removeDetailsElectionTownHallFromTownHall($d->getTownHall());
                        $em->remove($d);
                    }
                }
            }
            $electionService = $this->container->get('ElectionService');

            foreach ($election->getTmpTownHalls() as $m) {
                $election = $electionService->registerTownHallToElection($election, $m);
            }


            $em->persist($election);
            $em->flush();
            return $this->redirectToRoute('election_edit', array('id' => $election->getId()));
        }

        return $this->render(':gestion/election:edit.html.twig', array(
            'election' => $election,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a election entity.
     *
     */
    /**
     * @param Request $request
     * @param Election $election
     * @ParamConverter("election", class="AppBundle:Election",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function deleteAction(Request $request, Election $election)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedEdit($this->getUser(),$election)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas le droit d'editer cette eection");
        }
        $form = $this->createDeleteForm($election);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($election);
            $em->flush($election);
        }
        return $this->redirectToRoute('election_index');
    }

    /**
     * Creates a form to delete a election entity.
     *
     * @param Election $election The election entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    /**
     * @param Request $request
     * @param Election $election
     * @ParamConverter("election", class="AppBundle:Election",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    private function createDeleteForm(Election $election)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('election_delete', array('id' => $election->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
