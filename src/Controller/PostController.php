<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/posts", name="posts.")
 */
class PostController extends AbstractController
{
    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function index()
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET","POST"})
     */
    public function create(Request $request){

        $post = new Post();
        // $post->setTitle('rakesh')->setDescription('test');
        $postForm = $this->createForm(PostType::class, $post, [
            'action' => $this->generateUrl('posts.create'),
            // 'method' => 'POST',  //default method of form action
        ]);

        //handle the request
        $postForm->handleRequest($request);

        if($postForm->isSubmitted() && $postForm->isValid()){
            //Store Data
            // var_dump($post);die;
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
        }

        return $this->render('post/create.html.twig', [
            'postForm'  =>  $postForm->createView(),            
        ]);
    }
}
