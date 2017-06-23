<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TownHallControllerTest extends WebTestCase
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



        // Create a new entry in the database
        $crawler = $client->request('GET', '/gestion/townHall/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /townHall/");
        $crawler = $client->click($crawler->selectLink('Creer une nouvelle townHall')->link());

        // Fill in the form and submit it
        $nom =  'DeskTest2'+rand();
        $form = $crawler->selectButton('Creer')->form(array(
            'appbundle_townHall[nom]'  => $nom,
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();
        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("'.$nom.'")')->count(), 'Missing element td:contains("'.$nom.'")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Editer')->link());
        $form = $crawler->selectButton('Editer')->form(array(
            'appbundle_townHall[nom]'  => $nom."2",
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="'.$nom."2".'"]')->count(), 'Missing element [value="Foo"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Supprimer')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/'.$nom."2".'/', $client->getResponse()->getContent());
    }


}
