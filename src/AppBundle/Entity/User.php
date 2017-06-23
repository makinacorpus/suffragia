<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TownHall",cascade={"persist"})
     */
    private $owner;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getNom(){
        return $this->username;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    private function findIndice($array,$elem){
        $i=0;
        foreach ($array as $item){
            if($item===$elem){
                return $i;
            }
            $i++;
        }
        return null;
    }

    public function __construct()
    {
        parent::__construct();

        $this->deskx = new ArrayCollection();
        $this->qgs = new ArrayCollection();
        $this->townHalls = new ArrayCollection();
        $this->owner = null;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     * @return User
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }




    /**
     * @return mixed
     */
    public function removeAllDeskx()
    {
         $this->deskx = new ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function removeAllQgs()
    {
         $this->qgs = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function removeAllTownHalls()
    {
         $this->townHalls = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getDeskx()
    {

        return $this->deskx->toArray();
    }

    /**
     * @return Desk[]
     */
    public function getDesks()
    {

        return $this->deskx->toArray();
    }

    /**
     * @param mixed $desk
     */
    public function addDesk($desk)
    {
        if(!in_array($desk,$this->getDeskx())){
            $this->deskx[] = $desk;
        }
    }

    /**
     * @param mixed $desk
     */
    public function removeDesk($desk)
    {
        if(in_array($desk,$this->getDeskx())){
            unset($this->getQgs()[$desk]);
        }
    }


    /**
     * @return Qg[]
     */
    public function getQgs()
    {
        return $this->qgs->toArray();
    }

    /**
     * @param mixed $qg
     */
    public function addQg($qg)
    {
        if(!in_array($qg,$this->getQgs())){
            $this->qgs[] = $qg;
        }
    }

    /**
     * @param mixed $qg
     */
    public function removeQg($qg)
    {
        if(in_array($qg,$this->getQgs())){
            unset($this->getQgs()[$qg]);
        }
    }


    /**
     * @return TownHall[]
     */
    public function getTownHalls()
    {
        return $this->townHalls->toArray();
    }

    /**
     * @param mixed $townHall
     */
    public function addTownHall($townHall)
    {
        if(!in_array($townHall,$this->getTownHalls())){
            $this->townHalls[] = $townHall;
        }
    }


    public function setTownHalls($townHalls)
    {
            $this->townHalls = $townHalls;
    }


    /**
     * @param mixed $townHall
     */
    public function removeTownHall($townHall)
    {
        if(in_array($townHall,$this->getTownHalls())){
            unset($this->townHalls[$townHall]);
        }

    }


    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Desk",cascade={"persist"})
     */
    protected $deskx;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Qg",cascade={"persist"})
     */
    protected $qgs;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\TownHall",cascade={"persist"})
     */
    protected $townHalls;


    public function getRoles()
    {
        return $this->roles;
    }

    public function isGranted($role)
    {
        return in_array($role,$this->getRoles());
    }

    /**
     * @param mixed $deskx
     * @return User
     */
    public function setDeskx($deskx)
    {
        $this->deskx = $deskx;
        return $this;
    }

    /**
     * @param mixed $deskx
     * @return User
     */
    public function setDesks($deskx)
    {
        $this->deskx = $deskx;
        return $this;
    }

    /**
     * @param mixed $qgs
     * @return User
     */
    public function setQgs($qgs)
    {
        $this->qgs = $qgs;
        return $this;
    }



}