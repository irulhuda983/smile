      <?
			if(isset($errormsg))
      {
            echo "<font color=#ff0000><b>".$errormsg."</b></font>";
      }
      ?>
			<input type="hidden" name="mid" value="<?=$mid;?>">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="hidemainmenu" value="0" />
  	</form>
		
		<? 
    if($pagetype=="popup")  // popup tidak ada action menu
    {
    ?>
		<div id="clear-bottom-popup"></div>
    </div> 
    
    <div id="footer-popup">
      <p class="lft"><a href="javascript:window.close()"><img src="../images/action_stop.gif" border="0" align="absmiddle"> Close</a></p>
    </div>
		<? 
		}
		?>
        <div id="loading-mask"></div>
<div id="loading">
  <div class="loading-indicator">
    Loading...
  </div>
</div>
</body>
</html>