<?php

namespace App\Controller;

use App\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    /**
     * @Route("/service", name="service")
     */
    public function index()
    {
        $serviceRep = $this->getDoctrine()->getRepository(Service::class);
        $services = $serviceRep->findAll();
        return $this->render('service/index.html.twig', [
            'services' => $services,
        ]);
    }
}
