<?php

namespace AppBundle\Controller\gestion;

use AppBundle\Entity\Parti;
use AppBundle\Service\SecurityService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
/**
 * Parti controller.
 *
 */
class PartiController extends Controller
{
    /**
     * Lists all parti entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $partis = $em->getRepository('AppBundle:Parti')->findAll();

        return $this->render(':gestion/parti:index.html.twig', array(
            'partis' => $partis,
        ));
    }



    public function removeAllAction(){
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedCreate($this->getUser(),Parti::class)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas de cree ce parti");
        }

        $em = $this->getDoctrine()->getManager();

        foreach ($em->getRepository("AppBundle:Parti")->findAll() as $parti){
            $oqp = false;
            foreach ($em->getRepository("AppBundle:Candidat")->findAll() as $candidat){
                if($candidat->getParti() === $parti){
                    $oqp = true;
                }
            }
            if($oqp===false){
                $em->remove($parti);
                $em->flush();
            }
        }

        return $this->redirectToRoute("parti_index");



    }


    /**
     * Creates a new parti entity.
     *
     */
    public function newAction(Request $request)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedCreate($this->getUser(),Parti::class)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas de cree ce parti");
        }

        $parti = new Parti();
        $form = $this->createForm('AppBundle\Form\PartiType', $parti);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($parti);
            $em->flush($parti);
            // création de l'ACL
            $aclProvider = $this->get('security.acl.provider');
            $objectIdentity = ObjectIdentity::fromDomainObject($parti);
            $acl = $aclProvider->createAcl($objectIdentity);

            // retrouve l'identifiant de sécurité de l'utilisateur actuellement connecté
            $securityContext = $this->get('security.token_storage');
            $user = $securityContext->getToken()->getUser();
            $securityIdentity = UserSecurityIdentity::fromAccount($user);

            // donne accès au propriétaire
            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);
            return $this->redirectToRoute('parti_show', array('id' => $parti->getId()));
        }

        return $this->render(':gestion/parti:new.html.twig', array(
            'parti' => $parti,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param Parti $parti
     * @ParamConverter("parti", class="AppBundle:Parti",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function showAction(Parti $parti)
    {
        $deleteForm = $this->createDeleteForm($parti);

        return $this->render(':gestion/parti:show.html.twig', array(
            'parti' => $parti,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param Parti $parti
     * @ParamConverter("parti", class="AppBundle:Parti",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function editAction(Request $request, Parti $parti)
    {

        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedEdit($this->getUser(),$parti)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas le droit d'editer ce bureau");
        }
        $deleteForm = $this->createDeleteForm($parti);
        $editForm = $this->createForm('AppBundle\Form\PartiType', $parti);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('parti_edit', array('id' => $parti->getId()));
        }

        return $this->render(':gestion/parti:edit.html.twig', array(
            'parti' => $parti,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param Parti $parti
     * @ParamConverter("parti", class="AppBundle:Parti",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function deleteAction(Request $request, Parti $parti)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedEdit($this->getUser(),$parti)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas le droit d'editer ce candidat");
        }

        $securityContext = $this->get('security.authorization_checker');
        if (false === $securityContext->isGranted('DELETE', $parti))
        {
            throw new AccessDeniedException();
        }
        $form = $this->createDeleteForm($parti);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($parti);
            $em->flush($parti);
        }

        return $this->redirectToRoute('parti_index');
    }

    /**
     * @param Request $request
     * @param Parti $parti
     * @ParamConverter("parti", class="AppBundle:Parti",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    private function createDeleteForm(Parti $parti)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('parti_delete', array('id' => $parti->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
