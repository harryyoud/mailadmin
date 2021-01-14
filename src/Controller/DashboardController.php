<?php

namespace App\Controller;

use App\Entity\Alias;
use App\Entity\Domain;
use App\Entity\Mailbox;
use App\Entity\TlsPolicy;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController {
    /**
     * @Route("/", name="admin")
     */
    public function index(): Response {
        return parent::index();
    }

    public function configureDashboard(): Dashboard {
        return Dashboard::new()
            ->setTitle('Mail Admin');
    }

    public function configureMenuItems(): iterable {
        return [
            MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Mailboxes', 'fa fa-inbox', Mailbox::class),
            MenuItem::linkToCrud('Aliases', 'fa fa-share', Alias::class),
            MenuItem::linkToCrud('Domains', 'fa fa-globe-americas', Domain::class),
            MenuItem::linkToCrud('TLS Policies', 'fa fa-lock', TlsPolicy::class),
        ];
    }
}
