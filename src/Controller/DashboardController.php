<?php

namespace App\Controller;

use App\Entity\Alias;
use App\Entity\Domain;
use App\Entity\Mailbox;
use App\Entity\TlsPolicy;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController {
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {
    }

    #[Route('/', name: 'admin')]
    public function index(): Response {
        $routeBuilder = $this->adminUrlGenerator;
        return $this->redirect($routeBuilder->setController(MailboxCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard {
        return Dashboard::new()
            ->setTitle('Mail Admin');
    }

    public function configureMenuItems(): iterable {
        return [
            MenuItem::linkToCrud('Mailboxes', 'fa fa-inbox', Mailbox::class),
            MenuItem::linkToCrud('Aliases', 'fa fa-share', Alias::class),
            MenuItem::linkToCrud('Domains', 'fa fa-globe-americas', Domain::class),
            MenuItem::linkToCrud('TLS Policies', 'fa fa-lock', TlsPolicy::class),
        ];
    }

    #[Route('/login', name: 'login')]
    public function login(): Response {
        return $this->redirectToRoute('hwi_oauth_service_redirect', ['service' => 'keycloak']);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout() {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
