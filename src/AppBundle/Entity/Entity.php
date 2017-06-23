<?php
/**
 * Created by PhpStorm.
 * User: tux
 * Date: 12/05/17
 * Time: 14:52
 */

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

class Entity
{

    function __construct()
    {

    }



    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;



    /*
    * data for the view, do not store in the database
    */
    public $data;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Entity
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return Entity
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function arrayToArrayCollection($array){
        $arrayCollection = new ArrayCollection();
        foreach ($array as $item){
            $arrayCollection[] = $item;
        }
        return $arrayCollection;
    }


}