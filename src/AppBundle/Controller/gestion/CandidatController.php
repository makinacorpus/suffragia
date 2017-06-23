<?php

namespace AppBundle\Controller\gestion;

use AppBundle\Entity\Candidat;
use AppBundle\Entity\Parti;
use AppBundle\Service\ElectionService;
use AppBundle\Service\SecurityService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
/**
 * Candidat controller.
 *
 */
class CandidatController extends Controller
{
    /**
     * Lists all candidat entities.
     *
     */
    public function indexAction()
    {




        $em = $this->getDoctrine()->getManager();

        $candidats = $em->getRepository('AppBundle:Candidat')->findAll();

        return $this->render(':gestion/candidat:index.html.twig', array(
            'candidats' => $candidats,
        ));
    }

    /**
     * Creates a new candidat entity.
     *
     */
    public function newAction(Request $request)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');
        /** @var ElectionService $electionService */
        $electionService = $this->container->get('Electionservice');

        if(!$service->isGrantedCreate($this->getUser(),Candidat::class)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas de cree ce candidat");
        }


        $candidat = new Candidat();
        $form = $this->createForm('AppBundle\Form\CandidatType', $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if($candidat->getParti()===null){
                $parti = $em->getRepository('AppBundle:Parti')
                    ->findOneBy(array('name' => "div"));
                    if($parti == null){
                        $parti = new Parti();
                        $c= "SANS_ETIQUETTE";
                        $parti->setName($c);
                        $parti->setColor($electionService->getColorParti($parti));
                        $candidat->setParti($parti);
                        $em->persist($parti);
                    }
                    else{
                        $candidat->setParti($parti);
                    }


            }


            $em->persist($candidat);
            $em->flush($candidat);

            return $this->redirectToRoute('candidat_show', array('id' => $candidat->getId()));
        }

        return $this->render(':gestion/candidat:new.html.twig', array(
            'candidat' => $candidat,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param Candidat $candidat
     * @ParamConverter("candidat", class="AppBundle:Candidat",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function showAction(Candidat $candidat)
    {
        $deleteForm = $this->createDeleteForm($candidat);

        return $this->render(':gestion/candidat:show.html.twig', array(
            'candidat' => $candidat,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param Candidat $candidat
     * @ParamConverter("candidat", class="AppBundle:Candidat",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function editAction(Request $request, Candidat $candidat)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedEdit($this->getUser(),$candidat)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas le droit d'editer ce bureau");
        }

        $deleteForm = $this->createDeleteForm($candidat);
        $editForm = $this->createForm('AppBundle\Form\CandidatType', $candidat);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('candidat_edit', array('id' => $candidat->getId()));
        }

        return $this->render(':gestion/candidat:edit.html.twig', array(
            'candidat' => $candidat,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param Candidat $candidat
     * @ParamConverter("candidat", class="AppBundle:Candidat",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function deleteAction(Request $request, Candidat $candidat)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedEdit($this->getUser(),$candidat)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas le droit d'editer ce candidat");
        }
        $form = $this->createDeleteForm($candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($candidat);
            $em->flush($candidat);
        }

        return $this->redirectToRoute('candidat_index');
    }

    /**
     * @param Request $request
     * @param Candidat $candidat
     * @ParamConverter("candidat", class="AppBundle:Candidat",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    private function createDeleteForm(Candidat $candidat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('candidat_delete', array('id' => $candidat->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
