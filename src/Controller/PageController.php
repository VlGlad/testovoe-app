<?php

namespace App\Controller;

use App\Entity\Request as EntityRequest;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class PageController extends AbstractController
{
    #[Route('/testtest', name: 'testtest')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        return $this->render('base.html.twig', [
            
        ]);
    }

    #[Route('/test/{slug}')]
    public function test(string $slug = null): Response
    {
        return new Response("Hi buddy, ".$slug);
    }
}

