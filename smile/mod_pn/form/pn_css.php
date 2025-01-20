<style>
	/** class container & body */
	div.div-container {
		display: block;
		width: 100%;
	}
	div.div-header{
		min-width: 700px;
	}
	div.div-body{
		box-sizing:border-box;
    -moz-box-sizing:border-box; /* Firefox */
    -webkit-box-sizing:border-box; /* Safari */
		padding-left: 6px;
		padding-right: 6px;
		display: block;
		overflow-x: auto;
		overflow-y: hidden;
		white-space: nowrap;
	}
	div.div-body-content{
		display: block;
		float: left;
		padding: 15px 10px 15px 10px;
	}
	/** end class container & body */

	/** class footer */
	div.div-footer{
		box-sizing:border-box;
    -moz-box-sizing:border-box; /* Firefox */
    -webkit-box-sizing:border-box; /* Safari */
		padding-left: 6px;
		padding-right: 6px;
		display: block;
		float: left;
		width: 100%;
	}
	div.div-footer .div-footer-content {
		display: block;
		border-radius: 3px!important;
		background-color: #f2f2f2;
		padding: 10px 30px 10px 18px;
		border:1px solid #DBDBDB;
	}
	/** end class footer */

	/** class data table */
	div.div-data{
		overflow-x: auto;
		overflow-y: auto;
		white-space: nowrap;
	}
	div.div-data tbody tr:nth-child(even){
		background-color: #fafafa;
	}
	div.div-data tbody tr:nth-child(odd){
		background-color: #ffffff;
	}
	div.div-page{
		display: block;
		float: left;
		width: 100%;
		padding: 4px  0px 10px 0px;
	}
	/** end class data table */

	/** class table data */
	table.table-data{
		width: 100%;
		border-collapse: collapse;
		border-color: #c0c0c0;
		background-color: #ffffff;
	}
	table.table-data th{
		padding: 10px 6px 10px 6px;
		font-weight: bold;
		text-align: left;
	}
	table.table-data th a:link {
		color: #404040;
	}
	table.table-data th a:visited {
		color: #404040;
	}
	table.table-data th a:hover {
		color: #000000;
	}
	table.table-data th a:active {
		color: #404040;
	}
	table.table-data td{
		padding: 4px 6px 4px 6px;
		text-align: left;
		border-bottom: 1px solid #c0c0c0;
	}
	table.table-data tbody tr:last-child td{
		border-bottom:3px double #9e9e9e;
	}
	table.table-data tbody tr:hover{
		cursor: pointer;
		background-color:#e2e2e2;
	}
	table.table-data tfoot tr td{
		border-bottom:0px double #9e9e9e;
	}
	table.table-data th a img.order-icon {
		height: 12px;
		margin-bottom: -2px;
	}
	/** end class table data */

	/** class horizontal line */
	.hr-single-double{
		border-top:1px double #9e9e9e;
		border-bottom:3px double #9e9e9e;
	}
	.hr-double{
		border-top:3px double #9e9e9e;
		border-bottom:3px double #9e9e9e;
	}
	.hr-double-top{
		border-top:3px double #9e9e9e;
	}
	.hr-double-bottom{
		border-bottom:3px double #9e9e9e;
	}
	.hr-single-bottom{
		border-bottom:1px double #9e9e9e;
	}
	.hr-double-left{
		border-left:3px double #9e9e9e;
	}
	.hr-double-right{
		border-right:3px double #9e9e9e;
	}
	/** end class horizontal line */

	/** class a href paging */
	a.pagefirst {
		vertical-align: middle;
		padding-left: 2px;
		padding-right: 2px;
	}
	a.pageprev {
		vertical-align: middle;
		padding-left: 2px;
		padding-right: 2px;
	}
	a.pagenext {
		vertical-align: middle;
		padding-left: 2px;
		padding-right: 2px;
	}
	a.pagelast {
		vertical-align: middle;
		padding-left: 2px;
		padding-right: 2px;
	}
	input.pageinput {
		width: 40px;
		vertical-align: middle;
		text-align: center;
	}
	/** end class a href paging */

	/** class div row & col*/
	div.div-row {
		float: left;
		display: block;
		width: 100%;
		padding-top:4px;
	}
	div.div-row .div-col {
		float: left;
		display: block;
	}
	div.div-row .div-col-right {
		float: right;
		display: block;
	}
	/** end class div row & col*/

	/** others */
  .nohover-color:hover {
		cursor: pointer!important;
    background-color:#FFFFFF!important;
	}
	.value-modified{
    background-color: #b4eeb4!important;
	}
	.required{
		background-color:#ffff99;
	}
	/** end others */

	/** recolored untuk disabled input */
	input[type="text"]:disabled {
    color: black;
    -webkit-text-fill-color: black
	}
	textarea:disabled {
    color: black;
    -webkit-text-fill-color: black
	}
</style>

