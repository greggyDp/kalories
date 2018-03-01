<?php

namespace App\Controller;

use App\Entity\DailyCalories;
use App\Entity\Meal;
use App\Form\MealType;
use App\Repository\MealRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/meal")
 * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')")
 */
class MealController extends AbstractController
{
    /**
     * @Route("/", defaults={"page": "1"}, name="meal_index")
     * @Route("/page/{page}", requirements={"page": "[1-9]\d*"}, name="meal_index_paginated")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
    public function index(Request $request, MealRepository $mealRepository, int $page = 1): Response
    {
        $meals = $mealRepository->findAllMealsByUser($page, $request, $this->getUser());
        $dailyUserCaloriesNumber = $mealRepository->getUserTotalDailyCalories($this->getUser());
        $dailyCalories = $this->getDoctrine()
            ->getManager()
            ->getRepository(DailyCalories::class)
            ->getOrCreateTodaysRecord();


        return $this->render('meal/index.html.twig', [
            'meals' => $meals,
            'dailyCalories' => $dailyCalories,
            'userDailyCaloriesNumber' => $dailyUserCaloriesNumber
        ]);
    }

    /**
     * @Route("/new", name="meal_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $meal = new Meal();
        $meal->setUser($this->getUser());

        $form = $this->createForm(MealType::class, $meal)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($meal);
            $em->flush();

            $this->addFlash('success', 'meal.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('meal_new');
            }

            return $this->redirectToRoute('meal_index');
        }

        return $this->render('meal/new.html.twig', [
            'meal' => $meal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, name="meal_show")
     * @Method("GET")
     */
    public function show(Meal $meal): Response
    {
        return $this->render('meal/show.html.twig', [
            'meal' => $meal,
        ]);
    }

    /**
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="meal_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Meal $meal): Response
    {
        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Meal was updated successfully');
            return $this->redirectToRoute('meal_edit', ['id' => $meal->getId()]);
        }

        return $this->render('meal/edit.html.twig', [
            'meal' => $meal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="meal_delete")
     * @Method("POST")
     */
    public function delete(Request $request, Meal $meal): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('meal_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($meal);
        $em->flush();

        $this->addFlash('success', 'Meal was deleted successfully');

        return $this->redirectToRoute('meal_index');
    }
}
