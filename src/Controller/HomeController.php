<?php

namespace App\Controller;

use App\Class\Mail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {

        $mail = new Mail();
        $mail->send('axios.ludis@gmail.com', to_name: "Jane Doh", subject: "premier mail test", content: "Salut");

        return $this->render('home/index.html.twig');
    }
}
