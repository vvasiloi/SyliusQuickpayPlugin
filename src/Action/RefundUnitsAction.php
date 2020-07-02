<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayPlugin\Action;

use Psr\Log\LoggerInterface;
use Setono\SyliusQuickpayPlugin\Command\Factory\RefundUnitsCommandFactoryInterface;
use Sylius\RefundPlugin\Exception\InvalidRefundAmountException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RefundUnitsAction
{
    /** @var MessageBusInterface */
    private $commandBus;

    /** @var Session */
    private $session;

    /** @var UrlGeneratorInterface */
    private $router;

    /** @var RefundUnitsCommandFactoryInterface */
    private $commandFactory;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        MessageBusInterface $commandBus,
        Session $session,
        UrlGeneratorInterface $router,
        RefundUnitsCommandFactoryInterface $commandFactory,
        LoggerInterface $logger
    ) {
        $this->commandBus = $commandBus;
        $this->session = $session;
        $this->router = $router;
        $this->commandFactory = $commandFactory;
        $this->logger = $logger;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $this->commandBus->dispatch($this->commandFactory->fromRequest($request));

            $this->session->getFlashBag()->add('success', 'sylius_refund.units_successfully_refunded');
        } catch (InvalidRefundAmountException $exception) {
            $this->session->getFlashBag()->add('error', $exception->getMessage());

            $this->logger->error($exception->getMessage());
        } catch (HandlerFailedException $exception) {
            /** @var \Exception $previousException */
            $previousException = $exception->getPrevious();

            $this->provideErrorMessage($previousException);

            $this->logger->error($previousException->getMessage());
        }

        return new RedirectResponse($this->router->generate(
            'sylius_refund_order_refunds_list', ['orderNumber' => $request->attributes->get('orderNumber')]
        ));
    }

    private function provideErrorMessage(\Exception $previousException): void
    {
        if ($previousException instanceof InvalidRefundAmountException) {
            $this->session->getFlashBag()->add('error', $previousException->getMessage());

            return;
        }

        $this->session->getFlashBag()->add('error', 'sylius_refund.error_occurred');
    }
}
