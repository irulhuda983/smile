Vue.component('vDialog', {
  template: '#dialog-template',
  props: {
    value: {
      type: Boolean,
      default: false
    },
    title: {
      type: [String, Number],
      default: null
    },
    noAction: {
      type: Boolean,
      default: false
    },
    width: {
      type: [Number, String],
      default: null
    }
  },
  mounted() {},
  computed: {
    classDlg() {
      return this.value ? { overlay: true, popubshow: true } : { overlay: true, popubshow: false }
    },
    dlgWidth() {
      return this.width ? 'width:' + this.width : null
    }
    // hasActionSlot() {
    //   return !!this.$slots.action;
    // },
  }
})
