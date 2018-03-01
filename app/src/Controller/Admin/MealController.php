<?php

namespace App\Controller\Admin;

use App\Repository\MealRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/meal")
 * @Security("has_role('ROLE_ADMIN')")
 */
class MealController extends AbstractController
{
    /**
     * @Route("/", defaults={"page": "1"}, name="admin_meal_index")
     * @Route("/page/{page}", requirements={"page": "[1-9]\d*"}, name="admin_index_paginated")
     * @Method("GET")
     * @Cache(smaxage="10")
     */
    public function index(Request $request, MealRepository $mealRepository, int $page = 1): Response
    {
        $meals = $mealRepository->findAllMeals($page, $request);

        return $this->render('admin/meal/index.html.twig', [
            'meals' => $meals
        ]);
    }
}
