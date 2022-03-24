<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\DetaiInvoice;
use App\Entity\Invoice;
use App\Form\CustomerType;
use App\Form\DetailinvoiceType;
use App\Form\InvoiceType;
use App\Repository\CustomerRepository;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $em,CustomerRepository $req): Response
    {
        return $this->render('home/index.html.twig', [
            'reqs'=>$req->AllInvoice(),
        ]);
    }
    #[Route('/add', name: 'app_add')]
    
    public function add(Request $request, EntityManagerInterface $em,InvoiceRepository $value): Response
    {
        $Customer=new Customer();
        $invoice=new Invoice();
        $detail=new DetaiInvoice();
        //FORM CUSTOMER
        $formCust=$this->createForm(CustomerType::class, $Customer);
        $formCust->handleRequest($request);
        // FORM INVOICE
        $formInv=$this->createForm(InvoiceType::class, $invoice);
        $formInv->handleRequest($request);
         // FORM DETAILS
        $formDet=$this->createForm(DetailinvoiceType::class, $detail);
        $formDet->handleRequest($request);
       

        $session = $request->getSession();
        $Tabcomm = $session->get('calculs', []);
        $lig = 0;
        $ht=0;
        $Avat=0;
        $total=0;
        if (!$session->has('calculs'))
        {
            $session->set('calculs',[]);
            
        }
        if ($request->isMethod('POST')) {
            $choice=$request->get('btn');
            if($choice =="+=" ){ // BUTTON TO Operation
                $q=$detail->getQuantity();
                $a=$detail->getAmount();
                $vat=$detail->getVat();
                $total=$detail->getTotal();
                $ht=$q*$a;
                $Avat=($ht * $vat)*0.01;
                $total= $ht + $Avat;
            }
         if($choice=="validate"){ // ADD IN DATABASE
                $Customer=$formCust->getData();
                $invoice=$formInv->getData();
                $detail=$formDet->getData();
                $Customer->addInvoice($invoice);
                $invoice->setCustomerId($Customer);
                $em->persist($Customer);
                $em->persist($invoice);
                $em->flush();
                $lig = sizeof($Tabcomm);
            for ($i = 1; $i<=$lig; $i++){
                $det=new DetaiInvoice();
                $invoice->addDetaiInvoice($det);
                $det->setInvoice($invoice);
                $det->setDescription( $Tabcomm[$i]->getDescription() );
                $det->setQuantity( $Tabcomm[$i]->getQuantity() );
                $det->setAmount( $Tabcomm[$i]->getAmount() );
                $det->setVat( $Tabcomm[$i]->getVat() );
                $det->setLine($i);
                $det->setTotal( $Tabcomm[$i]->getTotal() );
                $em->persist($det);
                $em->flush();
                $session->clear();
                $this->addFlash('message','Invoice created success');
            }
            return $this->redirectToRoute('app_home'); 
            }else if($choice =="Add"){ //ADD IN CART
               
               
                $lig = sizeof($Tabcomm)+1;
                $detail->setLine($lig);
                $Tabcomm[$lig] = $detail;
                $session->set('calculs',$Tabcomm);
                
         }
        }
        return $this->render('home/add.html.twig',[
            'formCust'=>$formCust->createView(),
            'formInv'=>$formInv->createView(),
            'formDet'=>$formDet->createView(),
            'dcomm'=>$Tabcomm,
            'vals'=>$value->numberAuto(),
            'tot'=>$total,
        ]); 
    }
    // DELETE SESSION
    #[Route('/delete/{id}', name: 'app_delete')]
    public function deleted($id, Request $request): Response
    {
        
        $session = $request->getSession();
        $Tabcomm= $session->get('calculs', []);
        if (array_key_exists($id, $Tabcomm))
        {
            unset($Tabcomm[$id]);
            $session->set('calculs',$Tabcomm);
        }
        return $this->redirectToRoute('app_add'); 
    }
    // VIEW
    #[Route('/view/{number}', name: 'app_view')]
    public function view($number, Request $request, CustomerRepository $CustomerRepository): Response
    {
        $show=$CustomerRepository->ViewInvoice($number);
        return $this->render('home/view.html.twig',['show'=>$show]);
    }
// DELETE
    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete($id, Request $request,EntityManagerInterface $em): Response
    {
        $Customer=new Customer();
        $Customer= $em->getRepository(Customer::class)->find($id);
        $em->remove($Customer);
        $em->flush();
        $this->addFlash('message','Invoice deleted success');
        return $this->redirectToRoute('app_home'); 
    }
}
