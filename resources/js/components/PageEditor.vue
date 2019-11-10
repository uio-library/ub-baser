<template>
  <div>
    <input type="hidden" name="body" v-model="editorData">
    <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
  </div>
</template>

<script>

import ClassicEditor from '@ckeditor/ckeditor5-build-classic'

const ckeditor = () => import('@ckeditor/ckeditor5-vue').then(module => {
  return module.component
})

export default {
  name: 'page-editor',
  components: {
    ckeditor,
  },
  props: {
    data: String,
  },
  data() {
    return {
      editor: ClassicEditor,
      editorData: this.data,
      editorConfig: {
        // The configuration of the editor.
      }
    }
  }
}
</script>

<style scoped>

</style>
