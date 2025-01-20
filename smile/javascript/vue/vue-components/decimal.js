Vue.component('vDecimal', {
  template: '#decimal-template',
  props: {
    value: {
      type: [String, Number],
      default: null
    },
    bgRequired: {
      type: Boolean,
      default: false
    },
    decimal: {
      type: Number,
      default: 0
    }
  },
  inheritAttrs: false,
  mounted() {},
  data() {
    const data = {
      focus: {
        amount: false
      },
      amount_: '0'
    }
    data.amount_ = this.value
    return data
  },
  computed: {
    amount: {
      get() {
        return mask(this.amount_, Number(this.decimal), this.focus.amount)
      },
      set(value) {
        this.amount_ = unmask(value)
      }
    },
    styledec(){
      if(this.$attrs.style)
        return this.$attrs.style + ";text-align:right;"
      else
        return "text-align:right;"
    }
  },
  watch: {
    value: {
      immediate: true,
      handler(val) {
        // console.log('auu',val)
        if(val!=this.amount_) this.amount_=val
        // this.amount_ = (isDecimal(val) && val) || '0'
      }
    },
    amount_(val) {
      this.$emit('input', val)
    }
  },
  methods: {
    onlyForCurrency(evt) {
      let keyCode = evt.keyCode ? evt.keyCode : evt.which
      // only allow number and one dot
      if ( !(keyCode >= 48 && keyCode <= 57) && keyCode !== 46) {
        // 46 is dot
        // console.log('au')
        evt.preventDefault()
      }
    }
  }
})
