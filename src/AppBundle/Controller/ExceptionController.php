<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Advert;
use AppBundle\Entity\Vote;

use AppBundle\Entity\Candidat;
use AppBundle\Entity\packVote;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionController extends Controller
{
    
    public function showExceptionAction()
    {

        return $this->redirectToRoute("fos_user_security_login");
    }
}
