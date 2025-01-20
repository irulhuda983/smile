Vue.component('vTab', {
  template: '#tab-template',
  props: {
    value: {
      type: String,
      default: null
    },
    tabs:{
      type: Array,
      default:()=>[]
    },  
  },
  data() {
    const compData = {
      tabId:null,
      // arrTabs:[]
    }
    return compData
  },
  watch:{
    tabs:{
      immediate: true,
      handler: function (val) {
        if(this.value===null && val.length>0) {
          this.$emit('input',val[0].id)
        }
        // this.arrTabs=[...this.tabs]
      }
    },
    // value(val){
    //   if(val!==this.tabId) this.tabId=val
    // }
  },
  methods: {
    getliactive(val){
      return val===this.value?'tabactive':null
    },
    getlabelactive(val){
      return val===this.value?'active':'notactive'
    },
    ontabclick(val){
      if(val!==this.value){
        this.$emit('input',val)
        this.$emit('onchangetab',val)
      }
    }
  },
})
