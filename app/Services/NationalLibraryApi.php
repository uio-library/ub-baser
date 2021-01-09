<?php

namespace App\Services;

use App\Exceptions\HttpErrorResponse;
use Illuminate\Support\Arr;
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
     * @return array
     */
    public function lookup(string $id): array
    {
        return $this->getJson($this->baseUrl . '/' . $id);
    }

    /**
     * @param string $query
     * @return array
     */
    public function search(string $query): array
    {
        return $this->getJson($this->baseUrl . '?' . $query);
    }

    /**
     * @param string $url
     * @throws HttpErrorResponse
     * @return array
     */
    protected function getJson(string $url): array
    {
        $request = $this->factory->createRequest('GET', $url);
        $response = $this->http->sendRequest($request);

        if ($response->getStatusCode() != 200) {
            throw new HttpErrorResponse($response, $request);
        }

        return json_decode($response->getBody(), true);
    }
}
