@extends($page->layout)

@section('content')


    <form method="POST" action="{{ action('PageController@update', ['page' => $page->slug]) }}">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <textarea id="froala-editor" name="body">{!! $page->body !!}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">{{ trans('messages.update') }}</button>

        <a href="{{ action('PageController@show', ['page' => $page->slug]) }}" class="btn btn-default">{{ trans('messages.cancel') }}</a>

    </form>

@endsection

@section('script')

<script type="text/javascript">

  $(function() {
    $('textarea#froala-editor').froalaEditor({
      linkAutoPrefix: '',
      linkEditButtons: ['linkEdit', 'linkRemove'],
      linkInsertButtons: ['linkBack'],
      toolbarButtons: ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', 'insertHR', 'insertLink', 'insertImage', 'insertTable', 'undo', 'redo', 'clearFormatting', 'html']

    });
  });

</script>

@endsection