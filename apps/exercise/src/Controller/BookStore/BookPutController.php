<?php

declare(strict_types=1);

namespace CheckItOut\Apps\Exercise\Controller\BookStore;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use CheckItOut\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use CheckItOut\Exercise\BookStore\Application\Create\CreateBookCommand;

final class BookPutController
{
    private CommandBus $bus;

    public function __construct(CommandBus $bus)
    {
        $this->bus = $bus;
    }

    public function __invoke(string $id, Request $request): Response
    {
        try {
            $this->bus->dispatch(new CreateBookCommand(
                $id,
                $request->request->get('title')
            ));
        } catch (HandlerFailedException $exception) {
            return new JsonResponse(
                [
                    'message' => $exception->getMessage(),
                    'code' => Response::HTTP_BAD_REQUEST,
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new Response('', Response::HTTP_CREATED);
    }
}
