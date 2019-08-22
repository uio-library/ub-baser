@extends('layouts.master')

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
                @foreach ($entries as $entry)
                    <tr>
                        <td valign="top" style="white-space:nowrap; padding-left: 20px">
                            <small style="white-space:nowrap">{{ $entry->time }}</small>
                        </td>
                        <td valign="top" style="white-space:nowrap">
                            <span class="badge badge-{{ strtolower($entry->level_name) != 'error' ? strtolower($entry->level_name) : 'danger' }}">{{ $entry->level_name }}</span>
                        </td>
                        <td>
                            <?php
                            if (strpos($entry->message, PHP_EOL) !== false) {
                                $spl = explode(PHP_EOL, htmlspecialchars($entry->message));
                                $i0 = array_shift($spl);
                                echo '<div><a href="#" onclick="$(this).parent().next(\'.message-collapsed\').toggle(); return false;">' . $i0 . '</a></div>';
                                echo '<div class="message-collapsed" style="display:none;">' . implode('<br>', $spl) . '</div>';
                            } else {
                                echo $entry->message;
                            }
                            ?>
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

