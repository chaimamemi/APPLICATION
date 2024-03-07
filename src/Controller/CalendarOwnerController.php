<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Form\CalendarType;
use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarOwnerController extends AbstractController
{
    #[Route('/calendarowner', name: 'app_calendar_owner_index')]
    public function index(CalendarRepository $calendarRepository): Response
    {
        return $this->render('calendarOwner/index.html.twig', [
            'calendars' => $calendarRepository->findAll(),
        ]);
    }

    #[Route("/calendar/owner/new", name: "app_calendar_owner_new", methods: ["GET", "POST"])]
    public function new(Request $request): Response
    {
        $calendar = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($calendar);
            $entityManager->flush();

            return $this->redirectToRoute('app_calendar_owner_index');
        }

        return $this->render('calendarOwner/new.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/calendar/owner/{id}", name: "app_calendar_owner_show", methods: ["GET"])]
    public function show(Calendar $calendar): Response
    {
        return $this->render('calendarOwner/show.html.twig', [
            'calendar' => $calendar,
        ]);
    }

    #[Route("/calendar/owner/{id}/edit", name: "app_calendar_owner_edit", methods: ["GET", "POST"])]
    public function edit(Request $request, Calendar $calendar): Response
    {
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_calendar_owner_index');
        }

        return $this->render('calendarOwner/edit.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/calendar/owner/{id}", name: "app_calendar_owner_delete", methods: ["POST", "DELETE"])]
    public function delete(Request $request, Calendar $calendar): Response
    {
        if ($this->isCsrfTokenValid('delete' . $calendar->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($calendar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_calendar_owner_index');
    }
}
