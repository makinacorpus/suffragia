<?php

namespace AppBundle\Controller\gestion;

use AppBundle\Entity\Election;
use AppBundle\Entity\MetaElection;
use AppBundle\Service\SecurityService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Metaelection controller.
 *
 * @Route("metaelection")
 */
class MetaElectionController extends Controller
{
    /**
     * Lists all metaElection entities.
     *
     * @Route("/", name="metaelection_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $metaElections = $em->getRepository('AppBundle:MetaElection')->findAll();

        return $this->render('gestion/metaelection/index.html.twig', array(
            'metaElections' => $metaElections,
        ));
    }

    /**
     * Creates a new metaElection entity.
     *
     * @Route("/new", name="metaelection_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedCreate($this->getUser(),MetaElection::class)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas de cree cette meta Election");
        }


        $metaElection = new Metaelection();
        $form = $this->createForm('AppBundle\Form\MetaElectionType', $metaElection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($metaElection);
            $em->flush();

            return $this->redirectToRoute('metaelection_show', array('id' => $metaElection->getId()));
        }

        return $this->render('gestion/metaelection/new.html.twig', array(
            'metaElection' => $metaElection,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param MetaElection $metaElection
     * @ParamConverter("metaElection", class="AppBundle:MetaElection",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function showAction(MetaElection $metaElection)
    {
        $deleteForm = $this->createDeleteForm($metaElection);

        return $this->render('gestion/metaelection/show.html.twig', array(
            'metaElection' => $metaElection,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param MetaElection $metaElection
     * @ParamConverter("metaElection", class="AppBundle:MetaElection",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function editAction(Request $request, MetaElection $metaElection)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedEdit($this->getUser(),$metaElection)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas le droit d'editer ce bureau");
        }
        $deleteForm = $this->createDeleteForm($metaElection);
        $editForm = $this->createForm('AppBundle\Form\MetaElectionType', $metaElection);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('metaelection_edit', array('id' => $metaElection->getId()));
        }

        return $this->render('gestion/metaelection/edit.html.twig', array(
            'metaElection' => $metaElection,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param MetaElection $metaElection
     * @ParamConverter("metaElection", class="AppBundle:MetaElection",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function deleteAction(Request $request, MetaElection $metaElection)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');
        if(!$service->isGrantedEdit($this->getUser(),$metaElection)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas le droit d'editer cette metaElection");
        }

        $form = $this->createDeleteForm($metaElection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($metaElection);
            $em->flush();
        }

        return $this->redirectToRoute('metaelection_index');
    }

    /**
     * Creates a form to delete a metaElection entity.
     *
     * @param MetaElection $metaElection The metaElection entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MetaElection $metaElection)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('metaelection_delete', array('id' => $metaElection->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
