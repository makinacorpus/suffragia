<?php



namespace AppBundle\Service;

use AppBundle\Entity\Desk;
use AppBundle\Entity\Candidat;
use AppBundle\Entity\DetailElectionDesk;
use AppBundle\Entity\DetailElectionTownHall;
use AppBundle\Entity\Election;
use AppBundle\Entity\Entity;
use AppBundle\Entity\MetaElection;
use AppBundle\Entity\Parti;
use AppBundle\Entity\Qg;
use AppBundle\Entity\TownHall;
use AppBundle\Entity\PackVote;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\Proxy;

class SecurityService
{

    public function isGranted(User $user, $action, $object)
    {
        switch ($action) {
            case "EDIT":
                return $this->isGrantedEdit($user,$object);
                break;
            case "USE":
                return $this->isGrantedUse($user,$object);
                break;
            default:
                throw new \Exception("action inconnue");
        }
    }


    private function returnIsAdmin(User $user = null){
        if($user == null){
            return false;
        }
        if ($user->hasRole("ROLE_ADMIN")) {
            return true;
        }
        return false;
    }


    public function isGrantedCreate(User $user,$objectClass)
    {
        switch ($objectClass) {

            case Candidat::class:
                /** @var Candidat $object */
                return $this->returnIsAdmin($user);
            case MetaElection::class:
                return $this->returnIsAdmin($user);
            case Election::class:
                return $this->returnIsAdmin($user);
            case Parti::class:
                return $this->returnIsAdmin($user);
            case TownHall::class:
                return $this->returnIsAdmin($user);
            case Qg::class:
                if ($user->hasRole("ROLE_MAIRIE") and count($user->getTownHalls())>0) {
                    return true;
                }
                return false;

            case Desk::class:
                if ($user->hasRole("ROLE_QG") and count($user->getQgs())>0) {
                    return true;
                }
                return false;
        }
    }





    public function isGrantedView(User $user = null, Entity $object)
    {
        if($this->returnIsAdmin($user)){
            return true;
        }


        $roles = array("ROLE_ADMIN","ROLE_DESK","ROLE_QG","ROLE_BUREAU","ROLE_TOURISTE");
        switch (self::getRealClass(get_class($object))) {

            case DetailElectionDesk::class:
                /** @var DetailElectionDesk $object */

                if($object->getDetailElectionTownHall()->getState()!==DetailElectionTownHall::STATE_PRIVATE){
                    return true;
                }
                if($user === null){
                    return false;
                }
                foreach ($roles as $r) {
                    if ($user->hasRole($r) ){
                        return true;
                    }
                }
                return false;
            case DetailElectionTownHall::class:
                /** @var DetailElectionTownHall $object */
                if($user === null){
                    if($object->getState()===DetailElectionTownHall::STATE_PRIVATE){
                        return false;
                    }
                    return true;
                }
                foreach ($roles as $r) {
                    if ($user->isGranted($r) ){
                        return true;
                     }
                }
                return false;
        }
    }


    public function isGrantedUse(User $user, Entity $object)
    {
        if($this->returnIsAdmin($user)){
            return true;
        }

        switch (self::getRealClass(get_class($object))) {
            case TownHall::class:
                if (in_array($object,$user->getTownHalls())) {
                    return true;
                }
                return false;

            case Qg::class:
                if (in_array($object,$user->getQgs())) {
                    return true;
                }
                return false;

            case Desk::class:
                if (in_array($object,$user->getDesks())) {
                    return true;
                }
                return false;

            case DetailElectionDesk::class:
                /** @var DetailElectionDesk $object */
                if (in_array($object->getDesk(),$user->getDesks())) {
                    if($object->getState() === DetailElectionDesk::STATE_PROGRESS and $object->getDetailElectionTownHall()->getState() !==DetailElectionTownHall::STATE_FINICHED){
                        return true;
                    }
                }
                if (in_array($object->getDesk()->getQg(),$user->getQgs())) {//a qg can vote for this desks
                    if($object->getState() === DetailElectionDesk::STATE_PROGRESS and $object->getDetailElectionTownHall()->getState() !==DetailElectionTownHall::STATE_FINICHED){
                        return true;
                    }
                }
                return false;

            case DetailElectionTownHall::class:
                /** @var DetailElectionTownHall $object */
                if (in_array($object->getTownHall(),$user->getTownHalls())) {
                        return true;
                }
                return false;

            default:
                throw new \Exception("class inconnue");
        }
    }


    public function isGrantedEdit(User $user,$object)
    {
        if($this->returnIsAdmin($user)){
            return true;
        }

        $roleAdmin = "ROLE_ADMIN";
        switch (self::getRealClass(get_class($object))) {

            case TownHall::class:
                if ($user->hasRole($roleAdmin)) {
                    return true;
                }
                return false;break;

            case Election::class:
                if ($user->hasRole($roleAdmin)) {
                    return true;
                }
                return false;break;
            case Parti::class:
                if ($user->hasRole($roleAdmin)) {
                    return true;
                }
                return false;break;
            case MetaElection::class:
                if ($user->hasRole($roleAdmin)) {
                    return true;
                }
                return false;break;

            case Candidat::class:
                if ($user->hasRole($roleAdmin)) {
                    return true;
                }
                return false;break;
            case User::class:
                /** @var User $object */

            if($object->getOwner() == null){//admin
                    return false;
                }
                if(in_array($object->getOwner(),$user->getTownHalls())){
                    return true;
                }
                return false;

            case Qg::class:
                foreach ($user->getTownHalls() as $townHall) {
                    if (in_array($object, $townHall->getQgs())) {
                        return true;
                    }
                }
                return false;

            case Desk::class:
                foreach ($user->getQgs() as $qg) {
                    if (in_array($object, $qg->getDesks())) {
                        return true;
                    }
                }
                return false;

            default:
                throw new \Exception(TownHall::class."class inconnue".get_class($object));

        }
    }


    public function checkUrl($pathNextStep,array $routeAllowed,array $args){
        $allowed = false;
        $valuesAllowed = array('election','desk','desk','townHall',"qg");
        $keysAllowed = array('election_id','desk_id');
        if(in_array($pathNextStep,$routeAllowed)){
            $allowed = true;
            foreach ($args as $key=>$value){
                if(!in_array($key,$keysAllowed) and !is_int($key) ){
                    $allowed = false;
                }
                if(!in_array($value,$valuesAllowed) ){
                    $allowed = false;
                }
            }


        }
        return true;


    }


    /**
     * Gets the real class name of a class name that could be a proxy.
     *
     * @param string $class
     *
     * @return string
     */
    public static function getRealClass($class)
    {
        if (false === $pos = strrpos($class, '\\'.Proxy::MARKER.'\\')) {
            return $class;
        }

        return substr($class, $pos + Proxy::MARKER_LENGTH + 2);
    }



}
