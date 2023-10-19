<?php

declare(strict_types=1);


namespace App\Tests\Acceptance;


use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

final class ApiContext implements Context
{
    private Client $httpClient;
    private ResponseInterface $response;

    public function __construct()
    {
        $this->httpClient = new Client(['base_uri' => 'http://127.0.0.1:32777']);
    }

    /**
     * @Given I have the following JSON array:
     */
    public function iHaveTheFollowingJSONArray(PyStringNode $json)
    {
        $this->jsonArray = json_decode($json->getRaw(), true);
    }

    /**
     * @When I send a POST request to :endpoint with the JSON data
     */
    public function iSendAPostRequestToWithTheJSONData($endpoint)
    {
        $jsonString = json_encode($this->jsonArray);
        $request = new Request('POST', $endpoint, [], $jsonString);
        $this->response = $this->httpClient->send($request);
    }

    /**
     * @Then the response status code should be :statusCode
     */
    public function theResponseStatusCodeShouldBe($statusCode)
    {
        assert($this->response->getStatusCode() == $statusCode, "Status code is not as expected.");
    }

    /**
     * @Then the response should contain JSON:
     */
    public function theResponseShouldContainJSON(PyStringNode $json)
    {
        $responseData = json_decode($this->response->getBody()->getContents(), true);
        $expectedData = json_decode($json->getRaw(), true);

        assert($responseData == $expectedData, "Response JSON does not match expected JSON.");
    }
}