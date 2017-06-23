<?php
/**
 * Created by PhpStorm.
 * User: tda
 * Date: 11/04/17
 * Time: 17:25
 */

namespace AppBundle\Tests\Controller;
use AppBundle\Entity\Desk;
use AppBundle\Entity\Parti;
use AppBundle\Entity\Candidat;
use AppBundle\Entity\TownHall;
use AppBundle\Entity\Election;
use AppBundle\Entity\PackVote;
use AppBundle\Entity\Qg;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\DomCrawler\Crawler;
class PackVoteControllerTest extends WebTestCase
{
    public function testCompleteScenario()
    {
      $client = static::createClient();
      $container = $client->getContainer();
      $session = $container->get('session');
      /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
      $userManager = $container->get('fos_user.user_manager');
      /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
      $loginManager = $container->get('fos_user.security.login_manager');
      $firewallName = $container->getParameter('fos_user.firewall_name');

      $user = $userManager->findUserBy(array('username' => 'test'));
      $loginManager->loginUser($firewallName, $user);

      // save the login token into the session and put it in a cookie
      $container->get('session')->set('_security_' . $firewallName,
          serialize($container->get('security.token_storage')->getToken()));
      $container->get('session')->save();
      $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));


      $kernelNameClass = $this->getKernelClass(); // Récupération du nom de la classe qui sert de kernel
      $kernel = new $kernelNameClass('test', true); // Instanciation de la classe et exécution du kernel dans un environnement de test avec débogage
      $kernel->boot(); // On boot le kernel (comme un pc ^^)
      $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');


        $em->flush();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/desk/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /desk/");
        $value = $crawler->filter('option:contains("voteTest")')->attr('value');
        $form = $crawler->filter('form')->form();
        $form["choixDesk"]->select($value);
        $crawler = $client->followRedirect();







        $crawler = $client->click($crawler->selectLink('Creer un nouveau desk')->link());


        $classes = array("TownHall","Qg","Candidat","Parti","Desk","Election");

        foreach ($classes as $c) {
            $repository2 = static::$kernel->getContainer()
                ->get('doctrine')
                ->getManager()
                ->getRepository('AppBundle:'.$c);

            $items = $repository2->findBy(array('nom' => "voteTest"));
            foreach ($items as $i) {
                echo $c;
                $em->remove($i);
                $em->flush();

            }

        }

}
    
}
