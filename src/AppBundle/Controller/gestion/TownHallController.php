<?php

namespace AppBundle\Controller\gestion;

use AppBundle\Entity\Candidat;
use AppBundle\Entity\Election;
use AppBundle\Entity\TownHall;
use AppBundle\Service\SecurityService;
use Metadata\Tests\Driver\Fixture\C\SubDir\C;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * TownHall controller.
 *
 */
class TownHallController extends Controller
{


    /**
     * Lists all townHall entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $townHalls = $em->getRepository('AppBundle:TownHall')->findAll();

        return $this->render(':gestion/townHall:index.html.twig', array(
            'townHalls' => $townHalls,
        ));
    }



    /**
     * Creates a new townHall entity.
     *
     */
    public function newAction(Request $request)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedCreate($this->getUser(),TownHall::class)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas de cree cette mairie");
        }


        $townHall = new TownHall();
        $form = $this->createForm('AppBundle\Form\TownHallType', $townHall);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $townHall->getLogo();//UploadedFile

            $data = file_get_contents($file);
            $base64 = 'data:image/' . $file->guessExtension() . ';base64,' . base64_encode($data);

            /*dump($base64);
            return null;
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('logo_directory'),
                $fileName
            );


            $townHall->setLogo($fileName);/*/
            $townHall->setLogo($base64);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($townHall);
            $em->flush();
            $userService =  $this->get('UserService');
            $userService->manageRightTownHall($this->getUser(),$townHall,false);

            return $this->redirectToRoute('townHall_show', array('id' => $townHall->getId()));
        }

        return $this->render('gestion/townHall/new.html.twig', array(
            'townHall' => $townHall,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param townHall $townHall
     * @ParamConverter("townHall", class="AppBundle:TownHall",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function showAction(TownHall $townHall)
    {
        $deleteForm = $this->createDeleteForm($townHall);
        $townHallService = $this->container->get('TownHallService');
        $deskx = $townHallService->getDeskx($townHall);
        return $this->render('gestion/townHall/show.html.twig', array(
            'townHall' => $townHall,
            'deskx'=>$deskx,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param TownHall $townHall
     * @ParamConverter("townHall", class="AppBundle:TownHall", options={"candidat", "id"})
     * @ParamConverter("townHall", class="AppBundle:TownHall", options={"townHall", "id"})
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function editAction(Request $request, TownHall $townHall)//toto
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedEdit($this->getUser(),$townHall)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas le droit d'editer cette mairie");
        }
        $townHall->setLogo(null);

        $deleteForm = $this->createDeleteForm($townHall);
        $editForm = $this->createForm('AppBundle\Form\TownHallType', $townHall);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $file = $townHall->getLogo();//UploadedFile

            $data = file_get_contents($file);
            $base64 = 'data:image/' . $file->guessExtension() . ';base64,' . base64_encode($data);

            /*dump($base64);
            return null;
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('logo_directory'),
                $fileName
            );


            $townHall->setLogo($fileName);/*/
            $townHall->setLogo($base64);
            $this->getDoctrine()->getManager()->persist($townHall);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('townHall_edit', array('id' => $townHall->getId()));
        }

        return $this->render('gestion/townHall/edit.html.twig', array(
            'townHall' => $townHall,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param TownHall $townHall
     * @ParamConverter("townHall", class="AppBundle:TownHall",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function deleteAction(Request $request, TownHall $townHall)
    {
        /** @var SecurityService $service */
        $service = $this->container->get('SecurityService');

        if(!$service->isGrantedEdit($this->getUser(),$townHall)){
            throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException("vous n'avez pas le droit d'editer cette mairie");
        }
        $form = $this->createDeleteForm($townHall);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($townHall);
            $em->flush($townHall);
        }

        return $this->redirectToRoute('townHall_index');
    }

    /**
     * @param Request $request
     * @param TownHall $townHall
     * @ParamConverter("townHall", class="AppBundle:TownHall",options={"id": "id"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TownHall $townHall)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('townHall_delete', array('id' => $townHall->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
