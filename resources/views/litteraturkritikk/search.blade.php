    <form class="form-horizontal" id="searchForm" method="GET" action="{{ action('LitteraturkritikkController@index') }}">
        <input type="hidden" name="search" value="true">
        <input type="hidden" name="view" value="{{ $view }}">

        @foreach ($fields as $fieldIndex => $field)

        <div class="form-group field-set" data-index="{{ $fieldIndex }}">
            <div class="col-sm-4">
                <select class="form-control field-select" id="input{{ $fieldIndex }}field" name="input{{ $fieldIndex }}field">
                    @foreach ($selectOptions as $option))
                    <option value="{{ $option['id'] }}"{!! ($field[0] == $option['id']) ? ' selected="selected"' : '' !!}>{{ $option['label'] }} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-5">
              <input type="text" class="form-control" id="input{{ $fieldIndex }}value" name="input{{ $fieldIndex }}value" value="{{ $field[1] }}">
            </div>

            @if ($fieldIndex == count($fields) - 1)
            <div class="col-sm-1" id="addFieldButtonContainer">
              <button type="button" class="btn btn-info" id="addFieldButton"><i class="fa fa-plus"></i></button>
            </div>
            @else
                <div class="col-sm-1 help-block">og</div>
            @endif
        </div>

        @endforeach

        <div class="form-group"id="yearRangeContainer">
            <div class="checkbox">
                <label for="yearRange">
                    Utgivelsesår for kritikken:
                </label>
                {{ $minDate}} &nbsp; <input id="yearRange" name="date" type="text" class="span2" value="{{ $date[0] . '-' . $date[1] }}"
                                    data-slider-min="{{ $minDate }}" data-slider-max="{{ $maxDate }}" data-slider-step="1"
                                    data-slider-value="[{{ $date[0] }},{{ $date[1] }}]"/>
                &nbsp; {{ $maxDate }}
            </div>
        </div>

        <div class="form-group" id="searchButtonContainer">
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary btn-block"><i class="zmdi zmdi-search"></i> {{ trans('messages.search') }}</button>
            </div>
            <div class="col-sm-2">
                <a href="{{ action('LitteraturkritikkController@index') }}" class="btn btn-default">{{ trans('messages.clear') }}</a>
            </div>
        </div>
    </form>

@section('script')
    @parent
<script type="text/javascript">

$(function() {

    var fields = [
    @foreach ($selectOptions as $option)
        {id: '{{ $option['id'] }}', placeholder: '{{ $option['placeholder'] }}', type: '{{ $option['type'] }}'},
    @endforeach
    ];

    var fieldSetTemplate = $( ".field-set" ).last().clone();

    function addField(n) {
        var lastSet = $( ".field-set" ).last(),
            newSet = fieldSetTemplate.clone(),
            currentIdx = parseInt(lastSet.data('index'), 10),
            fieldSelect = newSet.find('select'),
            fieldValue = newSet.find('input[type="text"]'),
            newIdx = currentIdx + 1;

        newSet.data('index', newIdx);

        fieldSelect.prop('id', 'input' + newIdx + 'field')
            .attr('name', 'input' + newIdx + 'field');

        fieldValue.prop('id', 'input' + newIdx + 'value')
            .attr('name', 'input' + newIdx + 'value')
            .val('');

        if (!fieldSelect.length || !fieldValue.length) {
            console.error('Could not locate field');
            return;
        }

        // Add to DOM
        $('#yearRangeContainer').before(newSet);

        // Update placeholder text
        $('.field-select').trigger('change');

        // Remove add button from last set
        lastSet.find('#addFieldButtonContainer').off('click')
            .replaceWith('<div class="col-sm-1 help-block">og</div>');

        // Add event handlers
        activate();
    }

    function onFieldSelect(evt) {
        var fieldId = this.id.match('input([0-9]+)field')[1],
            newIdx = this.selectedIndex,
            oldIdx = $(this).data('oldIndex'),
            textField = $('#input' + fieldId + 'value');

        if (oldIdx == newIdx) {
            return;
        }

        $(this).data('oldIndex', newIdx);

        textField.attr('placeholder', fields[newIdx].placeholder);

        var s = textField[0].selectize;
        if (s) {
            console.log('Removing selectize');
            s.destroy();
        }
        textField.addClass('form-control');
        if (oldIdx !== undefined) {
            textField.val('');
        }

        if (fields[newIdx].type == 'select') {
            setTimeout(function() {
                console.log('Adding selectize');
                addSelectize(fields[newIdx], textField);
            }, 0);
        }
    }

    function addSelectize(fieldDef, fieldEl) {
        fieldEl.removeClass('form-control').selectize({
            valueField: 'value',
            labelField: 'value',
            searchField: 'value',
            delimiter: null,
            maxItems: 1,
            closeAfterSelect: true,
            openOnFocus: true,
            selectOnTab: true,
            preload: true,
            create: false,
            load: function(query, callback) {
                $.ajax({
                    url: '{{ action("LitteraturkritikkController@autocomplete") }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        field: fieldDef.id,
                        q: query,
                    },
                    error: function() {
                        callback();
                    },
                    success: function(res) {
                        callback(res);
                    }
                });
            }
        });
    }

    function activate() {
        $('#addFieldButton').on('click', addField);
        $('.field-select').off('change').on('change', onFieldSelect);
        $('.field-select').trigger('change');
    }

    activate();
    $("#yearRange").slider({formatter:function(val) {
        return val[0] + '-' + val[1];
    }});

});

</script>

@endsection