<?php
include_once __DIR__."/../../../includes/conf_global.php";
$rootpath = (!empty($_SERVER['HTTPS'])) ? "https://".$HTTP_HOST : "http://".$HTTP_HOST;
$pathcomponents = (!empty($_SERVER['HTTPS'])) ? "https://".$HTTP_HOST."/javascript/vue/vue-components/":"http://".$HTTP_HOST."/javascript/vue/vue-components/";
?>
<!-- table component -->
<script type="text/x-template" id="table-template">
  <div>
    <div class="div-row" style="padding-top: 4px;">
      <div class="div-col" style="padding: 2px 4px;">
        <slot name="filterleft">
        </slot>
      </div>
      <div class="div-col-right" style="padding: 2px 4px;font-weight: bold;">
        <slot name="filterright">
        </slot>
      </div>
    </div>
    <div class="div-data">
      <table class="table-data">
        <thead class="hr-single-double">
          <slot name="header">
            <tr v-if="headersRow1.length>0">
              <th
                v-for="(header, index) in headersRow1"
                :key="`thnv${index}`"
                :colspan="header.colspan?header.colspan:null"
                :rowspan="header.rowspan?header.rowspan:null"
                :align="header.align?header.align:null"
                style="border: 1px solid #eeeeee;"
              >
                {{header.text?header.text:null}}
              </th>
            </tr>
            <tr v-if="headersRow2.length>0">
              <th  style="border: 1px solid #eeeeee;vertical-align: middle;text-align: center" v-if="selectionValue!==null">
                <input type="checkbox" v-model="toogleSelectedValue" @change="$emit('tooglechecked',toogleSelectedValue)">
              </th>
              <th
                v-for="(header, index) in headersRow2"
                :key="`th${index}`"
                :align="header.align?header.align:null"
                style="border: 1px solid #eeeeee;cursor: pointer;"
                @click="onShortBy(header.value)"
              >
                {{header.text}}
                <img
                  class="order-icon"
                  :src="sortBy!==
                    header.value?'<?=$rootpath;?>/images/sort_both.png':(
                      !sortMode?'<?=$rootpath;?>/images/sort_both.png':(
                        sortMode==='DESC'?'<?=$rootpath;?>/images/sort_desc.png':'<?=$rootpath;?>/images/sort_asc.png'
                      )
                    )"
                  v-if="!header.noSort && !header.action" />
              </th>
            </tr>
          </slot>
        </thead>
        <tbody>
          <tr class="nohover-color" v-if="value.length===0">
            <td :colspan="headersItems.length" style="text-align: center">-- Data tidak ditemukan/ belum ada --</td>
          </tr>
          <template v-for="(item, index) in value">
            <slot name="item" v-bind:item="item">
              <tr :key="`tr${index}`" :style="rowStyle(item)" @click="$emit('onclickrow',item)">
                <td v-if="selectionValue!==null" style="text-align: center"><input type="checkbox" :checked="item[selectionValue]" @change="$emit('checked',item)" ></td>
                <td
                  v-for="(header, indexHeader) in headersItems"
                  :key="`td${indexHeader}`"
                  :align="header.align?header.align:null"
                  :style="header.align?'text-align:'+header.align:null"
                >
                  <slot :name="`item.${header.value}`" :item="item" :index="index">
                    <template v-if="header.action">
                      <template v-for="(itemAct,indexAct) in header.icon" >
                        <div class="tooltip" v-if="!itemAct.noAction && isShowItemAct(itemAct,item) && !itemAct.desc" >
                          <span 
                            :style="`cursor:pointer;color:${getColorAction(itemAct.color)};margin:0px 1px;`" 
                          >
                            <i
                              :key="`iconAct${indexAct}`" 
                              :class="getClassAction(itemAct.icon)" 
                              @click="$emit('onactitem',{act:itemAct.name, item: item})"
                            >
                            </i>
                          </span>
                          <span class="tooltiptext" :style="getColorTip(itemAct.color)">{{itemAct.tipText}}</span>
                        </div>
                        <div class="tooltip" v-if="!itemAct.noAction && isShowItemAct(itemAct,item) && itemAct.desc" >
                          <span 
                            :style="`cursor:pointer;color:${getColorAction(itemAct.color)};margin:0px 1px;padding:8px;background-color:lightblue;border-radius:4px;color:black;`"                             
                            @click="$emit('onactitem',{act:itemAct.name, item: item})"
                          >{{itemAct.desc}}</span>
                          <span class="tooltiptext" :style="getColorTip(itemAct.color)">{{itemAct.tipText}}</span>
                        </div>
                        <div class="tooltip" v-if="itemAct.noAction && isShowItemAct(itemAct,item)" >
                          <span 
                            :style="`color:${getColorAction(itemAct.color)};;margin:0px 1px;`" 
                          >
                            <i
                              :key="`iconAct${indexAct}`" 
                              :class="getClassAction(itemAct.icon)" 
                            ></i>
                          </span>
                          <span class="tooltiptext"  :style="getColorTip(itemAct.color)">{{itemAct.tipText}}</span>
                        </div>
                      </template>
                    </template>
                    <template v-else-if="header.currency">
                        {{rupiah(item[header.value])}}
                    </template>
                    <template v-else-if="header.func">
                      {{ valHelper(header.func(item),header) }}
                    </template>
                    <template v-else-if="header.indicator">
                        <span v-if="item[header.value] >= 100" style="width:80%;height:10px;background-color:green;display:block;margin:2px auto;color:white;text-align:center;padding:2px;border-radius:24px;">{{item[header.value]}}%</span>
                        <span v-else-if="item[header.value] >= 51 && item[header.value] < 100" style="width:80%;height:10px;background-color:yellow;color:black;display:block;margin:2px auto;text-align:center;padding:2px;border-radius:24px;">{{item[header.value]}}%</span>
                        <span v-else style="width:80%;height:10px;background-color:red;display:block;margin:2px auto;color:white;text-align:center;padding:2px;border-radius:24px;">{{item[header.value]}}%</span>
                    </template>
                    <template v-else-if="header.titlecase">
                        <span>{{titleCase(item[header.value])}}</span>
                    </template>
                    <span v-else>
                      {{ valHelper(item[header.value],header) }} 
                    </span>
                  </slot>
                </td>
              </tr>
            </slot>
          </template>
        </tbody>
      </table>
    </div>
    <div class="div-row" style="padding:4px 4px" v-if="!hidePaging">
      <div class="div-col">
        <span style="vertical-align: middle">Halaman</span>
        <a href="#" title="First Page" class="pagefirst" @click="page=1" v-if="!densePaging"><<</a>
        <a href="#" title="Previous Page" class="pagenext" @click="page=page<=1?1:page-1">Prev</a>
        <input type="text" class="pageinput" v-model="page" />
        <a href="#" title="Next Page" class="pagenext" @click="page=page>=pageCount?pageCount:page+1">Next</a>
        <a href="#" title="Last Page" class="pagelast" @click="page=pageCount"  v-if="!densePaging">>></a>
        <span style="vertical-align: middle" id="span_info_halaman">dari {{pageCount}} halaman</span>
        <input type="hidden" id="pages" />
      </div>
      <div class="div-col-right" style="padding:4px 8px">
        <span style="vertical-align: middle" v-if="!densePaging">Menampilkan item ke {{ (page-1)*itemsPerPage+1}} sampai dengan {{page*itemsPerPage}} dari {{totalData}} items</span>
        <span style="vertical-align: middle" v-else>Total {{totalData}} items</span>
        &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
        <span style="vertical-align: middle">Tampilkan</span>
        <select style="width: 46px; height: 20px" v-model="itemsPerPage">
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="15">15</option>
          <option value="20">20</option>
          <option value="25">25</option>
          <option value="50">50</option>
        </select>
        <span style="vertical-align: middle">item per halaman</span>
      </div>
    </div>
    <div id="div_footer" class="div-footer" v-if="!hideFooter">
      <div class="div-footer-content">
        <slot name="footer">
        </slot>
      </div>
    </div>
  </div>
</script>
<script src="<?=$pathcomponents;?>table.js"></script>