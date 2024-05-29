<?php
 
namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Routing\Attribute\Route;
//use App\Entity\Customer;
use App\Entity\Customers;

use Doctrine\DBAL\Types\Type;

Type::overrideType('datetime', 'Doctrine\DBAL\Types\VarDateTimeType');
Type::overrideType('datetimetz', 'Doctrine\DBAL\Types\VarDateTimeType');
Type::overrideType('time', 'Doctrine\DBAL\Types\VarDateTimeType');

Type::overrideType('datetime_immutable', 'Doctrine\DBAL\Types\VarDateTimeImmutableType');
Type::overrideType('datetimetz_immutable', 'Doctrine\DBAL\Types\VarDateTimeImmutableType');
Type::overrideType('time_immutable', 'Doctrine\DBAL\Types\VarDateTimeImmutableType');


class CustomerController extends AbstractController
{

    #[Route('/', name: 'api_index' )]
    public function index(): JsonResponse
    {
        $data = ['version' => "0.0.1", "server" => $_SERVER['SERVER_NAME']];
        return $this->json($data);
    }
    
    #[Route('/customers', name: 'customers_list', methods:['get'] )]
    public function customers(ManagerRegistry $doctrine): JsonResponse
    {
        $customers = $doctrine
            ->getRepository(Customers::class)
            ->findAll();
            
        //print_r($customers);
        $data = [];
        
        foreach ($customers as $customer) {
           $data[] = [
               'id' => $customer->getId(),
               'createdAt' => $customer->getCreatedAt(),
               'updatedAt' => $customer->getUpdatedAt(),
               'name' => $customer->getName(),
               'birthdate' => $customer->getBirthdate(),
               'emailAddress' => $customer->getEmailAddress(),
               'notes' => $customer->getNotes(),
           ];
        }
   
        return $this->json($data);
    }
 
 
    #[Route('/customers', name: 'customers_create', methods:['POST'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        echo "hello!"; die();
        $entityManager = $doctrine->getManager();
   
        $customer = new Customers();
        $customer->setName($request->request->get('name'));
        $customer->setNotes($request->request->get('notes'));
   
        $entityManager->persist($customer);
        $entityManager->flush();
   
        $data =  [
            'id' => $customer->getId(),
            'createdAt' => $customer->getCreatedAt(),
            'updatedAt' => $customer->getUpdatedAt(),
            'name' => $customer->getName(),
            'birthdate' => $customer->getBirthdate(),
            'emailAddress' => $customer->getEmailAddress(),
            'notes' => $customer->getNotes(),
        ];
           
        return $this->json($data);
    }
 
 
    #[Route('/customers/{id}', name: 'customer_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $customer = $doctrine->getRepository(Customers::class)->find($id);
   
        if (!$customer) {
   
            return $this->json('No customer found for id ' . $id, 404);
        }
   
        $data =  [
            'id' => $customer->getId(),
            'createdAt' => $customer->getCreatedAt(),
            'updatedAt' => $customer->getUpdatedAt(),
            'name' => $customer->getName(),
            'birthdate' => $customer->getBirthdate(),
            'emailAddress' => $customer->getEmailAddress(),
            'notes' => $customer->getNotes(),
        ];
           
        return $this->json($data);
    }
 
    
}