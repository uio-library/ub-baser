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
import BoldPlugin from '@ckeditor/ckeditor5-basic-styles/src/bold'
import Strikethrough from '@ckeditor/ckeditor5-basic-styles/src/strikethrough'
import Code from '@ckeditor/ckeditor5-basic-styles/src/code'
import EssentialsPlugin from '@ckeditor/ckeditor5-essentials/src/essentials'
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
import ItalicPlugin from '@ckeditor/ckeditor5-basic-styles/src/italic'
import Link from '@ckeditor/ckeditor5-link/src/link'
import LinkPlugin from '@ckeditor/ckeditor5-link/src/link'
import List from '@ckeditor/ckeditor5-list/src/list'
import ParagraphPlugin from '@ckeditor/ckeditor5-paragraph/src/paragraph'
import PasteFromOffice from '@ckeditor/ckeditor5-paste-from-office/src/pastefromoffice'
import SimpleUploadAdapter from '@ckeditor/ckeditor5-upload/src/adapters/simpleuploadadapter'
import Table from '@ckeditor/ckeditor5-table/src/table'
import TableToolbar from '@ckeditor/ckeditor5-table/src/tabletoolbar'
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
          BoldPlugin,
          Code,
          EssentialsPlugin,
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
          ItalicPlugin,
          Link,
          LinkPlugin,
          List,
          ParagraphPlugin,
          PasteFromOffice,
          SaveButton,
          SimpleUploadAdapter,
          Strikethrough,
          Table,
          TableToolbar,
          UploadAdapter,
        ],

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
          toolbar: [ 'imageTextAlternative' ],
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

      },
    }
  }
}
</script>
