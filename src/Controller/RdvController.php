<?php

namespace App\Controller;

use App\Entity\Rendezvous;
use App\Entity\Service;
use App\Repository\RendezvousRepository;
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
            $duration = date('00:00:00');
            $code = $post['code'];
            $adress = strip_tags($post['adress']);

            $services = $post['services'];
            foreach ($services as $service) {
                $serviceFound = $servicesRep->findOneByShortName($service);
                $time = $serviceFound->getDuration();
                $time = $time->format('H:i:s');

                $duration = explode(':', $duration);
                $time = explode(':', $time);

                $h = $duration[0] + $time[0];
                $m = $duration[1] + $time[1];
                $s = $duration[2] + $time[2];

                $duration = date($h.':'.$m.':'.$s);
                $rdv->addService($serviceFound);

            }

            $duration = new \DateTime($duration);

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

        // Appel fontion sauvegarde si il y a une requete
        if ($request->request->all()){
            $this->saveRdv($request);
        }

        $Month = new Month(new \DateTime('now'));
        $data = $Month->getMonthData();
        $month = $Month->getMonthName();
        $monthNumber = (new \DateTime('now'))->format('m');
        $today = new \DateTime('now');
        $today = intval($today->format('d'));
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
            'today' => $today,
        ]);
    }
}
