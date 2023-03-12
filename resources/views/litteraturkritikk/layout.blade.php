@extends('layout')
@section('db-title', $base->title)

@section('head')
<script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Dataset",
        "name": "Norsk litteraturkritikk",
        "description": "Bibliografi over litteraturkritikk publisert i norske medier. Den inneholder også enkelte omtaler av norsk litteratur publisert i utenlandske medier. Bibliografien har over 20 000 innførsler, men er ikke fullstendig, og redigeres og utbygges kontinuerlig av Universitetsbiblioteket i Oslo og Institutt for lingvistiske og nordiske studier.",
        "url": "https://ub-baser.uio.no/norsk-litteraturkritikk",
        "keywords": ["Literary criticism", "Norwegian literature"],
        "license": "http://creativecommons.org/licenses/by-nc-nd/4.0/",
        "isAccessibleForFree": true,
        "creator": [{
            "@type": "Organization",
            "url": "https://www.hf.uio.no/iln/",
            "name": "Institutt for lingvistiske og nordiske studier"
        }, {
            "@type": "Organization",
            "url": "https://www.ub.uio.no/",
            "name": "Universitetsbiblioteket i Oslo"
        }],
        "distribution": [{
            "@type": "DataDownload",
            "encodingFormat": "CSV",
            "contentUrl": "https://ub-baser.uio.no/static/norsk-litteraturkritikk-2022-12-30.zip"
        }],
        "temporalCoverage": "1789-01-01/2014-12-18"
    }
</script>
@endsection

@section('h1-title')
<a href="{{ $base->action('index') }}">{{ $base->title }}</a>
@endsection

@section('footer-column1')
<ul class="list-unstyled">
    <li>
        Nettstedet drives av
        <a href="https://www.ub.uio.no/">Universitetsbiblioteket</a>
        sammen med
        <a href="https://www.hf.uio.no/iln/">Institutt for lingvistiske og nordiske studier</a>.
        <br>
        Kontakt oss: <a href="mailto:norsklitteraturkritikk@ub.uio.no">norsklitteraturkritikk@ub.uio.no</a>
    </li>
</ul>
@endsection
