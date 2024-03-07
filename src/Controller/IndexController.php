<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/index', name: 'app_index')]
    public function index(): Response
    {
       
        return $this->render('base.html.twig');
    }
 
    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
       
        return $this->render('login.html.twig');
    }

    #[Route('/Register', name: 'app_register')]
    public function Register(): Response
    {
       
        return $this->render('lRegister.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function Contact(): Response
    {
       
        return $this->render('contact.html.twig');
    }

    #[Route('/about', name: 'app_about')]
    public function About(): Response
    {
       
        return $this->render('about.html.twig');
    }

    
    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
       
        return $this->render('owner/profile.html.twig');
    }

    


}
