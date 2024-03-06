<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client; 
use App\Repository\TypeRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Session\Session ; 
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\BarChart;
use App\Form\RechercheType;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{


    #[Route('/', name: 'app_reclamation_index', methods: ['GET', 'POST'])]
    public function index( ReclamationRepository $reclamationRepository,EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {    
        $reclamations=$entityManager->getRepository(Reclamation::class)->findAll();
        $back = null;
        if($request->isMethod("POST")){
            if ( $request->request->get('optionsRadios')){
                $SortKey = $request->request->get('optionsRadios');
                switch ($SortKey){
                    case 'nom':
                        $reclamations = $reclamationRepository->SortBynom();
                        break;

                    case 'date':
                        $reclamations = $reclamationRepository->SortBydate();
                        break;

                

                }
            }
            else
            {
                $type = $request->request->get('optionsearch');
                $value = $request->request->get('Search');
                switch ($type){
                    case 'nom':
                        $reclamations = $reclamationRepository->findBynom($value);
                        break;

               

                    case 'prenom':
                        $reclamations = $reclamationRepository->findByprenom($value);
                        break;


                }
            }

            if ( $reclamations ){
                $back = "success";
            }else{
                $back = "failure";
            }}
            $reclamations = $paginator->paginate(
                $reclamations,
                $request->query->getInt('page', 1),
                3
            );
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
            'back' => $back,

            ]);
       
    }


#[Route('/statisreclamation', name: 'app_reclamation_statisreclamation', methods: ['GET'])]
public function statisreclamation(ReclamationRepository $ReclamationRepository)
{
    //on va chercher les categories
    $rech = $ReclamationRepository->barDep();
    $arr = $ReclamationRepository->barArr();
    $type = $ReclamationRepository->bartype();
    $bar = new barChart ();
    $bar->getData()->setArrayToDataTable(
        [['reclamation', 'Type'],
         ['test1', intVal($rech)],
         ['test2', intVal($arr)],
         ['test3', intVal($type)],

        ]
    );

    $bar->getOptions()->setTitle('les Reclamations');
    $bar->getOptions()->getHAxis()->setTitle('Nombre de reclamation');
    $bar->getOptions()->getHAxis()->setMinValue(0);
    $bar->getOptions()->getVAxis()->setTitle('Type');
    $bar->getOptions()->SetWidth(800);
    $bar->getOptions()->SetHeight(400);


    return $this->render('reclamation/statisreclamation.html.twig', array('bar'=> $bar )); 

} 

    #[Route('/listr', name: 'app_reclamation_listr', methods: ['GET'])]
    public function listr(ReclamationRepository $reclamationRepository): Response
    {
        

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $l = $reclamationRepository->findAll();
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reclamation/listr.html.twig', [
            'reclamations' =>$l,
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
        return new Response();
    }


    
    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager)
    {   $reclamation = new reclamation();
         $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        $myDictionary = array(
            "tue", "merde", "pute",
            "gueule",
            "débile",
            "con",
            "abruti",
            "clochard",
            "sang"
        );
        dump($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myText = $request->get("reclamation")['description'];
            $badwords = new PhpBadWordsController();
            $badwords->setDictionaryFromArray($myDictionary)
                ->setText($myText);
            $check = $badwords->check();
            dump($check);
            if ($check) {
                $this->addFlash(
                    'erreur',
                    'Mot inapproprié! , Reclamation n est pas ajouté'
                ); } 
                else {

           
                $entityManager = $this->getdoctrine()->getManager();
                $entityManager->persist($reclamation);

                $this->sendTwilioMessage($reclamation);

                $entityManager->flush();
                $this->addFlash(
                    'info',
                    'Reclamation ajouté !!'
                );
            }

            return $this->redirectToRoute('app_reclamation_new', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),

        ]);
    }

    
    
    
    
    





    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setEtat('traité');
            $reclamationRepository->save($reclamation, true);
            $this->sendTwilioMessage($reclamation);
            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        $avis = $paginator->paginate(
            $allAvis,
            $request->query->getInt('page', 1),
            5
        );

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }
        $this->addFlash('message', 'Votre ajout est complete');


        $this->addFlash(
            'info',
            ' le Reclamation a été supprimer', 
        );



        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @throws ConfigurationException
     * @throws TwilioException
     */
    private function sendTwilioMessage( Reclamation $reclamation) : void
    {
        $twilioAccountSid = $this->getParameter('twilio_account_sid');
        $twilioAuthToken = $this->getParameter('twilio_auth_token');
        $twilioPhoneNumber = $this->getParameter('twilio_phone_number');

        $twilioClient = new Client($twilioAccountSid, $twilioAuthToken);


        $twilioClient->messages->create(
            '+21624524605', //// badel enum  mtaaak
            [
                'from' => $twilioPhoneNumber,
                'body' => 'Your reclamation has been  trated successfully registered with the following details: ' .
                    'status: ' . $reclamation->getEtat() . ', ' .
                    'prenom : ' . $reclamation->getPrenom() . ', ' .
                    'email : ' . $reclamation->getEmail(),
            ]
        );
    }

    
   
}
