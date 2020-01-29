import Plugin from '@ckeditor/ckeditor5-core/src/plugin'
import ButtonView from '@ckeditor/ckeditor5-ui/src/button/buttonview'
import PendingActions from '@ckeditor/ckeditor5-core/src/pendingactions';

let isDirty = false;

export default class SaveButton extends Plugin {

  init() {
    const editor = this.editor;
    const config = editor.config.get( 'saveButton' ) || {};

    editor.ui.componentFactory.add( 'saveButton', locale => {
      this.view = new ButtonView( locale );

      this.view.set( {
        withText: true,
        label: 'Lagre',
        tooltip: false,
        class: 'ck-savebutton ck-savebutton-success',
        isEnabled: false,
      } );

      // console.log(view)
      // console.log(view.template)
      // console.log(view.template.attributes)

      //view.template.attributes.class = ['ck-savebutton']

      // Callback executed once the image is clicked.
      this.view.on( 'execute', () => {
        config.save(editor)
      } );

      return this.view;
    } );

    config.init(this)

  }

  setSaving () {
    this.view.set({
      class: 'ck-savebutton ck-savebutton-success',
      tooltip: false,
      isEnabled: false,
      label: 'Lagrer...',
    })
  }


  setDirty () {
    this.view.set({
      class: 'ck-savebutton ck-savebutton-success ck-savebutton-active',
      tooltip: false,
      isEnabled: true,
      label: 'Lagre',
    })
  }

  setSaved () {
    this.view.set({
      class: 'ck-savebutton ck-savebutton-success',
      isEnabled: false,
      label: 'Lagret!',
      tooltip: false,
    })
  }

  setFailed () {
    this.view.set({
      isEnabled: true,
      class: 'ck-savebutton ck-savebutton-error',
      label: 'Lagring feilet!',
      tooltip: 'Trykk for å prøve igjen.',
    })
  }
}
