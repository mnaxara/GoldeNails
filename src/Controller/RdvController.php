<?php

namespace App\Controller;

use App\Entity\Rendezvous;
use App\Entity\Service;
use App\Service\AddTime;
use App\Service\Month;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RdvController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    #Fonction de création d'un rendez vous demandé sur la page de prise de rendez vous
    private function saveRdv(Request $request){
        $servicesRep = $this->em->getRepository(Service::class);
        $post = $request->request->all();
        if ((!preg_match('#^\d{1,2}[/]\d{2}[/]\d{4} à (\d{1,2}h\d{2})$#', $post['date']))||(!is_numeric($post['code'])))
        {
            $this->addFlash('danger', 'Date incorrecte');
            return $this->redirectToRoute('rdv');
        }
        elseif (empty($post['adress']))
        {
            $this->addFlash('danger', 'Entrer une adresse');
            return $this->redirectToRoute('rdv');
        }
        else
        {
            $rdv = New Rendezvous();
            $user = $this->getUser();
            $date = $post['date'];
            $duration = new \DateTime('1970-01-01 00:00:00');
            $code = $post['code'];
            $adress = strip_tags($post['adress']);

            $services = $post['services'];
            foreach ($services as $service) {
                $serviceFound = $servicesRep->findOneByShortName($service);
                $time = $serviceFound->getDuration();
                $addT = new AddTime();
                $add = $addT->addtime($duration, $time);
                $duration = new \DateTime('1970-01-01 '.$add);
                $rdv->addService($serviceFound);

            }
            $rdv->setUser($user);
            $rdv->setDateCode($code);
            $rdv->setDate($date);
            $rdv->setDuration($duration);
            $rdv->setAdress($adress);
            $rdv->setValidation(false);
            $this->em->persist($rdv);
            $this->em->flush();

            $this->addFlash('success', 'Rendez vous pris en compte ! Merci !');
            return $this->redirectToRoute('rdv');
        }
    }

    /**
     * @Route("/rdv", name="rdv")
     */
    public function index(Request $request)
    {
        $servicesRep = $this->em->getRepository(Service::class);
        $rdvRep = $this->em->getRepository(Rendezvous::class);


        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Appel fonction sauvegarde si il y a une requete
        if ($request->request->all()){
            $this->saveRdv($request);
        }

        /* Récupération du Jour et Mois en cours (en lettre et en chiffre)
         * Récupération des horaires deja réservé
         */
        $today = new \DateTime('now');
        $Month = new Month($today);
        $data = $Month->getMonthData();
        $month = $Month->getMonthName();
        $monthNumber = ($today)->format('m');
        $todayDay = intval($today->format('d'));
        $year = $Month->getYear();
        $takenReqs = $rdvRep->getRdvByMonth($monthNumber, true);
        $takenRdv = [];
        foreach ($takenReqs as $takenReq) {
            $takenRdv[] = $takenReq['dateCode'];
        }

        $services = $servicesRep->findAll();

        return $this->render('rdv/index.html.twig', [
            'data' => $data,
            'monthNumber' => $monthNumber,
            'month' => $month,
            'year' => $year,
            'services' => $services,
            'takenRdv' => $takenRdv,
            'today' => $todayDay,
        ]);
    }
}
