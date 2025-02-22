<?php

use Sammyjo20\Saloon\Http\MockResponse;
use Sammyjo20\Saloon\Clients\MockClient;
use Sammyjo20\Saloon\Tests\Resources\Requests\UserRequest;
use Sammyjo20\Saloon\Exceptions\SaloonInvalidConnectorException;
use Sammyjo20\Saloon\Tests\Resources\Requests\NoConnectorRequest;
use Sammyjo20\Saloon\Tests\Resources\Connectors\ExtendedConnector;
use Sammyjo20\Saloon\Tests\Resources\Requests\InvalidConnectorRequest;
use Sammyjo20\Saloon\Exceptions\SaloonNoMockResponsesProvidedException;
use Sammyjo20\Saloon\Tests\Resources\Requests\ExtendedConnectorRequest;
use Sammyjo20\Saloon\Tests\Resources\Requests\InvalidConnectorClassRequest;

test('if you dont pass in a mock client to the saloon request it will not be in mocking mode', function () {
    $request = new UserRequest();
    $requestManager = $request->getRequestManager();

    expect($requestManager->isMocking())->toBeFalse();
});

test('you can pass a mock client to the saloon request and it will be in mock mode', function () {
    $request = new UserRequest();
    $mockClient = new MockClient([new MockResponse([], 200)]);

    $requestManager = $request->getRequestManager($mockClient);

    expect($requestManager->isMocking())->toBeTrue();
});

test('you cant pass a mock client without any responses', function () {
    $mockClient = new MockClient();
    $request = new UserRequest();

    $this->expectException(SaloonNoMockResponsesProvidedException::class);

    $request->send($mockClient);
});

test('saloon throws an exception if if no connector is specified', function () {
    $noConnectorRequest = new NoConnectorRequest;

    $this->expectException(SaloonInvalidConnectorException::class);

    expect($noConnectorRequest->getConnector());
});

test('saloon throws an exception if the connector is invalid', function () {
    $invalidConnectorRequest = new InvalidConnectorRequest;

    $this->expectException(SaloonInvalidConnectorException::class);

    expect($invalidConnectorRequest->getConnector());
});

test('saloon throws an exception if the connector is not a connector class', function () {
    $invalidConnectorClassRequest = new InvalidConnectorClassRequest;

    $this->expectException(SaloonInvalidConnectorException::class);

    expect($invalidConnectorClassRequest->getConnector());
});

test('saloon works even if you have an extended connector', function () {
    $request = new ExtendedConnectorRequest;

    expect($request->getConnector())->toBeInstanceOf(ExtendedConnector::class);
});
