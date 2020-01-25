<template>
  <div v-once>
    <div ref="me" :id="this.id" style="width: 100%; height: 600px; background: black;"></div>
  </div>
</template>

<script>

import OpenSeaDragon from 'openseadragon'

export default {
  name: 'image-viewer',
  props: {
    id: String,
    src: String,
    tileSrc: String,
  },
  mounted () {

    if (!this.tileSrc) {
      console.warn('image-viewer: no tile-src specified!')
      return
    }

    OpenSeaDragon({
      id: this.id,
      tileSources: this.tileSrc,
      debugMode: false,
      showNavigationControl: true,

      // We don't want to allow zooming outside of what's filling the window
      minZoomImageRatio: 1.0,

      // The percentage (as a number from 0 to 1) of the source image which must
      // be kept within the viewport. If the image is dragged beyond that limit,
      // it will 'bounce' back until the minimum visibility ratio is achieved.
      visibilityRatio: 1.0,
    })
    .addHandler('open', (target) => {
      const cw = this.$refs.me.clientWidth
      const src = target.eventSource.source  // A DziTileSource object
      const tx =  src.height / src.width * cw
      this.$refs.me.style.height = tx + 'px'
    })
  }
}
</script>

<style scoped>

</style>
