<?php

namespace App\Controller;

use App\Entity\Event;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends BaseController
{
    public function __construct(RequestStack $requestStack, ManagerRegistry $doctrine)
    {
        parent::__construct($requestStack, $doctrine);
    }

    /**
     * @Route("/event", name="event")
     */
    public function list(): Response
    {
        $events = $this->doctrine
            ->getRepository(Event::class)
            ->findAll();
        if (!$events) {
            throw $this->createNotFoundException(
                'No Events found'
            );
        }
        return $this->render('event.html.twig', ['events'=>$events]);

    }
}
