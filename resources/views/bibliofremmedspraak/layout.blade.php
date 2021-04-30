@extends('layout')
@section('db-title', $base->title)

@section('footer-column1')
    <ul class="list-unstyled">
        <li>
            @lang('bibliomanuel.footer_line1', [
                'publisher' => '<a href="https://www.ub.uio.no/">' . trans('bibliomanuel.ubo') . '</a>'
            ])
        </li>
        <li>
            @lang('bibliomanuel.footer_line2', [
                'contact' => '<a href="https://www.ub.uio.no/om/ansatte/uhs/uhsfagstudier/jmaria/index.html">Jose Maria Izquierdo</a>',
            ])
        </li>
    </ul>
@endsection
