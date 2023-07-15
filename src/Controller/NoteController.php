<?php

namespace App\Controller;

use App\Entity\Audio;
use App\Entity\Note;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Video;
use App\Form\NotesFormType;
use Doctrine\ORM\EntityManager;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\security;
use Symfony\Component\Security\Core\User\UserInterface;

class NoteController extends AbstractController
{
    #[Route('/home', name: 'app_note')]
    public function index(NoteRepository $noteRepository): Response
    {
        $res = $noteRepository->findAll();
        return $this->render('note/index.html.twig',['res'=>$res]);
    }

    #[Route('/',name:'list_note')]
    public function getNote(NoteRepository $noteRepository):Response
    {
        $res = $noteRepository->findAll();
        return $this->render('note/note.html.twig',['res'=>$res]);
    }

    #[Route('/create',name:'create_note')]
    public function storeNote(Request $request ,EntityManagerInterface $em):Response
    {
        $note = new Note();

        //create form for Note
        $noteForm = $this->createForm(NotesFormType::class,$note);

        $noteForm->handleRequest($request);
        

        if($noteForm->isSubmitted() && $noteForm->isValid()){

            $image = $noteForm->get('image')->getData();
            $video = $noteForm->get('video')->getData();
            $audio = $noteForm->get('audio')->getData();

            foreach ($image as $fileName) {
                // Générez un nom de fichier unique
                $fi = md5(uniqid()) . '.' . $fileName->guessExtension();
                $fileName->move('Upload', $fi);

                $image = new Image();
                $image->setUrl($fi);
                // Ajoutez l'image à la note
                $note->addImage($image);
            }

            foreach ($video as $fileName) {
                // Générez un nom de fichier unique
                $fi = md5(uniqid()) . '.' . $fileName->guessExtension();
                $fileName->move('Upload', $fi);
            
                $videos = new Video();
                $videos->setUrl($fi);
                // Ajoutez l'image à la note
                $note->addVideo($videos);
            }


            foreach ($audio as $fileName) {
                // Générez un nom de fichier unique
                $fi = md5(uniqid()) . '.' . $fileName->guessExtension();
                $fileName->move('Upload', $fi);
            
                $audios = new Audio();
                $audios->setUrl($fi);
                // Ajoutez l'audio à la note
                $note->addAudio($audios);
            }
            



            $em->persist($note);
            $em->flush();
            $this->addFlash('success','Your note is posted with success !');
        }
        return $this->render('note/create.html.twig',[
            'noteForm'=>$noteForm->createView()
        ]);
    }


    #[Route('/show/{id}',name:'show_note')]
    public function showNote(Request $request, $id,NoteRepository $noteRepository){
        if(is_numeric($id)){
            $data = $noteRepository->findBy(['id' => $id]);
            return $this->render('note/show.html.twig', ['data' => $data]);
        }else{
            return new Response('<h2 style="text-align:center;margin-top:10vh;color:red">Erreur de paramètre d\'entrer </h2>');
        }
    }
}
