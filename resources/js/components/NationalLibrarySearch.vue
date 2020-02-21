<template>
  <a :href="'https://www.nb.no/search?' + searchQueryString" class="btn btn-outline-success btn-sm">
    <em class="fa fa-search"></em>
    Søk etter fulltekst i NB
    <span v-if="state === 'complete'">({{ responseOverview.totalElements}} treff)</span>
  </a>
</template>

<script>
export default {

  name: 'NationalLibrarySearch',

  props: {
    baseUrl: String,
    query: Object,
  },

  data () {
    return {
      searchQueryString: this.buildSearchQueryString(),
      responseOverview: {},
      state: 'pending',
    }
  },

  methods: {
    buildApiQueryString () {

      // Should we add profile=wwwnbno ??

      const magicMap = {
        'name': 'api_namecreators',
        'title': 'api_title',
        'series': 'api_series',
      }
      const q = this.query
      let url = `q=${q.query}&filter=contentClasses%3Ajp2&`
      return url + Object.keys(q.filters).map(key => {
        let value = q.filters[key]
        if (key == 'date') {
          value = `[${value[0]} TO ${value[1]}]`
        }
        if (magicMap[key] !== undefined) {
          key = magicMap[key]
        }
        return 'filter=' + encodeURIComponent(`${key}:${value}`)
      }).join('&')
    },

    buildSearchQueryString () {
      const q = this.query
      let url = `q=${q.query}&filter=contentClasses%3Ajp2&`
      return url + Object.keys(q.filters).map(key => {
        let value = q.filters[key]
        if (key == 'date') {
          return `fromDate=${value[0]}&toDate=${value[1]}`
        }
        return key + '=' + encodeURIComponent(value)
      }).join('&')
    },
  },

  mounted () {
    console.log(this.query)

    this.$http.get(this.baseUrl + '?' + this.buildApiQueryString())
      .then(res => {
        console.log('Response:', res.data)
        this.responseOverview = res.data.page
        this.state = 'complete'
      })
  },

}
</script>

<style lang="css" scoped>
</style>
