@extends('layout')

@section('content')
    <div v-pre><!--

        NOTE: v-pre skips Vue compilation for this element and all its children!

        -->
        <h3>
            <a href="{{ action('LogEntryController@index') }}">Logg</a>
        </h3>

        <p>
            Loggmeldinger lagres i {{ config('logging.channels.postgres.days') }} dager.
        </p>

        <p>
            Filtre:
            @foreach ($filters as $filter)
                <span class="badge badge-light">{{ $filter }}</span>
            @endforeach
            <a class="btn btn-sm btn-default" href="{{ action('LogEntryController@index') }}">Nullstill</a>
        </p>

        </div>
            <table class="table table-striped table-sm">
                <caption class="sr-only">Liste over loggmeldinger</caption>
                <tr>
                    <th scope="col">Tidspunkt</th>
                    <th scope="col">Nivå</th>
                    <th scope="col">Melding</th>
                    <th scope="col">Kategorier</th>
                </tr>
                @foreach ($entries as $entry)
                    <tr>
                        <td style="white-space: nowrap; vertical-align: top;">
                            <small style="white-space:nowrap">{{ $entry->time }}</small>
                        </td>
                        <td style="white-space: nowrap; vertical-align: top;">
                            <span class="badge badge-{{ strtolower($entry->level_name) != 'error' ? strtolower($entry->level_name) : 'danger' }}">{{ $entry->level_name }}</span>
                        </td>
                        <td>
                            {{ $entry->message }}
                            @if (\Illuminate\Support\Arr::has($entry->context, 'url'))
                                <a href="{{ $entry->context['url'] }}">[Vis post]</a>
                            @endif
                            @if (\Illuminate\Support\Arr::has($entry->context, 'changes'))
                                <span>
                                    <a href="#" onclick="$(this).parent().next('.message-collapsed').toggle(); return false;">[Vis endringer]</a>
                                </span>
                                <ul class="message-collapsed" style="display: none;">
                                    @foreach($entry->context['changes'] as $change)
                                        <li>{{ $change }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                        <td style="white-space:nowrap; text-align: right; padding-right: 20px;">
                            @if (\Illuminate\Support\Arr::has($entry->context, 'user'))
                                <a href="{{ action('LogEntryController@index', ['user' => \Illuminate\Support\Arr::get($entry->context, 'user')]) }} " class="badge badge-warning">
                                    {{ \Illuminate\Support\Arr::get($entry->context, 'user') }}
                                </a>
                            @endif

                            @if (\Illuminate\Support\Arr::has($entry->context, 'group'))
                                <a href="{{ action('LogEntryController@index', ['group' => \Illuminate\Support\Arr::get($entry->context, 'group')]) }} " class="badge badge-success">
                                    {{ \Illuminate\Support\Arr::get($entry->context, 'group') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
    </div>

@stop

