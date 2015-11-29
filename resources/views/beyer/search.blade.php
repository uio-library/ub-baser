    <form class="form-horizontal" id="searchForm" method="GET" action="{{ action('BeyerController@index') }}">
        <input type="hidden" name="search" value="true">

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
              <button type="button" class="btn btn-info" id="addFieldButton">+</button>
            </div>
            @else
                <div class="col-sm-1 help-block">og</div>
            @endif
        </div>

        @endforeach

        <div class="form-group" id="searchButtonContainer">
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary btn-block">{{ trans('messages.search') }}</button>
            </div>
            <div class="col-sm-2">
                <a href="{{ action('BeyerController@index') }}" class="btn btn-default btn-block">{{ trans('messages.clear') }}</a>
            </div>
        </div>
    </form>

@section('script')

<script type="text/javascript">

$(function() {

    var fields = [
    @foreach ($selectOptions as $option)
        {placeholder: '{{ $option['placeholder'] }}'},
    @endforeach
    ];

    function addField(n) {
        var lastSet = $( ".field-set" ).last(),
            newSet = lastSet.clone(true),  // include event handlers
            currentIdx = parseInt(newSet.data('index'), 10),
            fieldSelect = newSet.find('#input' + currentIdx + 'field'),
            fieldValue = newSet.find('#input' + currentIdx + 'value'),
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
        $('#searchButtonContainer').before(newSet);

        // Update placeholder text
        $('.field-select').trigger('change');

        // Remove add button from last set
        lastSet.find('#addFieldButtonContainer').off('click').replaceWith('<div class="col-sm-1 help-block">og</div>');
    }

    function onFieldSelect(evt) {
        var fieldId = this.id.match('input([0-9]+)field')[1],
            selIdx = this.selectedIndex,
            textField = $('#input' + fieldId + 'value');

        textField.attr('placeholder', fields[selIdx].placeholder);
    }

    function activate() {
        $('#addFieldButton').on('click', addField);
        $('.field-select').off('change').on('change', onFieldSelect);
        $('.field-select').trigger('change');
    }

    activate();

});

</script>

@endsection