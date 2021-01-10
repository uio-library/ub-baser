@extends('layout')
@section('db-title', $base->title)

@section('footer-column1')
    <ul class="list-unstyled">
        <li>
            @lang('bibliomanuel.footer_line1', [
                'publisher' => '<a href="https://www.ub.uio.no/">' . trans('oversatte_lover.ubo') . '</a>'
            ])
        </li>
        <li>
            @lang('bibliomanuel.footer_line2', [
                'contact' => '<a href="TODO">TODO</a>',
            ])
        </li>
    </ul>
@endsection
