<?php

namespace App\Controller\Admin;

use App\Entity\DailyCalories;
use App\Form\DailyCaloriesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/daily-calories")
 * @Security("has_role('ROLE_ADMIN')")
 */
class DailyCaloriesController extends AbstractController
{
    /**
     * @Route("/", name="admin_daily_calories")
     * @Method("GET|POST")
     */
    public function index(Request $request): Response
    {
        $dailyCalories = $this->getDoctrine()->getManager()
            ->getRepository(DailyCalories::class)
            ->getOrCreateTodaysRecord();

        $form = $this->createForm(DailyCaloriesType::class, $dailyCalories);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Daily calories number was successfully updated! ');
            return $this->redirectToRoute('admin_meal_index');
        }

        return $this->render('admin/dailyCalories/index.html.twig', [
            'dailyCalories' => $dailyCalories,
            'form' => $form->createView(),
        ]);
    }
}
