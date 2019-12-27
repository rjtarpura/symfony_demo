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
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository(Post::class)->findAll();
        
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $posts,
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

            $this->addFlash(
                'info',
                'Post Added successfully'
            );

            return $this->redirectToRoute('posts.list');
        }

        return $this->render('post/create.html.twig', [
            'postForm'  =>  $postForm->createView(),            
        ]);
    }

    /**
     * @Route("/show/{post}", name="show", methods={"GET"})
     */
    public function showPost(Request $request, Post $post){
        
        return $this->render('post/show.html.twig',[
            'post' => $post
        ]);
    }

    /**
     * @Route("/{post}", name="delete", methods={"DELETE"})
     */
    public function removePost(Request $request, Post $post){
       
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
            $this->addFlash('info','Post removed successfully.');
        }else{
            $this->addFlash('error','Token not matched.');
        } 
        return $this->redirectToRoute('posts.list');        
    }

    /**
     * @Route("/edit/{post}", name="edit", methods={"GET","POST"})
     */
    public function editPost(Request $request, Post $post){
        dd($post);
    }
}
