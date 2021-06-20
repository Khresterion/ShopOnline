<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HistoryController extends AbstractController
{

    #[Route('/qui-sommes-nous', name: 'history')]
    public function index(): Response
    {


        return $this->render('history/index.html.twig');
    }
}
