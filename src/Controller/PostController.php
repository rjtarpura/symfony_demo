<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\CustomService;
use Symfony\Component\HttpFoundation\Response;

// use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * @Route("/posts", name="posts.")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/see_service")
     */
    public function service(CustomService $cs)
    {
        // dd($cs->get('Rakesh'));
        return new Response($cs->get('Rakesh'));
    }

    /** 
     * @Route("", name="list", methods={"GET"})
     */
    public function index()
    {
        // dd($this->container);
        // dd($this->container->get(CustomService::class));
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
            
            $uploads_director = $this->getParameter('uploads_director');

            // dd($request->files->get('post'));die;
            //multiple file upload
            // if( $files = $request->files->get('post')['my_file'] ) {

            //     foreach ($files as $file) {
                    
            //         $filename = md5(uniqid()) . '.' . $file->guessExtension();  //php function

            //         try {
            //             $file->move($uploads_director, $filename);
            //             echo $file->getClientOriginalName() . "Uploads<br/>";
            //         } catch (FileException $e) {
            //             dd($e);
            //         }
            //     }
            // } die;

            
            if( $file = $request->files->get('post')['my_file'] ) {

                $filename = md5(uniqid()) . '.' . $file->guessExtension();  //php function

                try {
                    $file->move($uploads_director, $filename);
                    $post->setFileName($filename);
                } catch (FileException $e) {
                    dd($e);
                }
            }

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
     * @Route("/{post}/edit", name="edit", methods={"GET","POST"})
     */
    public function editPost(Request $request, Post $post){
        
        $postForm = $this->createForm(PostType::class, $post, [
            'action' => $this->generateUrl('posts.edit',[
                'post' => $post->getId(),
            ]),
            // 'method' => 'POST',  //default method of form action
        ]);

        $postForm->handleRequest($request);

        if($postForm->isSubmitted() && $postForm->isValid()){
            //Update Data
            // dd($request->files->get('post')['my_file']); //it will also work
            $uploads_director = $this->getParameter('uploads_director');
            if( $file = $postForm['my_file']->getData() ) {

                $filename = md5(uniqid()) . '.' . $file->guessExtension();  //php function

                try {
                    $file->move($uploads_director, $filename);
                    $post->setFileName($filename);
                } catch (FileException $e) {
                    dd($e);
                }
            }

            $em = $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'info',
                'Post Updated successfully'
            );

            return $this->redirectToRoute('posts.list');
        }

        return $this->render('post/create.html.twig', [
            'postForm'  =>  $postForm->createView(),            
        ]);

    }
}
