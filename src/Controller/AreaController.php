<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AreaController extends AbstractController
{
    /**
     * @Route("/area", name="area")
     */
    public function index()
    {
        return $this->render('area/index.html.twig', [
            'controller_name' => 'AreaController',
        ]);
    }
}
