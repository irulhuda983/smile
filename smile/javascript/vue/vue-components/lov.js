Vue.component('vLov', {
  template: '#lov-template',
  props: {
    title: {
      type: [String, Number],
      default: null
    },
    urlApi: {
      type: String,
      default: null
    },
    urlApiMethod: {
      type: String,
      default: 'get'
    },
    paramApi: {
      type: Object,
      default: () => ({})
    },
    items: {
      type: Array,
      default: () => []
    },
    headers: {
      type: Array,
      required: true,
      default: () => []
    },
    serverPaging: {
      type: Boolean,
      default: false
    },
    totalDataKey: {
      type: String,
      default: 'TOTAL_REC'
    },
    width: {
      type: [String, Number],
      default: null
    },
    densePaging: {
      type: Boolean,
      default: false
    },
    iconEdit: {
      type: Boolean,
      default: false
    }
  },
  data() {
    const compData = {
      lovItems: [],
      searchTxt: null,
      searchTxtTmp: null,
      options: {
        page: 1,
        itemsPerPage: 10,
        sortBy: '',
        sortMode: ''
      },
      totalData: 0,
      dlg: false,
      isLoading: false
    }
    return compData
  },
  computed: {
    dlgTitle() {
      return this.title ? this.title : this.api.title ? this.api.title : null
    },
    tableData() {
      return !this.serverPaging ? this.listLokalData(this.lovItems) : this.lovItems
    }
  },
  methods: {
    async openLov() {
      this.options = { ...this.options, page: 1, sortBy: '', sortMode: '' }
      this.dlg = true
      this.getData()
    },
    cariData() {
      this.options.page = 1
      this.searchTxt = this.searchTxtTmp
      if (!this.urlApi) {
        this.getData()
      }
    },
    getData() {
      try {
        if (!this.urlApi) {
          this.lovItems = this.items
          this.totalData = this.items.length
        } else {
          this.lovItems = []
          this.$emit('onloading', true)
          axios
            .get(this.urlApi, { ...this.paramApi, ...this.options })
            .then((res) => {
              if (res.data.ret === 0) {
                this.lovItems = [...res.data.DATA]
                this.totalData = res.data[this.totalDataKey] ? res.data[this.totalDataKey] : res.data.DATA.length
              }

              this.$emit('onloading', false)
            })
            .catch(() => {
              this.$emit('onloading', false)
            })
        }
        this.isLoading = false
      } catch (e) {
        console.log(e)
      }
    },
    listLokalData(val) {
      if (this.searchTxt) {
        const data = val.filter((x) => this.headers.some((y) => (x[y.value] + '').toUpperCase().includes(this.searchTxt.toUpperCase())))
        this.totalData = data.length
        return this.doOrderItems(data).slice(
          (this.options.page ? this.options.page - 1 : 0) * (this.options.itemsPerPage ? this.options.itemsPerPage : 10), // start Index
          (this.options.page ? this.options.page : 1) * (this.options.itemsPerPage ? this.options.itemsPerPage : 10) - 1 // jumlah data per page
        )
      } else {
        this.totalData = val.length
        return this.doOrderItems(val).slice(
          (this.options.page ? this.options.page - 1 : 0) * (this.options.itemsPerPage ? this.options.itemsPerPage : 10), // start Index
          (this.options.page ? this.options.page : 1) * (this.options.itemsPerPage ? this.options.itemsPerPage : 10) - 1 // jumlah data per page
        )
      }
    },
    doOrderItems(val) {
      if (this.options.sortBy) {
        const sortProp = !this.options.sortBy ? null : this.options.sortBy === '' ? null : this.options.sortBy
        if (sortProp) {
          const sortDesc = !this.options.sortMode ? 1 : this.options.sortMode === '' || this.options.sortMode === 'DESC' ? -1 : 1
          let dataSort = [...val]
          // console.log(sortDesc)
          dataSort.sort((a, b) => {
            let nameA = a[sortProp] ? a[sortProp].toString().toUpperCase() : '' // ignore upper and lowercase
            let nameB = b[sortProp] ? b[sortProp].toString().toUpperCase() : '' // ignore upper and lowercase

            nameA = sortDesc === -1 && nameA === '' ? 'Z' : nameA
            nameB = sortDesc === -1 && nameB === '' ? 'Z' : nameB
            if (nameA < nameB) {
              return 1 * sortDesc
            }
            if (nameA > nameB) {
              return -1 * sortDesc
            }

            // names must be equal
            return 0
          })
          return dataSort
        }
      }
      return val
    }
  }
})
