Vue.component('vTable', {
  template: '#table-template',
  props: {
    value: {
      type: Array,
      default: () => []
    },
    hideFooter: {
      type: Boolean,
      default: false
    },
    hidePaging: {
      type: Boolean,
      default: false
    },
    headers: {
      type: Array,
      default: () => []
    },
    options: {
      type: Object,
      default: () => ({
        page: 1,
        itemsPerPage: 10,
        sortBy: '',
        sortMode: ''
      })
    },
    itemTotal: {
      type: Number,
      default: 0
    },
    selectionValue: {
      type: String,
      default: null
    },
    clickOnRow: {
      type: Boolean,
      default: false
    },
    rowBgcolor: {
      type: Function,
      default: null
    },
    rowColor: {
      type: Function,
      default: null
    },
    densePaging: {
      type: Boolean,
      default: false
    }
  },
  data() {
    const compData = {
      page: 1,
      itemsPerPage: 10,
      sortBy: '',
      sortMode: '',
      toogleSelectedValue: false
    }
    compData.page = this.options.page ? this.options.page : compData.page
    compData.itemsPerPage = this.options.itemsPerPage ? this.options.itemsPerPage : compData.itemsPerPage
    compData.sortBy = this.options.sortBy ? this.options.sortBy : compData.sortBy
    compData.sortMode = this.options.sortMode ? this.options.sortMode : compData.sortMode
    return compData
  },
  computed: {
    headersRow1() {
      return this.headers.filter((x) => x.row1)
    },
    headersRow2() {
      return this.headers.filter((x) => (!x.row1 && !x.row2) || x.row2)
    },
    headersItems() {
      return this.headers.filter((x) => !!x.value || x.action || x.func)
    },
    totalData() {
      return this.itemTotal === 0 && this.value.length > 0 ? this.value.length : this.itemTotal
    },
    pageCount() {
      return Math.ceil(this.totalData / this.itemsPerPage)
    }
  },
  watch: {
    options: {
      true: true,
      handler: function (val) {
        if (val.page !== this.page) this.page = val.page
        if (val.itemsPerPage !== this.itemsPerPage) this.itemsPerPage = val.itemsPerPage
        if (val.sortBy !== this.sortBy) this.sortBy = val.sortBy
        if (val.sortMode !== this.sortMode) this.sortMode = val.sortMode
      }
    },
    page(val) {
      if (val !== this.options.page) {
        this.$emit('update:options', { ...this.options, page: val })
        this.$emit('onchangeoptions')
      }
    },
    itemsPerPage(val) {
      if (val !== this.options.itemsPerPage) {
        this.$emit('update:options', { ...this.options, itemsPerPage: val })
        this.$emit('onchangeoptions')
      }
    }
    // sortBy(val) {
    //   if (val !== this.options.sortBy) this.$emit('update:options', { ...this.options, sortBy: val })
    // },
    // sortMode(val) {
    //   if (val !== this.options.sortMode) this.$emit('update:options', { ...this.options, sortMode: val })
    // }
  },
  methods: {
    valHelper(val, header) {
      if (header.helper) {
        const mapHelperList = ['date', 'decimal']
        if (!mapHelperList.findIndex((x) => x === header.helper) < 0) return val
        else if (header.helperOption)
          return (
            // header.helper==='date'?formatDate(val,header.helperOption):
            header.helper === 'decimal' ? formatDecimal(val, header.helperOption) : val
          )
        else
          return (
            // header.helper==='date'?formatDate(val):
            header.helper === 'decimal' ? formatDecimal(val) : val
          )
      } else return val
    },
    getClassAction(val) {
      let a = { fa: true }
      a['fa-' + val] = true
      return a
    },
    getColorAction(val) {
      return !val
        ? '#5c90e4'
        : (val === 'secondary' && 'rgb(66, 184, 221)') ||
            (val === 'success' && 'rgb(28, 184, 65)') ||
            (val === 'error' && 'rgb(202, 60, 60)') ||
            (val === 'warning' && 'rgb(223, 117, 20)') ||
            '#5c90e4'
    },
    getColorTip(val){
      return !val
      ? 'background-color:#5c90e4'
      : (val === 'secondary' && 'background-color:rgb(66, 184, 221,0.7)') ||
          (val === 'success' && 'background-color:rgb(28, 184, 65,0.7)') ||
          (val === 'error' && 'background-color:rgb(202, 60, 60,0.7)') ||
          (val === 'warning' && 'background-color:rgb(223, 117, 20,0.7)') ||
          'background-color:#5c90e4'
    },
    onShortBy(val) {
      if (val !== this.sortBy) {
        this.sortBy = val
        this.sortMode = 'ASC'
        this.$emit('update:options', { ...this.options, sortBy: val, sortMode: 'ASC' })
      } else if (this.sortMode === '') {
        this.sortMode = 'ASC'
        this.$emit('update:options', { ...this.options, sortMode: 'ASC' })
      } else if (this.sortMode === 'ASC') {
        this.sortMode = 'DESC'
        this.$emit('update:options', { ...this.options, sortMode: 'DESC' })
      } else if (this.sortMode === 'DESC') {
        this.sortMode = ''
        this.$emit('update:options', { ...this.options, sortMode: '' })
      }
      this.$emit('onchangeoptions')
    },
    rowStyle(item) {
      const stylePointer = this.clickOnRow ? 'cursor:pointer;' : ''
      const styleBgColor = !this.rowBgcolor ? '' : this.rowBgcolor(item) ? 'background-color:' + this.rowBgcolor(item) + ';' : ''
      const styleColor = !this.rowColor ? '' : this.rowColor(item) ? 'color:' + this.rowColor(item) + ';' : ''
    
      if (stylePointer + styleBgColor + styleColor !== '') return stylePointer + styleBgColor + styleColor
      else return null
    },
    isShowItemAct(itemAct, item) {
      if (itemAct.condition) return itemAct.condition(item)
      else return true
    }
  }
})
