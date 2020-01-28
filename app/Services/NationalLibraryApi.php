<?php

namespace App\Services;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class NationalLibraryApi
{
    protected $baseUrl = 'https://api.nb.no/catalog/v1/items';

    public function __construct(ClientInterface $http, RequestFactoryInterface $factory)
    {
        $this->http = $http;
        $this->factory = $factory;
    }

    public function request(string $query): array
    {
        // https://api.nb.no/catalog/v1/items?q=2%20Engelstad%20Kundera&filter=mediatype:tidsskrift&filter=title:Samtiden&filter=date:[19790101%20TO%2019791231]
        // q=2+Engelstad+Kundera&mediatype=tidsskrift&title=Samtiden&fromDate=19790101&toDate=19791231"
        // ?q=2%20Engelstad%20Kundera&filter=mediatype:tidsskrift&

        // filter=digital:Ja&filter=contentClasses%3Ajp2&filter=api_title%3ASamtiden&
        //
        // filter=date:[19790101%20TO%2019791231]&aggs=mediatype&aggs=month&size=6&profile=wwwnbno

        // https://api.nb.no/catalog/v1/items?q=2%20Engelstad%20Kundera&filter=mediatype:tidsskrift&filter=digital:Ja&filter=contentClasses%3Ajp2&filter=api_title%3ASamtiden&filter=date:[19790101%20TO%2019791231]&aggs=mediatype&aggs=month&size=11&profile=wwwnbno
        $url = $this->baseUrl . '?' . $query;
        $request = $this->factory->createRequest('GET', $url);
        //dd($request->getUri());

        $response = $this->http->sendRequest($request);

        return json_decode($response->getBody(), true);
    }
}
