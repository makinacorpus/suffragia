<?php



namespace AppBundle\Service;

use AppBundle\Entity\Desk;
use AppBundle\Entity\Candidat;
use AppBundle\Entity\DetailElectionDesk;
use AppBundle\Entity\DetailElectionTownHall;
use AppBundle\Entity\Election;
use AppBundle\Entity\MetaElection;
use AppBundle\Entity\Parti;
use AppBundle\Entity\TownHall;
use AppBundle\Entity\PackHoraire;
use Doctrine\ORM\EntityManager;

class ElectionService
{

function getColorParti(Parti $parti)
{
    $colors = array(
        "ANAR" => "#000000",
        "EXG" => "#BB0000",
        "LO" => "#BB0000",
        "NPA" => "#BB0000",
        "FG" => "#DD0000",
        "PCF" => "#DD0000",
        "COM" => "#DD0000",
        "PG" => "#DD0000",
        "MRC" => "#CC6666",
        "ND" => "#CC6666",
        "VEC" => "#00C000",
        "EELV" => "#00C000",
        "CAP" => "#77FF77",
        "DVE" => "#77FF77",
        "PS" => "#FF8080",
        "SOC" => "#FF8080",
        "PRG" => "#FFD1DC",
        "RDG" => "#FFD1DC",
        "DVG" => "#FFC0C0",
        "DVG2" => "#FFC0C0",
        "MDM" => "#FF9900",
        "UC" => "#74C2C3",
        "NC" => "#00FFFF",
        "UDI" => "#00FFFF",
        "DVD" => "#ADC1FD",
        "DVD2" => "#ADC1FD",
        "UMP" => "#0066CC",
        "PR" => "#0066CC",
        "DLR" => "#8040C0",
        "MPF" => "#8040C0",
        "PP" => "#8040C0",
        "FN" => "#C0C0C0",
        "SP" => "#C0C0C0",
        "EXD" => "#404040",
        "DIV" => "#F0F0F0",
        "DIV2" => "#F0F0F0",
        "DLF" => "#CCC",
        "UD" => "#ADC1FD",
        "UG" => "#FFC0C0",
        "egal" => "#FFFFFF",
        "BLANC" => "#FFFFFF",
        "Null" => "#FFFFFF",
        "LR"=>"#0066CC",
        "ECO"=>"#77FF77",
        "REM"=>"#B6D98D",
        "FI"=>"#DD0000",
        "SANS_ETIQUETTE"=>"##F0F0F0"
    );

    if(array_key_exists(strtoupper($parti->getName()),$colors)){
        return $colors[strtoupper($parti->getName())];
    }
    return "#FF3300";


}



    /**
     * ElectionService constructor.
     */
    public function __construct(TownHallService $townHallService,EntityManager $em)
    {
        $this ->townHallService = $townHallService;
        $this->em = $em;
    }



    public function count($liste){
        $i=0;
        foreach ($liste as $i){
            $i++;
        }
        return $i;
    }



    /**
     *Retur all DetailElectionDesk link to the election
     * @return DetailElectionDesk[]
     */
    public function getDetailsElectionDesk(Election $election){
        $detailsElectionDesk = array();
        foreach ($election->getDetailsElectionTownHall() as $detailElectionTownHall){
            foreach ($detailElectionTownHall->getDetailsElectionDesk() as $detailElectionDesk){
                $detailsElectionDesk[] = $detailElectionDesk;
            }
        }
        return $detailsElectionDesk;
    }

    public function getPacksVote(Election $election){
        $packVote = array();
        foreach ($this->getDetailsElectionDesk($election) as $detailElectionDesk){
           $packVote= array_merge($packVote,$detailElectionDesk->getPacksVote());
        }

        return $packVote;
    }

    /**
     *Retur all packHoraires link to the election
     * @return PackHoraire[]
     */
    public function getPacksHoraire(Election $election){
        $packHoraires = array();

        foreach ($this->getDetailsElectionDesk($election) as $detailElectionDesk){
            $packs = ($detailElectionDesk->getPacksHoraire());
            $packHoraires= array_merge($packHoraires,$packs);
        }
        return $packHoraires;
    }



    public function importCandidat(TownHall $townHall,Election $election){
        if($election->getMetaElection()->getType() !== MetaElection::TYPE_LEGISLATIVE or $election->getMetaElection()->getDate()->format("Y")!=="2017"){
            return;
        }
        return;//TODO change the link
        $circonscription = $townHall->getCirconscription();
        $numDepartement = $townHall->getNumeroDepartement();
        $link = "https://public.opendatasoft.com/api/records/1.0/search/?dataset=elections-legislatives-11-18-juin-2017-candidats-1er-tour&rows=300&facet=libelle_du_departement&facet=libelle_circonscription&facet=sexe_candidat&facet=nuance_candidat&facet=profession_candidat&facet=le_candidat_est_sortant&refine.code_du_departement=".$numDepartement."&refine.code_circonscription=".$circonscription;
        $json = file_get_contents($link);
        $JSON = json_decode($json);


        foreach ($JSON->records as $row){
            $candidat = new Candidat();

            foreach ($row->fields as $key=>$c){
                if($key === "nom_candidat"){
                    $candidat->setName($c);
                }
                if($key === "prenom_candidat"){
                    $candidat->setName($c." ".$candidat->getName());
                }
                if($key === "nuance_candidat"){
                    $parti = $this->em->getRepository('AppBundle:Parti')
                        ->findOneBy(array('name' => $c));
                    if($parti == null){
                        $parti = new Parti();
                        if($c===""){
                            $c= "SANS_ETIQUETTE";
                        }
                        $parti->setName($c);
                        $parti->setColor($this->getColorParti($parti));
                        $candidat->setParti($parti);
                        $this->em->persist($parti);
                    }
                    else{
                        $candidat->setParti($parti);
                    }


                }

            }

            $candidatBis = $this->em->getRepository('AppBundle:Candidat')
                ->findOneBy(array('name' => $candidat->getName()));
            if($candidatBis != null){
                $candidat = $candidatBis;
            }

            $existe = false;

            foreach ($election->getCandidats() as $c){
                if($c->getName() == $candidat->getName()){
                    $existe=true;
                }
            }
            if($existe !== true) {
                $candidat->setElection($election);
                $election->addCandidat($candidat);

                $this->em->persist($candidat);
                $this->em->flush();
            }
        }
        return null;



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





    /**
     * @param Election
     * @param  TownHall
     * @return Election
     */
    public function registerTownHallToElection(Election $election,TownHall $townHall){

        $monDetail  =null;
        foreach( $townHall->getDetailsElectionTownHall() as $detail){
            if($detail->getElection()===$election){
                $monDetail=$detail;
            }
        }

        if($monDetail===null) {
            $dm = new DetailElectionTownHall();
            $dm->setElection($election);
            $dm->setTownHall($townHall);
            $townHall->addDetailElectionTownHall($dm);
            $monDetail=$dm;
            $election->addDetailsElectionTownHall($dm);


        }
        $dm = $monDetail;

        foreach ($this->townHallService->getDeskx($townHall) as $b){
                $existe = false;
                foreach ($monDetail->getDetailsElectionDesk() as $deb) {
                    if($deb->getDesk()===$b) {
                        $existe = true;
                    }
                }
                if (!$existe) {
                    $db= new DetailElectionDesk();
                    $db->setDesk($b);
                    $db->setDetailElectionTownHall($dm);
                    $dm->addDetailElectionDesk($db);

                }
            }
        $this->em->flush();

        return $election;
    }

}