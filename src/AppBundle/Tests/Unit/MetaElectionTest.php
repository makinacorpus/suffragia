<?php
namespace AppBundle\Tests\Unit;

/**
 * Created by PhpStorm.
 * User: tux
 * Date: 14/06/17
 * Time: 14:51
 */
use AppBundle\Entity\MetaElection;
use AppBundle\Tests\BaseTest;
use Doctrine\ORM\Query\Expr\Base;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DomCrawler\Crawler;

class MetaElectionTest extends BaseTest
{


    private function getIntex(){

        /** @var Client $client */

        $client = $this->getCurrentClient();
        $url = $this->getContainer()->get('router')->generate("metaelection_index");
        $args  =array();
        // Create a new entry in the database
        $crawler = $client->request('GET',$url,$args);
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /metaelection/");

        $crawler = new Crawler();
        $crawler->addHtmlContent($client->getResponse()->getContent());
        return $crawler;

    }


    private function getShow(MetaElection $metaElection,Crawler $crawler){

        $crawler = $crawler->filter("table");
        foreach ($crawler->filter("tr") as $tr){
            $c = new Crawler();$c->add($tr);
            if($c->text() == $metaElection->getId()) {
                $crawlerShow = $this->getCurrentClient()->request('GET',$c->filter("a")->attr('href'),array());
                $this->assertEquals(200, $this->getCurrentClient()->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET show Metaelection");
                return $crawlerShow;
            }
        }
        throw new \Exception("la mairie n existe pas");
    }


    public function delete(MetaElection $metaElection){

        $crawler = $this->getIntex();
        $crawlerShow = $this->getShow($metaElection,$crawler);
        $this->getCurrentClient()->submit($crawlerShow->selectButton('Supprimer')->form());
        $crawler = $this->getCurrentClient()->followRedirect();
        $this->assertEquals(200, $this->getCurrentClient()->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET delete Metaelection");

    }


}