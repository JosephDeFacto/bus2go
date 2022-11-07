<?php

namespace App\Controller\Admin;

use App\Controller\IndexController;
use App\Controller\OrderController;
use App\Entity\Order;
use App\Entity\TravelSchedule;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class DashboardController extends AbstractDashboardController
{
    /**
     * @isGranted("ROLE_ADMIN")
     * @Route("/admin", name="admin_home")
     */
    public function index(): Response
    {
        //return parent::index();
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(DashboardController::class)->generateUrl());
    }

    public function configureMenuItems(): iterable
    {
        return [
             MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
             MenuItem::section('Blog'),
             MenuItem::linkToCrud('Travel Schedules', 'fa fa-tags', TravelSchedule::class),
             MenuItem::linkToLogout('Logout', 'fa fa-exit'),
        ];
    }
}
