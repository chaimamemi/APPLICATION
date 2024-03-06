<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Avis;
use App\Entity\Type;
use App\Repository\TypeRepository;
use App\Repository\AvisRepository;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;
use PHPUnit\Util\Json;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;




class MobileController extends AbstractController
{
     /**
     * @Route("/listReclamations", name="app_mobile")
     */
    public function index(NormalizerInterface $normalizer): Response
    {
        $reclamations = $this->getDoctrine()->getManager()
            ->getRepository(Reclamation::class)
            ->findAll();

        $jsonContent = $normalizer->normalize($reclamations,'json',['groups'=>'reclamations']);

        return new JsonResponse($jsonContent);


    }



 

    /**
     * @Route("/detailReclamation", name="detail_mobile")
     */

    public function DetailReclamation(Request $request)
    {
        $id=$request->get("id");
        $em=$this->getDoctrine()->getManager();
        $reclamation=$em->getRepository(Reclamation::class)->find($id);
        $encoder= new JsonEncoder();
        $normalizer=new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object){
            return $object->getDescription;
        });
        $serializer= new Serializer([new ObjectNormalizer()]);
        $formatted= $serializer->normalize($reclamation);

        return new JsonResponse($formatted);


    }





     /**
     * @Route("/detailAvis", name="detail_mobile")
     */

    public function DetailAvis(Request $request)
    {
        $id=$request->get("id");
        $em=$this->getDoctrine()->getManager();
        $avis=$em->getRepository(Avis::class)->find($id);
        $encoder= new JsonEncoder();
        $normalizer=new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object){
            return $object->getDescription;
        });
        $serializer= new Serializer([new ObjectNormalizer()]);
        $formatted= $serializer->normalize($avis);

        return new JsonResponse($formatted);


    }


       /**
     * @Route("/listavis", name="listesAvis")
     */
    public function getAvis(EntityManagerInterface $em,NormalizerInterface $Normalizer)
    {
        $avis=$em
        ->getRepository(Avis::class)
        ->findAll();

        $jsonContent=$Normalizer->normalize($avis, 'json', ['groups'=>'aviss']);
        //dump($json);
        //die;
        return new Response(json_encode($jsonContent));
    
    }










     /**
     * @Route("/addAvis", name="ajouteAvis")
     */
    public function add_avis( NormalizerInterface $normalizable, EntityManagerInterface $entityManager, Request $request)
    {

        $avis = new Avis();


        $avis->setRating($request->get('rating'));
        $avis->setCommentaire($request->get('commentaire'));
        $avis->setTitre($request->get('titre'));

        $entityManager->persist($avis);
        $entityManager->flush();
        return new JsonResponse([
            'success' => "avis has been added"
        ]);
    }

        /**
     * @Route("/addReclamation", name="ajouteReclamation")
     */
    public function add_reclamation( NormalizerInterface $normalizable, EntityManagerInterface $entityManager, Request $request)
    {

        $reclamation = new Reclamation();

        $daterec= $request->get("date_reclamation");
        $reclamation->setNom($request->get("nom"));
        $reclamation->setPrenom($request->get("prenom"));
        $reclamation->setEmail($request->get("email"));
        $reclamation->setTel($request->get("tel"));
        $reclamation->setEtat($request->get("etat"));
        $reclamation->setDescription($request->get("description"));
       // $reclamation->setDateReclamation($request->get("date_reclamation"));
       $reclamation->setDateReclamation(new \DateTime($daterec));
        
        

        $entityManager->persist($reclamation);
        $entityManager->flush();
        return new JsonResponse([
            'success' => "reclamation has been added"
        ]);
    }


    

    /**
     * @Route("/delAvis/{id}",name="removeAvis")
     */

    public function removeAvis(EntityManagerInterface $em,$id):Response
    {

        $cat=$em
        ->getRepository(Avis::class)
        ->find($id);
        $this->getDoctrine()->getManager()->remove($cat);

        $this->getDoctrine()->getManager()->flush();
        return $this->json(array('title'=>'successful','message'=> "Avis supprimé avec succès"),200);

    }

/**
     * @Route("/delReclamation/{id}", name="delreclamation")
     */


     public function delReclamationoffre(Request $request,NormalizerInterface $normalizer)
     {
         $em=$this->getDoctrine()->getManager();
         $rec=$this->getDoctrine()->getRepository(Reclamation::class)
             ->find($request->get("id"));
         $em->remove($rec);
         $em->flush();
         $jsonContent = $normalizer->normalize($rec,'json',['reclamation'=>'post:read']);
         return new Response(json_encode($jsonContent));
     }



   



    /**
     * @Route("/editavis/{id}",name="editavis")
     */

    public function editAvis(Request $request, EntityManagerInterface $em,$id):Response
    {
        $avis=$em
        ->getRepository(Avis::class)
        ->find($id);
        $avis->setRating($request->get('rating'));
        $avis->setCommentaire($request->get('commentaire'));
        $avis->setTitre($request->get('titre'));


        $this->getDoctrine()->getManager()->persist($avis);

        $this->getDoctrine()->getManager()->flush();
        return $this->json(array('title'=>'successful','message'=> "Aeroport modifié avec succès"),200);

    }




   
    /**
     * @Route("/editeReclamation/{id}", name="update_reclamation")
     */

     public function modifierReclamationAction(Request $request)
     {
         $em = $this->getDoctrine()->getManager();
         $reclamation = $this->getDoctrine()->getManager()
             ->getRepository(Reclamation::class)
             ->find($request->get("id"));
 
 
 
             $reclamation->setNom($request->get("nom"));
             $reclamation->setPrenom($request->get("prenom"));
             $reclamation->setEmail($request->get("email"));
             $reclamation->setTel($request->get("tel"));
             $reclamation->setEtat($request->get("etat"));
             $reclamation->setDescription($request->get("description"));
          
 
         $em->persist($reclamation);
         $em->flush();
 
         $serializer = new Serializer([new ObjectNormalizer()]);
         $formatted = $serializer->normalize($reclamation);
         return new JsonResponse("Reclamation a ete modifiee avec success.");
 
     }
 

}
