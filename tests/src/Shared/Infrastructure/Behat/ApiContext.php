<?php

declare(strict_types=1);

namespace CheckItOut\Tests\Shared\Infrastructure\Behat;

use RuntimeException;
use Behat\Mink\Session;
use Behat\Gherkin\Node\PyStringNode;
use Behat\MinkExtension\Context\RawMinkContext;
use CheckItOut\Tests\Shared\Infrastructure\Mink\MinkHelper;
use CheckItOut\Tests\Shared\Infrastructure\Mink\MinkSessionRequestHelper;

class ApiContext extends RawMinkContext
{
    private $sessionHelper;
    private $minkSession;
    private $request;

    public function __construct(Session $minkSession)
    {
        $this->minkSession = $minkSession;
        $this->sessionHelper = new MinkHelper($this->minkSession);
        $this->request = new MinkSessionRequestHelper(new MinkHelper($minkSession));

        \file_put_contents('/app/books.json', '{"057eb536-7e06-45d3-91ac-1bb8bd14a13c":"book title"}');
    }

    /**
     * @Given I send a :method request to :url
     *
     * @param mixed $method
     * @param mixed $url
     */
    public function iSendARequestTo($method, $url): void
    {
        $this->request->sendRequest($method, $this->locatePath($url));
    }

    /**
     * @Given I send a :method request to :url with body:
     *
     * @param mixed $method
     * @param mixed $url
     */
    public function iSendARequestToWithBody($method, $url, PyStringNode $body): void
    {
        $this->request->sendRequestWithPyStringNode($method, $this->locatePath($url), $body);
    }

    /**
     * @Then the response content should be:
     */
    public function theResponseContentShouldBe(PyStringNode $expectedResponse): void
    {
        $expected = $this->sanitizeOutput($expectedResponse->getRaw());
        $actual = $this->sanitizeOutput($this->sessionHelper->getResponse());

        if ($expected !== $actual) {
            throw new RuntimeException(\sprintf("The outputs does not match!\n\n-- Expected:\n%s\n\n-- Actual:\n%s", $expected, $actual));
        }
    }

    /**
     * @Then the response should be empty
     */
    public function theResponseShouldBeEmpty(): void
    {
        $actual = \trim($this->sessionHelper->getResponse());

        if (!empty($actual)) {
            throw new RuntimeException(\sprintf("The outputs is not empty, Actual:\n%s", $actual));
        }
    }

    /**
     * @Then print last api response
     */
    public function printApiResponse(): void
    {
        \print_r($this->sessionHelper->getResponse());
    }

    /**
     * @Then print response headers
     */
    public function printResponseHeaders(): void
    {
        \print_r($this->sessionHelper->getResponseHeaders());
    }

    /**
     * @Then the response status code should be :expectedResponseCode
     *
     * @param mixed $expectedResponseCode
     */
    public function theResponseStatusCodeShouldBe($expectedResponseCode): void
    {
        if ($this->minkSession->getStatusCode() !== (int) $expectedResponseCode) {
            throw new RuntimeException(\sprintf('The status code <%s> does not match the expected <%s>', $this->minkSession->getStatusCode(), $expectedResponseCode));
        }
    }

    private function sanitizeOutput(string $output)
    {
        return \json_encode(\json_decode(\trim($output), true));
    }
}
