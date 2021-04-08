<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ModificationFormType;
use App\Form\RegistrationFormType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ModifyUserController
 * @package App\Controller
 * @Route ("/user", name="user_")
 */
class ModifyUserController extends AbstractController
{
    /**
     * @Route("/display/{id}", requirements={"id":"\d+"}, name="display_user", methods={"GET","POST"}, defaults={1})
     */
    public function displayUser(Request $request, EntityManagerInterface $entityManager)
    {
        $profileId = $request->get('id');
        $profile = $entityManager->getRepository(User::class)->find($profileId);
        return $this->render('modify_user/displayProfile.html.twig',['profile' => $profile]);
    }

    /**
     * @Route("/modify/{id}", requirements={"id":"\d+"}, name="modify_user", methods={"GET","POST"})
     *
     */
    public function modifyUser(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $profileId = $request->get('id');
        $profile = $entityManager->getRepository(User::class)->find($profileId);
        $pass = $profile->getPassword();
        dump($pass);
        $form = $this->createForm(ModificationFormType::class, $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $passChek = $passwordEncoder->encodePassword(
                $profile,
                $form["oldPassword"]->getData()
            );
            dump($passChek);
            exit();

            $entityManager->persist($profile);
            $entityManager->flush();
            $this->addFlash('success', 'Modification success');
            return $this->redirectToRoute('home');// pas sur du nom de la route
        }
        return $this->render('modify_user/modifyProfile.html.twig', ["userForm" => $form->createView()]);
    }

}