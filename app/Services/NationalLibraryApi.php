<?php

namespace App\Services;

use Http\Client\Exception\RequestException;
use Illuminate\Support\Arr;
use Psr\Http\Client\ClientExceptionInterface;
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

    /**
     * @param string $url
     * @throws ClientExceptionInterface
     * @return string
     */
    public function resolveUrl(string $url): string
    {
        if (preg_match('_https?://www\.nb\.no/items/([0-9a-z-]+)(\?.+)?_', $url, $matches, PREG_UNMATCHED_AS_NULL)) {
            $sesamId = $matches[1];
            $queryString = $matches[2];

            // Tidy up the query string by removing everything except 'page'
            $qs = '';
            if (preg_match('_page=([0-9]+)_', $queryString, $pageMatch)) {
                $qs = '?page=' . $pageMatch[1];
            }

            $res = $this->lookup($sesamId);

            // If the record has a URN, use it
            $urn = Arr::get($res, 'metadata.identifiers.urn');
            if (isset($urn)) {
                return 'https://urn.nb.no/' . $urn . $qs;
            }
            // Otherwise, just tidy up the url a bit
            return 'https://www.nb.no/items/' . $sesamId . $qs;
        }

        return $url;
    }

    /**
     * @param string $id
     * @throws ClientExceptionInterface
     * @return array
     */
    public function lookup(string $id): array
    {
        $url = $this->baseUrl . '/' . $id;
        $request = $this->factory->createRequest('GET', $url);
        $response = $this->http->sendRequest($request);

        if ($response->getStatusCode() != 200) {
            throw new RequestException($response->getBody(), $request);
        }

        return json_decode($response->getBody(), true);
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
