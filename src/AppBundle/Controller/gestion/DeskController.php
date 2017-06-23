<?php

namespace AppBundle\Controller\gestion;

use AppBundle\Controller\utilisation\UserController;
use AppBundle\Entity\Desk;
use AppBundle\Entity\Group;
use AppBundle\Service\ElectionService;
use AppBundle\Service\SecurityService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
/**
 * Desk controller.
 *
 */
class DeskController extends Controller
{




    /**
     * Lists all desk entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $desks = $this->belongingFilterDesk($em->getRepository('AppBundle:Desk')->findAll());
        return $this->render(':gestion/desk:index.html.twig', array(
            'desks' => $desks,
        ));
    }


    public function refresh($desk){


    }



    /**
     * Creates a new desk entity.
     *
     */
    public function newAction(Request $request)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');
        /** @var ElectionService $electionService */
        $electionService = $this->container->get('ElectionService');
        if(!$service->isGrantedCreate($this->getUser(),Desk::class)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas de cree ce bureau, posseder vous un QG ?");
        }

        $desk = new Desk();
        $form = $this->createForm('AppBundle\Form\DeskType', $desk);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if (count($user->getQgs()) < 1) {
                throw new \Symfony\Component\Finder\Exception\AccessDeniedException("vous n'avez pas les droits d'un qg");
            }
            $em = $this->getDoctrine()->getManager();

            $em->persist($desk);




            foreach ($desk->getQg()->getTownHall()->getDetailsElectionTownHall() as $detail){
                 $electionService->registerTownHallToElection($detail->getElection(), $detail->getTownHall());
                $em->persist($detail->getElection());
                $em->persist($detail->getTownHall());
                $em->flush();
            };
            $em->flush();

            $userService =  $this->get('UserService');
            $userService->manageRightDesk($this->getUser(),$desk,false);
            return $this->redirectToRoute('desk_show', array('id' => $desk->getId()));
        }

        return $this->render(':gestion/desk:new.html.twig', array(
            'desk' => $desk,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a desk entity.
     *
     */
    /**
     * @param Request $request
     * @param Desk $desk
     * @ParamConverter("desk", class="AppBundle:Desk",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function showAction(Desk $desk)
    {



        $townHall = $desk->getQg()->getTownHall();
        $detailElectionTownHallService = $this->container->get('DetailElectionTownHallService');
        $elections = $detailElectionTownHallService->getElections($townHall->getDetailsElectionTownHall());
        $deleteForm = $this->createDeleteForm($desk);

        return $this->render(':gestion/desk:show.html.twig', array(
            'desk' => $desk,
            'elections' => $elections,

            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * @param Request $request
     * @param Desk $desk
     * @ParamConverter("desk", class="AppBundle:Desk",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function editAction(Request $request, Desk $desk)
    {

        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedEdit($this->getUser(),$desk)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas le droit d'editer ce bureau");
        }
        $deleteForm = $this->createDeleteForm($desk);
        $editForm = $this->createForm('AppBundle\Form\DeskType', $desk);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('desk_edit', array('id' => $desk->getId()));
        }

        return $this->render(':gestion/desk:edit.html.twig', array(
            'desk' => $desk,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param Desk $desk
     * @ParamConverter("desk", class="AppBundle:Desk",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function deleteAction(Request $request, Desk $desk)
    {

        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedEdit($this->getUser(),$desk)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas le droit d'editer ce bureau");
        }

        $form = $this->createDeleteForm($desk);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($desk);
            $em->flush($desk);

        }

        return $this->redirectToRoute('desk_index');
    }

    /**
     * Creates a form to delete a desk entity.
     *
     * @param Desk $desk The desk entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    /**
     * @param Request $request
     * @param Desk $desk
     * @ParamConverter("desk", class="AppBundle:Desk",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    private function createDeleteForm(Desk $desk)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('desk_delete', array('id' => $desk->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    private function belongingFilterDesk($desks)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        $items = array();
        foreach ($desks as $desk) {
            if($service->isGrantedEdit($this->getUser(),$desk) or $service->isGrantedUse($this->getUser(),$desk)){
                $items[] = $desk;
            }
        }
        return $items;
    }


}
