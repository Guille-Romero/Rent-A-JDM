<?php

namespace App\Controller\back;

use App\Entity\Car;
use App\Entity\Make;
use App\Form\CarType;
use App\Repository\MakeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 * @Route("/back/car", name="back_car_")
 */
class CarController extends AbstractController
{

    /**
     * @Route("/list", name="list")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Car::class);
        $cars = $repository->findAll();

        return $this->render('back/car/list.html.twig', [
            'cars' => $cars,
        ]);
    }

    /**
     * @Route("/list/{id<\d+>}", name="list_by_make")
     */
    public function makeListById(Make $make, $id, MakeRepository $makeRepository, EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Make::class);
        $cars = $makeRepository->findWithAssociatedData($id);
        
        return $this->render('back/car/list.html.twig', [
            'cars' => $cars,
            'make' => $make
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function createCar(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Build a form that allows the creation of a new car
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($car);
            $entityManager->flush();

            $entityManager->flush();

            $this->addFlash('success', 'Voiture ajoutée');


            return $this->redirectToRoute('back_car_list');
        }

        return $this->render('back/car/create.html.twig', [
            'carForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/read/{id<\d+>}", name="read")
     */
    public function readCar(Car $car): Response
    {
        return $this->render('back/car/read.html.twig', [
            'car' => $car,
        ]);
    }

    /**
     * @Route("/update/{id<\d+>}", name="update")
     */
    public function updateCar(Car $car, Request $request,EntityManagerInterface $entityManager): Response
    {
        // Build a form that allows the update of a car
        $form = $this->createForm(CarType::class, $car);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($car);
            $entityManager->flush();

            $entityManager->flush();

            $this->addFlash('success', 'Voiture editée');


            return $this->redirectToRoute('back_car_list');
        }

        return $this->render('back/car/create.html.twig', [
            'carForm' => $form->createView(),
            'car' => $car,
        ]);
    }

    /**
     * @Route("/delete/{id<\d+>}", name="delete")
     */
    public function deleteCar(Car $car, EntityManagerInterface $entityManager): Response
    {
        // Check if the car entity exists
        if (!$car) {
            throw $this->createNotFoundException('Car not found');
        }
    
        // Remove the car from the database
        $entityManager->remove($car);
        $entityManager->flush();
    
        $this->addFlash('success', 'Car deleted successfully');
    
        return $this->redirectToRoute('back_car_list');
    }
}
