@extends('layout')
@section('db-title', 'Letras')

@section('footer-column1')
    <ul class="list-unstyled">
        <li>
            @lang('letras.footer_line1', [
                'publisher' => '<a href="https://www.ub.uio.no/">' . trans('letras.ubo') . '</a>'
            ])
        </li>
        <li>
            @lang('letras.footer_line2', [
                'contact' => '<a href="https://www.ub.uio.no/om/ansatte/uhs/uhsfagstudier/jmaria/index.html">Jose Maria Izquierdo</a>',
            ])
        </li>
    </ul>
@endsection
