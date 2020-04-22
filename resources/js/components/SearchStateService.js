const SearchStateService = {

  init (source) {
    this.params = new URLSearchParams(source)
  },

  asObject () {
    return Object.fromEntries(this.params)
  },

  parseQuery () {
    const fields = []

    const parts = (this.params.get('q') || '').split(/ (AND|OR) /)

    parts.forEach(currentValue => {
      if (currentValue.match(/AND|OR/)) {
        fields[fields.length - 1].boolean = currentValue
      } else if (currentValue.length) {
        const qps = currentValue.match(/(.+?) (.+?) (.+)/)
        if (qps) {
          fields.push({
            field: qps[1],
            operator: qps[2],
            value: qps[3],
            boolean: null,
          })
        }
      }
    })

    console.log('FIELDS', fields)

    return fields
  },

  parseOrder () {
    return this.params.getAll('order').map(val => {
      val = val.split(':')
      return { key: val[0], direction: val[1] }
    })
  },

  serializeQuery (queryObj) {
    let query = queryObj
      .filter(q => q.value !== '' || ['isnull', 'notnull'].indexOf(q.operator) !== -1)
      .map(q => `${q.field} ${q.operator} ${q.value} ${q.boolean.toUpperCase()}`)
      .join(' ')
    if (query.endsWith(' AND')) query = query.substr(0, query.length - 4)
    if (query.endsWith(' OR')) query = query.substr(0, query.length - 3)

    return query
  },

  pushState () {
    let params = this.params.toString()
    if (params) params = '?' + params

    window.history.pushState(null, '', window.location.pathname + params)
  },

  setOrder (order) {
    console.log('setOrder', order)

    // this.order = order.split(',').map(item => {
    //   const t = item.split(':')
    //   return {key: t[0], direction: t.length ? t[1] : 'asc'}
    // })
    this.params.set('order', order)

    this.pushState()
  },

  setQuery (params) {
    const q = this.serializeQuery(params.q)
    console.log('setQuery', q, params.advanced)

    if (q === '') {
      this.params.delete('q')
    } else {
      this.params.set('q', q)
    }

    if (!params.advanced) {
      this.params.delete('advanced')
    } else {
      this.params.set('advanced', params.advanced)
    }

    this.pushState()
  },
}

export default SearchStateService
