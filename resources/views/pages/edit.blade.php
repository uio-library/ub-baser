@extends($page->layout)

@section('content')


    <form method="POST" action="{{ route($updateRoute) }}">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <textarea id="froala-editor" name="body">{!! $page->body !!}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">{{ trans('messages.update') }}</button>

    </form>

@endsection

@section('script')

<script type="text/javascript">

  $(function() {
    $('textarea#froala-editor').froalaEditor({
      linkAutoPrefix: '',
      linkEditButtons: ['linkEdit', 'linkRemove'],
      linkInsertButtons: ['linkBack']
    });
  });

</script>

@endsection