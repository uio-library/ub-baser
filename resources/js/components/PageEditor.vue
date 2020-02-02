<template>
  <div>
    <ckeditor5
      :editor="editor"
      v-model="editorData"
      :config="editorConfig"
      @input="onEditorInput"
    ></ckeditor5>
    <input type="hidden" name="body" v-model="editorData">
  </div>

</template>
<script>

import CKEditorVue from '@ckeditor/ckeditor5-vue'
import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor'
import Bold from '@ckeditor/ckeditor5-basic-styles/src/bold'
import Strikethrough from '@ckeditor/ckeditor5-basic-styles/src/strikethrough'
import Code from '@ckeditor/ckeditor5-basic-styles/src/code'
import Essentials from '@ckeditor/ckeditor5-essentials/src/essentials'
import Heading from '@ckeditor/ckeditor5-heading/src/heading'
import Highlight from '@ckeditor/ckeditor5-highlight/src/highlight'
import Image from '@ckeditor/ckeditor5-image/src/image'
import ImageCaption from '@ckeditor/ckeditor5-image/src/imagecaption'
import ImageResize from '@ckeditor/ckeditor5-image/src/imageresize'
import ImageStyle from '@ckeditor/ckeditor5-image/src/imagestyle'
import ImageTextAlternative from '@ckeditor/ckeditor5-image/src/imagetextalternative'
import ImageToolbar from '@ckeditor/ckeditor5-image/src/imagetoolbar'
import ImageUpload from '@ckeditor/ckeditor5-image/src/imageupload'
import Indent from '@ckeditor/ckeditor5-indent/src/indent'
import IndentBlock from '@ckeditor/ckeditor5-indent/src/indentblock'
import Italic from '@ckeditor/ckeditor5-basic-styles/src/italic'
import Link from '@ckeditor/ckeditor5-link/src/link'
import List from '@ckeditor/ckeditor5-list/src/list'
import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph'
import PasteFromOffice from '@ckeditor/ckeditor5-paste-from-office/src/pastefromoffice'
import SimpleUploadAdapter from '@ckeditor/ckeditor5-upload/src/adapters/simpleuploadadapter'
import Table from '@ckeditor/ckeditor5-table/src/table'
import TableToolbar from '@ckeditor/ckeditor5-table/src/tabletoolbar'
import TextTransformation from '@ckeditor/ckeditor5-typing/src/texttransformation';
import UploadAdapter from '@ckeditor/ckeditor5-adapter-ckfinder/src/uploadadapter'

// Local plugins
import SaveButton from './SaveButton'

/**
 * Note to future me: For plugins to work, they must be ES 6 classes,
 * so we must make sure they are not transpiled by Babel. For this reason,
 * we have disabled IE11 support in .babelrc
 */

let saveButton = null
let dirty = false
export default {
  name: 'ckeditor',
  components: {
    'ckeditor5': CKEditorVue.component,
  },
  props: {
    data: String,
    imageUploadUrl: String,
    updateUrl: String,
    csrfToken: String,
  },
  created () {
    window.addEventListener('beforeunload', this.onUnload)
  },
  destroyed () {
    window.removeEventListener('beforeunload', this.onUnload)
  },
  methods: {
    onUnload (evt) {
      if ( dirty ) {
        // Cancel the event
        evt.preventDefault();
        // Chrome requires returnValue to be set
        evt.returnValue = '';
      }
    },
    onEditorInput () {
      dirty = true
      saveButton.setDirty()
    },

    onInitSaveButton (btn) {
      saveButton = btn
    },

    save (editor) {
      saveButton.setSaving()
      this.$http.post(this.updateUrl, {
        body: this.editorData,
      }).then(res => {
        saveButton.setSaved()
        dirty = false
      }).catch(err => {
        saveButton.setFailed()
      })
    },
  },
  data() {
    return {
      editor: ClassicEditor,
      editorData: this.data,
      editorConfig: {
        plugins: [
          Bold,
          Code,
          Essentials,
          Heading,
          Highlight,
          Image,
          ImageCaption,
          ImageStyle,
          ImageResize,
          ImageTextAlternative,
          ImageToolbar,
          ImageUpload,
          Indent,
          IndentBlock,
          Italic,
          Link,
          List,
          Paragraph,
          PasteFromOffice,
          SaveButton,
          SimpleUploadAdapter,
          Strikethrough,
          Table,
          TableToolbar,
          TextTransformation,
          UploadAdapter,
        ],

        heading: {
          options: [
            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
            { model: 'heading2', view: 'h2', title: 'Heading 1', class: 'ck-heading_heading2' },
            { model: 'heading3', view: 'h3', title: 'Heading 2', class: 'ck-heading_heading3' },
            { model: 'heading4', view: 'h4', title: 'Heading 3', class: 'ck-heading_heading4' },
            { model: 'heading5', view: 'h5', title: 'Overskrift 4', class: 'ck-heading_heading5' },
          ],
        },

        toolbar: {
          items: [
            'saveButton',
            '|',
            'heading',
            '|', 'outdent', 'indent',
            '|', 'bulletedList', 'numberedList',
            '|',
            'bold',
            'italic',
            'code',
            'strikethrough',
            'highlight',
            '|',
            'link',
            'imageUpload',
            '|',
            'undo',
            'redo',
            '|',
            'insertTable',
          ]
        },

        image: {
          toolbar: [ 'imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight' ],

          styles: [
            // This option is equal to a situation where no style is applied.
            'full',

            // This represents an image aligned to the left.
            'alignLeft',

            // This represents an image aligned to the right.
            'alignRight'
          ],
        },

        saveButton: {
          init: this.onInitSaveButton,
          save: this.save,
        },

        table: {
          contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
        },

        simpleUpload: {
          uploadUrl: this.imageUploadUrl,
          headers: {
            'X-CSRF-TOKEN': this.csrfToken,
          },
        },

        typing: {
          transformations: {
            remove: [
              // Do not use the transformations from the
              // 'symbols' and 'quotes' groups.
              'quotes',
            ],

            extra: [
              // Add some custom transformations – e.g. for emojis.
              { from: ':)', to: '🙂' },
              { from: ':+1:', to: '👍' },
              { from: ':tada:', to: '🎉' },

              // You can also define patterns using regular expressions.
              // Note: The pattern must end with `$` and all its fragments must be wrapped
              // with capturing groups.
              // The following rule replaces ` "foo"` with ` «foo»`.
              {
                from: /(^|\s)(")([^"]*)(")$/,
                to: [ null, '«', null, '»' ]
              },
            ],
          }
        }
      },
    }
  }
}
</script>
