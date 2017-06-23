<?php

namespace AppBundle\Controller\gestion;

use AppBundle\Entity\Qg;
use AppBundle\Service\SecurityService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
/**
 * Qg controller.
 *
 */
class QgController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qgs = $em->getRepository('AppBundle:Qg')->findAll();

        return $this->render(':gestion/qg:index.html.twig', array(
            'qgs' => $qgs,
        ));
    }


    public function newAction(Request $request)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedCreate($this->getUser(),Qg::class)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas de cree ce Qg, vous posseder une mairie");
        }

        $qg = new Qg();
        $form = $this->createForm('AppBundle\Form\QgType', $qg);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qg);
            $em->flush($qg);
            $userService =  $this->get('UserService');
            $userService->manageRightQg($this->getUser(),$qg,false);
            $em->persist($qg);
            $em->flush();
            return $this->redirectToRoute('qg_show', array('id' => $qg->getId()));
        }

        return $this->render(':gestion/qg:new.html.twig', array(
            'qg' => $qg,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param Qg $qg
     * @ParamConverter("qg", class="AppBundle:Qg",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function showAction(Qg $qg)
    {
        $deleteForm = $this->createDeleteForm($qg);

        return $this->render(':gestion/qg:show.html.twig', array(
            'qg' => $qg,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qg entity.
     *
     */
    /**
     * @param Request $request
     * @param Qg $qg
     * @ParamConverter("qg", class="AppBundle:Qg",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function editAction(Request $request, Qg $qg)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedEdit($this->getUser(),$qg)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas le droit d'editer ce qg");
        }
        $deleteForm = $this->createDeleteForm($qg);
        $editForm = $this->createForm('AppBundle\Form\QgType', $qg);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('qg_edit', array('id' => $qg->getId()));
        }

        return $this->render(':gestion/qg:edit.html.twig', array(
            'qg' => $qg,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qg entity.
     *
     */
    /**
     * @param Request $request
     * @param Qg $qg
     * @ParamConverter("qg", class="AppBundle:Qg",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function deleteAction(Request $request, Qg $qg)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedEdit($this->getUser(),$qg)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas le droit d'editer ce qg");
        }

        $form = $this->createDeleteForm($qg);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $user->getTownHalls()[0]->removeQg($qg);
            $em->persist($qg);
            $em->persist($user);
            $em->flush($qg);

            $em->remove($qg);
            $em->flush($qg);
        }

        return $this->redirectToRoute('qg_index');
    }

    /**
     * Creates a form to delete a qg entity.
     *
     * @param Qg $qg The qg entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    /**
     * @param Request $request
     * @param Qg $qg
     * @ParamConverter("qg", class="AppBundle:Qg",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    private function createDeleteForm(Qg $qg)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('qg_delete', array('id' => $qg->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
/*
 *             // création de l'ACL
            $aclProvider = $this->get('security.acl.provider');
            $objectIdentity = ObjectIdentity::fromDomainObject($qg);
            $acl = $aclProvider->createAcl($objectIdentity);

            // retrouve l'identifiant de sécurité de l'utilisateur actuellement connecté
            $securityContext = $this->get('security.token_storage');
            $user = $securityContext->getToken()->getUser();
            $securityIdentity = UserSecurityIdentity::fromAccount($user);

            // donne accès au propriétaire
            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);
 */