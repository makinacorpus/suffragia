<?php



namespace AppBundle\Service;

use AppBundle\Entity\Concrete;
use AppBundle\Entity\Desk;
use AppBundle\Entity\Candidat;
use AppBundle\Entity\Election;
use AppBundle\Entity\Entity;
use AppBundle\Entity\TownHall;
use AppBundle\Entity\PackVote;
use AppBundle\Entity\Qg;
use AppBundle\Entity\User;

class UserService
{

    protected $em;

    /**
     * UserService constructor.
     * @param $em
     */
    public function __construct(\Doctrine\ORM\Entitymanager $em)
    {
        $this->em = $em;
    }


    public function getTownHall(User $user){
        foreach ($user->getTownHalls() as $item){
            return $item;
        }
        foreach ($user->getQgs() as $item){
            return $item->getTownHall();
        }
        foreach ($user->getDesks() as $item){
            return $item->getQg()->getTownHall();
        }
        return null;
    }



    public function removeRightQg(User $user)
    {
        if ($user->hasRole("ROLE_QG")) {
            $user->removeRole("ROLE_QG");
        }
        $user->removeAllQgs();



        $this->em->persist($user);
        $this->em->flush();
    }


    public function removeRightTouriste(User $user)
    {
        if ($user->hasRole("ROLE_TOURISTE")) {
            $user->removeRole("ROLE_TOURISTE");
        }
        $this->em->persist($user);
        $this->em->flush();
    }

    public function removeRightAdmin(User $user)
    {
        if ($user->hasRole("ROLE_ADMIN")) {
            $user->removeRole("ROLE_ADMIN");
        }
        $this->em->persist($user);
        $this->em->flush();
    }

    public function removeRightTownHall(User $user)
    {
        if ($user->hasRole("ROLE_MAIRIE")) {
            $user->removeRole("ROLE_MAIRIE");
        }
        $user->removeAllTownHalls();

        $this->em->persist($user);
        $this->em->flush();
    }


    public function removeRightDesk(User $user)
    {
        if ($user->hasRole("ROLE_BUREAU")) {
            $user->removeRole("ROLE_BUREAU");
        }
        $user->removeAllDeskx();
        $this->em->persist($user);
        $this->em->flush();

    }

    public function setEm($em){
        $this->em = $em;

    }

    public function manageRightQg(User $user, Qg $qg, $removeAuther = true)
    {
        if (!$user->hasRole("ROLE_QG")) {
            $user->addRole("ROLE_QG");
        }
        if ($removeAuther) {

            $user->removeAllQgs();
        }
        $user->addQg($qg);
        $this->em->persist($user);
        $this->em->flush();

    }


    public function manageRightTownHall(User $user, TownHall $townHall, $removeAuther = true)
    {
        if (!$user->hasRole("ROLE_MAIRIE")) {
            $user->addRole("ROLE_MAIRIE");
        }
        if ($removeAuther) {
            $user->removeAllTownHalls();
        }

        $user->addTownHall($townHall);
        $this->em->persist($user);
        $this->em->flush();
    }


    public function manageRightTouriste(User $user)
    {
        if (!$user->hasRole("ROLE_TOURISTE")) {
            $user->addRole("ROLE_TOURISTE");
        }
        $this->em->persist($user);
        $this->em->flush();
    }

    public function manageRightAdmin(User $user)
    {
        if (!$user->hasRole("ROLE_ADMIN")) {
            $user->addRole("ROLE_ADMIN");
        }
        $this->em->persist($user);
        $this->em->flush();
    }

    public function manageRightDesk(User $user, Desk $desk, $removeAuther = true)
    {
        if (!$user->hasRole("ROLE_BUREAU")) {
            $user->addRole("ROLE_BUREAU");
        }
        if ($removeAuther) {
            $user->removeAllDeskx();
        }
        $user->addDesk($desk);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function removeUser(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }




}


