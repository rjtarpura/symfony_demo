<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{

    
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/form")
     */
    public function form(){
        
        //Store stuff in database
        $em = $this->getDoctrine()->getManager();

        
        //Create Post
        /*
        $post = new Post();
        $post->setTitle('Second Post')->setDescription('This is my second Post');
        //Create SQL.
        $em->persist($post);
        */
        /*
        $post2 = new Post();
        $post2->setTitle('Third Post')->setDescription('This is my third Post');
        //Create SQL.
        $em->persist($post2);
        */

        

        $post = $em->getRepository(Post::class)->findOneBy([
            'id' => 2
        ]);

        // $em->remove($post);
        //Call it at the end.
        // $em->flush();
        // dd($post);
        // dd($post->getTitle());

        $form = $this->createFormBuilder()
                ->add('first_name')
                ->getForm();

        return $this->render('home/form.html.twig', [
            'user_form' => $form->createView(),
            'post' =>$post,
        ]);
    }

}
