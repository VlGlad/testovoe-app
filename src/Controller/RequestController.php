<?php

namespace App\Controller;

use App\Entity\Request as EntityRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RequestController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $new_request = $doctrine->getRepository(EntityRequest::class)->findAll();
        
        return $this->render('request/index.html.twig', [
            'requests' => $new_request,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addRequest(Request $request, ManagerRegistry $doctrine): Response
    {
        $new_request = new EntityRequest();
        $form = $this->createFormBuilder($new_request)
            ->add('head', TextType::class)
            ->add('text', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Отправить заявку'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $new_request->setCreateDate(new \DateTime())
                ->setStatus("новая");

            $new_request = $form->getData();
            
            $entityManager = $doctrine->getManager();

            $entityManager->persist($new_request);
    
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }
        return $this->renderForm('request/addRequest.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/check', name: 'checkRequest')]
    public function checkRequest(Request $request, ManagerRegistry $doctrine)
    {
        $request_id = $request->query->get('id');

        $change_request = $doctrine->getRepository(EntityRequest::class)->find($request_id);

        $form = $this->createFormBuilder()
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'новая' => 'новая',
                    'в работе' => 'в работе',
                    'завершена' => 'завершена',
                    'отменена' => 'отменена',
                ],], ['label' => 'Назначить новый статус'])
                ->add('save', SubmitType::class, ['label' => 'Изменить статус'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $form_data = $form->getData();

            $change_request->setStatus($form_data["status"]);
            
            $entityManager = $doctrine->getManager();
    
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }


        return $this->renderForm('request/changeRequest.html.twig', [
            'form' => $form,
            'request' => $change_request,
        ]);
    }
}
