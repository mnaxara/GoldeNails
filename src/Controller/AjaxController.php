<?php

namespace App\Controller;

use App\Entity\Rendezvous;
use App\Entity\Service;
use App\Form\ServiceAdminFormType;
use App\Form\ServiceFormType;
use App\Service\Month;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
    private $servicesRep;
    private $rdvRep;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->servicesRep = $em->getRepository(Service::class);
        $this->rdvRep = $em->getRepository(Rendezvous::class);
        $this->em = $em;
    }

    /**
     * @Route("/ajax/month", name="monthAjax")
     */

    /*
     * Fonction qui permet de récupérer les données du mois demandé lors du changement avec les flèches
     */
    public function changeMonth(Request $request)
    {

        $number = $request->request->get('number');
        $year = $request->request->get('year');

        $newDate = \DateTime::createFromFormat('n-Y', $number.'-'.$year);
        $Month = new Month($newDate);
        $data = $Month->getMonthData();
        $month = $Month->getMonthName();
        $monthNumber = $Month->getMonth();
        $year = $Month->getYear();
        $takenReqs = $this->rdvRep->getRdvByMonth($monthNumber, true);
        $takenRdv = [];

        foreach ($takenReqs as $takenReq) {
            $takenRdv[] = $takenReq['dateCode'];
        }

        $services = $this->servicesRep->findAll();
        $today = new \DateTime('now');
        $today = intval($today->format('d'));
        $todayMonth = new \DateTime('now');
        $todayMonth = intval($todayMonth->format('m'));

        return $this->render('ajax/month.html.twig',
            [
                'data' => $data,
                'monthNumber' => $number,
                'month' => $month,
                'year' => $year,
                'services' => $services,
                'takenRdv' => $takenRdv,
                'today' => $today,
                'todayM' => $todayMonth,
            ]);


    }

    /**
     * @Route("/ajax/agenda", name="agendaAjax")
     */

    /*
    * Fonction qui permet de récupérer l'agenda à jour, lors du clic d'"Agenda" du menu Adminisrateur
    */

    public function getAgenda()
    {
        $Month = new Month(new \DateTime('now'));
        $data = $Month->getMonthData();
        $month = $Month->getMonthName();
        $monthNumber = (new \DateTime('now'))->format('m');
        $year = $Month->getYear();
        $takenReqs = $this->rdvRep->getRdvByMonth($monthNumber, true);
        $takenRdv = [];
        foreach ($takenReqs as $takenReq) {
            $takenRdv[] = $takenReq['dateCode'];
        }
        $submitReqs = $this->rdvRep->getRdvByMonth($monthNumber, false);

        $services = $this->servicesRep->findAll();


        return $this->render('ajax/agenda.html.twig',
            [
                'data' => $data,
                'monthNumber' => $monthNumber,
                'month' => $month,
                'year' => $year,
                'services' => $services,
                'takenRdv' => $takenRdv,
                'submitRdvs' => $submitReqs,
            ]);
    }

    /**
     * @Route("/ajax/rdv", name="rdvAjax")
     */

    /*
    * Fonction qui permet de gérer l'annulation ou la confirmation d'un rendez-vous posé par un client
    */

    public function getRdv(Request $request)
    {
        $id = $request->request->get('id');
        $type = $request->request->get('type');
        $rdv = $this->rdvRep->find($id);

        if ($type === 'valid')
        {
            dump($rdv);
            $rdv->setValidation(true);
            $this->em->flush();
            return new Response('Rendez vous confirmé');

        }
        elseif ($type === 'cancel')
        {
            $this->em->remove($rdv);
            $this->em->flush();
            return new Response('Rendez vous annulé');

        }
        else
        {
            return new Response('Erreur lors de la confirmation/annulation');
        }

    }

    /**
     * @Route("/ajax/service", name="serviceAjax")
     */

    /*
    * Fonction qui permet de récupérer les services, lors du clic de "Services" du menu Adminisrateur
    */

    public function serviceList()
    {
        $services = $this->servicesRep->findAll();

        return $this->render('ajax/services.html.twig',
            [
                'services' => $services
            ]);
    }

    /**
     * @Route("/ajax/service/update/{id}", name="serviceUpdateAjax", requirements={"id"="\d+"})
     */

    /*
    * Fonction qui permet de mettre à jour un service via le menu administrateur "Service"
    */

    public function serviceUpdate(Service $service, Request $request)
    {
        $form = $this->createForm(ServiceAdminFormType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){
            $service = $form->getData();
            $this->em->flush();
        }

        return $this->render('ajax/serviceUpdate.html.twig',
            [
                'service' => $service,
                'form' => $form->createView(),
                'action' => 'Modification'
            ]);

    }

    /**
     * @Route("/ajax/service/delete/{id}", name="serviceDeleteAjax", requirements={"id"="\d+"})
     */

    /*
    * Fonction qui permet de supprimer un service via le menu administrateur "Service"
    */

    public function serviceDelete(Service $service)
    {
        $this->em->remove($service);
        $this->em->flush();

        return new Response('Service supprimé');

    }

    /**
     * @Route("/ajax/service/add", name="serviceAddAjax")
     */

    /*
    * Fonction qui permet d'ajouter un service via le menu administrateur "Service"
    */
    public function serviceAdd(Request $request)
    {
        $form = $this->createForm(ServiceAdminFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){
            $service = new Service();
            $service = $form->getData();
            $this->em->persist($service);
            $this->em->flush();
            return new Response('Service ajouté');
        }
        else if ($form->isSubmitted())
        {
            return new Response('formulaire invalide');
        }

        return $this->render('ajax/serviceUpdate.html.twig',
            [
                'form' => $form->createView(),
                'action' => 'Ajout'
            ]);

    }
}
