<?php

namespace App\Controller;

use App\Entity\Gallery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    /**
     * @Route("/gallery", name="gallery")
     */
    public function index()
    {
        $galleryRep = $this->getDoctrine()->getRepository(Gallery::class);
        $gallery = $galleryRep->findAll();
        return $this->render('gallery/index.html.twig', [
            'gallery' => $gallery,
        ]);
    }
}
