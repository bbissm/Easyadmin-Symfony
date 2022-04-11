<?php

namespace App\Controller;
use App\Entity\Member;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends BaseController
{
    public function __construct(RequestStack $requestStack, ManagerRegistry $doctrine)
    {
        parent::__construct($requestStack, $doctrine);
    }

    /**
     * @Route("/member", name="member")
     */
    public function index(): Response
    {
        $members = $this->doctrine
            ->getRepository(Member::class)
            ->findAll();
        return $this->render('team.html.twig', [
                'members' => $members,
                'base_path' => '%app.path.attachments%'
        ]);
    }
}
