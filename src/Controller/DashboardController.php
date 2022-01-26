<?php

namespace App\Controller;

use App\Entity\Alias;
use App\Entity\Domain;
use App\Entity\Mailbox;
use App\Entity\TlsPolicy;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DashboardController extends AbstractDashboardController {
    private AuthorizationCheckerInterface $auth;

    public function __construct(AuthorizationCheckerInterface $auth) {
        $this->auth = $auth;
    }

    /**
     * @Route("/", name="admin")
     */
    public function index(): Response {
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        return $this->redirect($routeBuilder->setController(MailboxCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard {
        return Dashboard::new()
            ->setTitle('Mail Admin');
    }

    public function configureMenuItems(): iterable {
        if (!$this->auth->isGranted('ROLE_USER')) {
            return [
                MenuItem::linktoDashboard('Login', 'fa fa-key'),
            ];
        }
        return [
//            MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Mailboxes', 'fa fa-inbox', Mailbox::class),
            MenuItem::linkToCrud('Aliases', 'fa fa-share', Alias::class),
            MenuItem::linkToCrud('Domains', 'fa fa-globe-americas', Domain::class),
            MenuItem::linkToCrud('TLS Policies', 'fa fa-lock', TlsPolicy::class),
        ];
    }

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @param AdminUrlGenerator $routeBuilder
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, AdminUrlGenerator $routeBuilder): Response {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('@EasyAdmin/page/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'page_title' => 'Mail Admin Login',
            'csrf_token_intention' => 'authenticate',
            'target_path' => $routeBuilder->setController(MailboxCrudController::class)->generateUrl(),
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout() {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
