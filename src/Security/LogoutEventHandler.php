<?php

namespace App\Security;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutEventHandler implements EventSubscriberInterface {
    public function __construct(
        #[Autowire('%env(KEYCLOAK_BASE_URL)%/realms/%env(KEYCLOAK_REALM)%/protocol/openid-connect/logout')]
        private $keycloakLogoutUrl,
    ) {}

    public static function getSubscribedEvents(): array {
        return [LogoutEvent::class => 'onLogout'];
    }

    public function onLogout(LogoutEvent $event): void {
        $response = new RedirectResponse(
            $this->keycloakLogoutUrl,
            Response::HTTP_SEE_OTHER
        );
        $event->setResponse($response);
    }
}