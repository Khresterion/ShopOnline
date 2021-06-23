<?php

namespace App\Controller;

use App\Class\Mail;
use App\Entity\User;
use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // /**
    //  * @Route("/mot-de-passe-oublie", name="reset_password")
    //  */
    #[Route('/mot-de-passe-oublie', name: 'reset_password')]
    public function index(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $request->get('email')]);

            if ($user) {
                //Enregistrer la demande de reset password avec user, token, createdAt.
                $reset_password = (new ResetPassword())
                    ->setUser($user)
                    ->setToken(uniqId())
                    ->setCreatedAt(new \DateTime());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                // Envoyer un email au user avec un lien permettant de mettre à jour le mot de passe
                $url = $this->generateUrl('update_password', [
                    'token' => $reset_password->getToken()
                ]);
                $content = "Bonjour " . $user->getFirstname() . ",<br/>Vous avez demandé à réinitialiser votre mot de passe sur le site '...'<br/><br/>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='" . $url . "'> mettre à jour votre mot de passe.</a>";
                $mail = new Mail();
                $mail->send($user->getEmail(), $user->getFullName(), 'Réinitialisation de votre mot de passe', $content);

                $this->addFlash('notice', "Vous allez recevoir un mail avec la procédure pour réinitialiser votre mot de passe");
            } else {
                $this->addFlash('notice', "Cette adresse mail est inconnue");
            }
        }

        return $this->render('reset_password/index.html.twig');
    }

    // /**
    //  * @Route("/modifier-mon-mot-de-passe/{token}", name="update_password")
    //  */
    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'update_password')]
    public function update(Request $request, $token, UserPasswordHasherInterface $hasher)
    {
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneBy(['token' => $token]);

        if (!$reset_password) {
            return $this->redirectToRoute('reset_password');
        }
        // Verifier si le createdAt est égal à NOW -3h
        $now = new \DateTime();
        if ($now > $reset_password->getCreatedAt()->modify('+ 3 hours')) {
            $this->addFlash('notice', "Votre demande de réinitialisation de mot de passe a expiré. Merci de la renouveler");
            return $this->redirectToRoute('reset_password');
        }

        // Rendre une vue avec mot de passe et confirmation mot de passe
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_pwd = $form->get('new_password')->getData();

            // Encodage mot de passe
            $password = $hasher->hashPassword($reset_password->getUser(), $new_pwd);
            $reset_password->getUser()->setPassword($password);

            // Flush en bdd 
            $this->entityManager->flush();

            // Redirection du user vers la page de connexion
            $this->addFlash('notice', "Votre mot de passe a bien été mis à jour");
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
