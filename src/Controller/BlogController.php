<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\AddBlogType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('blog/index1.html.twig', [
            'all_blogs' => $blogRepository->findAll(),
        ]);
    }
    public function __construct(Environment $twig, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/addblog", name="addblog")
     */
    public function show(Request $request, BlogRepository $blogRepository, Environment $twig) 
    {
        
        $blog = new Blog();
        $form = $this->createForm(AddBlogType::class, $blog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $blog = $form->getData();

            //$blog->setBlog($blog);

            $this->entityManager->persist($blog);
            $this->entityManager->flush();
            return $this->redirectToRoute('addblog');
            
        }
    
        return new Response($twig->render('blog/index.html.twig' , ['add_form'=>$form->createView()]));
    }
}
