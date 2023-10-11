<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AuthController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/register', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {

        $form = $this->createFormBuilder()
            ->add('gebruikersnaam')
            ->add('password', RepeatedType::class, [
               'type' => PasswordType::class,
               'required' => true,
                'first_options' => ['label' => 'Wachtwoord'],
                'second_options' => ['label' => 'Bevestig Wachtwoord'],
            ])
            ->add('Gebruiker_Aanmaken', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $data = $form->getData();
            $user = new User();
            $user->setUsername($data['gebruikersnaam']);
            $user->setPassword(
                $passwordHasher->hashPassword($user, $data['password'])
            );

            $entityManager = $this->entityManager;
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('auth/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(User $user): RedirectResponse
    {
        $em = $this->entityManager;
        $em->remove($user);
        $em->flush();

        $this->addFlash(type: 'success', message: 'Gebruiker is verwijderd');

        return $this->redirect($this->generateUrl( route: 'users' ));
    }
}
