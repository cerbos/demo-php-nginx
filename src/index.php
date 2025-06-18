<?php

require __DIR__ . '/vendor/autoload.php';

use Cerbos\Sdk\Builder\AttributeValue;
use Cerbos\Sdk\Builder\CerbosClientBuilder;
use Cerbos\Sdk\Builder\CheckResourcesRequest;
use Cerbos\Sdk\Builder\Principal;
use Cerbos\Sdk\Builder\ResourceEntry;
use Cerbos\Sdk\Utility\RequestId;

$client = CerbosClientBuilder::newInstance("cerbos:3593")
    ->withPlaintext(true)
    ->build();

$request = CheckResourcesRequest::newInstance()
    ->withRequestId(RequestId::generate())
    ->withPrincipal(
        Principal::newInstance("john")->withRole("admin")
    )
    ->withResourceEntry(
        ResourceEntry::newInstance("leave_request", "xx125")
            ->withActions(["create"])
            ->withPolicyVersion("default")
    )
;
  
$response = $client->checkResources($request);
if ($response->find("xx125")->isAllowed("create")) {
    echo 'Allowed';
}
else {
    echo 'Denied';
}
