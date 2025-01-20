<?php
include_once __DIR__."/../../../includes/conf_global.php";
$pathcomponents = (!empty($_SERVER['HTTPS'])) ? "https://".$HTTP_HOST."/javascript/vue/vue-components/":"http://".$HTTP_HOST."/javascript/vue/vue-components/";
?>
<!-- componenet Dialog-->
<script type="text/x-template" id="tab-template">
  <div class="pc-tab">
    <nav>
      <ul>
        <li v-for="(tab,index) in tabs" :class="getliactive(tab.id)" @click="ontabclick(tab.id)" :key="tab.id">
          <label :class="getlabelactive(tab.id)">{{tab.title}}</label>
        </li>
      </ul>
    </nav>
    <slot name="content">
      <section style="border-top:1px solid #ddd"> 
        <slot></slot>
      </section>
    </slot>
  </div>
</script>
<script src="<?=$pathcomponents;?>tab.js"></script>