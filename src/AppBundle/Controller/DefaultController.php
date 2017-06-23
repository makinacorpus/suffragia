<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {

        return $this->redirectToRoute('visiteur_index');
        $repositoryPackVote = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:TownHall');
        // On récupère l'entité correspondante à l'id $id
        $townHalls = $repositoryPackVote->findAll();





        return $this->render(':utilisation/Visiteur:index.html.twig',array('townHalls'=>$townHalls));

    }
}
