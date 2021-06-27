<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // /**
    //  * @Route("/connexion", name="app_login")
    //  */
    #[Route('/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['g-recaptcha'])) {
            } else {
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=6LcPGl0bAAAAAEKU72g-6xBzpLSYSGOhIhb0h7Y2&response={$_POST['g-recaptcha']}";
            }
            if (function_exists('curl_version')) {
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_TIMEOUT, 1);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($curl);
            } else {
                $response = file_get_contents($url);
            }

            if ($this->getUser()) {
                return $this->redirectToRoute('account');
            }
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    // /**
    //  * @Route("/déconnexion", name="app_logout")
    //  */
    #[Route('/déconnexion', name: 'app_logout')]
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
