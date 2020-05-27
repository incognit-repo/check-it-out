<?php

declare(strict_types=1);

namespace CheckItOut\Apps\Exercise\Controller\BookStore;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CheckItOut\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use CheckItOut\Exercise\BookStore\Application\Find\FindBookQuery;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use CheckItOut\Exercise\BookStore\Application\Find\BookFinderResponse;

final class BookGetController
{
    private QueryBus $bus;

    public function __construct(QueryBus $bus)
    {
        $this->bus = $bus;
    }

    public function __invoke(Request $request, string $id): Response
    {
        try {
            /** @var BookFinderResponse $response */
            $response = $this->bus->get(new FindBookQuery($id));
        } catch (HandlerFailedException $exception) {
            return new JsonResponse(
                [
                    'message' => $exception->getMessage(),
                    'code' => Response::HTTP_NOT_FOUND,
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse(
            [
                'bookId' => $response->getId(),
                'title' => $response->getTitle(),
            ]
        );
    }
}
