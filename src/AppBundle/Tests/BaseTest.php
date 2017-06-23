<?php
/**
 * Created by PhpStorm.
 * User: tux
 * Date: 12/05/17
 * Time: 14:41
 */

namespace AppBundle\Tests;
use AppBundle\Controller\utilisation\DetailElectionDeskController;
use AppBundle\Entity\DetailElectionDesk;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class BaseTest extends WebTestCase
{


    private $user = null;

    /** @var $client  \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $doctrine = null;
    private $client = null;
    private $manager = null;

    private function getKernel()
    {

        $kernel = $this->createKernel();
        $kernel->boot();
        return $kernel;
    }


    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $kernel = $this->getKernel();
        $this->setContainer($kernel->getContainer());
    }

    public function setContainer(ContainerInterface $container){
        $this->container = $container;
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry|object
     */
    protected function getDoctrine(){

        if($this->doctrine==null){
            $this->doctrine = $this->getContainer()->get('doctrine');
        }
        return $this->doctrine;
    }

    protected function getManager(){
        if($this->manager==null){
            $this->manager = $this->getDoctrine()->getManager();
        }
        return $this->manager;
    }

    /**
     * @param string $route
     * @param array $args
     */
    protected function request($route,$args = array(),$status){
        $url = $this->getContainer()->get('router')->generate($route,$args);
        $crawler = $this->client->request('GET', $url,$args);
        if($this->client->getResponse()->getStatusCode()==500){
            $html = $this->client->getResponse()->getContent();
            $crawler = new Crawler($html);
            $crawler= $crawler->filterXPath('//div[@class="text-exception"]');
            dump($crawler->html());

        }



        $this->assertEquals($status, $this->client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET ".$url);
        return $this->client->getResponse()->getContent();
    }


    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected function getContainer(){
        return $this->getKernel()->getContainer();
    }

    protected function get($x){
        return $this->getContainer()->get($x);
    }


    protected function createUserAction() {

        $email = "mail".$this->generateRandomString();
        $password = "pass".$this->generateRandomString();
        $name = "name".$this->generateRandomString();
        $factory = $this->get('security.encoder_factory');

        $user = $this->user;
        if($user === null){
            $user = new User();
        }

        $encoder = $factory->getEncoder($user);
        $user->setSalt(md5(time()));
        $user->setUsername($name);
        $pass = $encoder->encodePassword($password, $user->getSalt());
        $user->setEmail($email);
        $user->setPassword($pass);
        $user->setEnabled(true); //enable or disable

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($user);
        $em->flush();
        return $user;
    }



    /**
     * @param array $roles
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function generateClient(){

        $client = static::createClient();
        $container = $client->getContainer();

        $session = $container->get('session');
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $container->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $container->get('fos_user.security.login_manager');
        $firewallName = $container->getParameter('fos_user.firewall_name');


        $client = static::createClient();
        $container = $client->getContainer();

        $session = $container->get('session');
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $container->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $container->get('fos_user.security.login_manager');
        $firewallName = $container->getParameter('fos_user.firewall_name');

        if($this->user === null) {
            $user = $this->createUserAction();
            $this->getManager()->persist($user);
            $this->getManager()->flush();
            foreach (array("ROLE_ADMIN", "ROLE_BUREAU", "ROLE_MAIRIE", "ROLE_TOURISTE") as $role) {
                //$user->removeRole($role);
            }
            $user->setTownHalls(array());
            $user->setQgs(array());
            $user->setDesks(array());
            $this->getManager()->persist($user);
            $this->getManager()->flush();
            $this->user = $user;
        }
        $user = $this->user;
        $this->getManager()->persist($user);
        $this->getManager()->flush();
        $loginManager->loginUser($firewallName, $user);

        // save the login token into the session and put it in a cookie
        $container->get('session')->set('_security_' . $firewallName,
            serialize($container->get('security.token_storage')->getToken()));
        $container->get('session')->save();
        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));
        $this->client = $client;
        return $client;
    }

    function removeUser(){
        $this->getManager()->remove($this->user);
        $this->getManager()->flush();
    }

    protected function getCurrentUser(){
        return $this->user;
    }



    /**
     * @return null
     * @deprecated
     */
    protected function getCurentUser(){
        return $this->user;
    }


    /**
     * @return Crawler
     */
    protected function getCurrentClient(){
        return $this->client;
    }

    /**
     * @param null $client
     * @return BaseTest
     */
    public function setCurrentClient($client)
    {
        $this->client = $client;
        return $this;
    }



    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}