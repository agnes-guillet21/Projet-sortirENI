<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/create", name="create", methods={"GET"})
     */
    public function createEvent(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();

        $formEvent = $this->createForm(EventType::class, $event);
        $formEvent->handleRequest($request);

        if ($formEvent->isSubmitted() && $formEvent->isValid()) {

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie ajoutée !');

            return $this->redirectToRoute('list');
        }

        return $this->render('event/createEvent.html.twig', [ 'formEvent' => $formEvent->createView() ]);
    }


    /**
     * @Route(path="/list", name="list", methods={"GET"})
     */
    public function listEvent(EntityManagerInterface $entityManager) {

        $listEvent = $entityManager->getRepository('App:Event')->findAll();

        return $this->render('event/listEvent.html.twig', ['listEvent' => $listEvent]);
    }

}


