<template>
  <div>
    <p v-if="!exists" class="alert alert-success">
      OBS: Du er i ferd med å opprette en ny side.
    </p>

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
export default {
  name: 'ckeditor',
  components: {
    'ckeditor5': CKEditorVue.component,
  },
  props: {
    data: String,
    imageUploadUrl: String,
    updateUrl: String,
    lockUrl: String,
    unlockUrl: String,
    csrfToken: String,
    existed: Boolean,
  },
  created () {
    this.onBeforeUnload = (evt) => {
      if (this.lock) {
        // Cancel the event
        evt.preventDefault();
        // Chrome requires returnValue to be set
        evt.returnValue = '';
      }
    }
    this.onUnload = () => {
      if (this.lock) {
        console.log('Unlocking')

        const formData = new FormData();
        formData.append('lock', this.lock);
        formData.append('_token', this.csrfToken);

        navigator.sendBeacon(this.unlockUrl, formData)
      }
    }
    window.addEventListener('beforeunload', this.onBeforeUnload)
    window.addEventListener('unload', this.onUnload);
  },
  destroyed () {
    window.removeEventListener('beforeunload', this.onBeforeUnload)
    // window.removeEventListener('unload', this.onUnload)
  },
  methods: {
    onEditorInput () {
      if (this.exists && this.lock === null) {
        this.lock = 'obtaining'
        this.$http.post(this.lockUrl).then(res => {
          console.log(res.data)
          this.lock = res.data.lock_id
          saveButton.setDirty()
        }).catch(err => {
          const msg = `Beklager, siden er låst av ${err.response.data.user}. Prøv igjen senere.`
          this.lock = null
          saveButton.setFailed('locked', msg)
          alert(msg)
        })
      } else if (!this.exists) {
        saveButton.setDirty()
      }
    },

    onInitSaveButton (btn) {
      saveButton = btn
    },

    save (editor) {
      saveButton.setSaving()
      this.$http.post(this.updateUrl, {
        body: this.editorData,
        lock: this.lock,
      }).then(res => {
        saveButton.setSaved()
        this.exists = true
        this.lock = null
      }).catch(err => {
        saveButton.setFailed('savefailed', err)
      })
    },
  },
  data() {
    return {
      editor: ClassicEditor,
      editorData: this.data,
      lock: null,
      exists: this.existed,
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
            {
                model: 'smallParagraph',
                view: {
                    name: 'p',
                    classes: 'footnote'
                },
                title: 'Fotnotetekst',
                class: 'ck-heading_small_paragraph',

                // It needs to be converted before the standard 'paragraph'.
                converterPriority: 'high'
            }
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
