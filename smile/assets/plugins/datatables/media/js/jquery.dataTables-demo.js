/*** -----------------------------------------------------------------------------------------------

	ADMIN TEMPLATE | Boo Admin Template
	----------------------------------------

	JS - Demo DataTable
	
	-------------------------------------------------------------------------------------------------------------------------------- ***/
$(document)
        .ready(function () {
        demo_datatable.tableDT();
        demo_datatable.tableDTA();
        demo_datatable.tableDTB_1();
        demo_datatable.tableDTB_2();
        demo_datatable.tableDTC();
        demo_datatable.tableDTCF();
        demo_datatable.tableDTSC();
        
});

// DATATABLE SETTINGS
// ------------------------------------------------------------------------------------------------ * -->
demo_datatable = {
        tableDT: function () {
                var oTable = $('#exampleDT')
                        .dataTable({
                        iDisplayLength: 3,
                        sDom: "<'row-fluid' <'span4'l> <'span8'pf> > rt <'row-fluid' <'span12'i> >"
                });

        },
        tableDTA: function () {
                var oTable = $('#exampleDTA')
                        .dataTable({
                        oLanguage: {
                                sSearch: 'Global search:',
                                sLengthMenu: '_MENU_ to page',
                                sZeroRecords: 'No record found <button class="btn btn-danger resetTable">Reset filter</button>',
                                oPaginate: {
                                        sNext: '<i class="arrowicon-r-black"></i>',
                                        sPrevious: '<i class="arrowicon-l-black"></i>'
                                }
                        },
                        iDisplayLength: 3,
                        aaSorting: [
                                [0, 'desc']
                        ],
                        aoColumnDefs: [{
                                "aTargets": [2],
                                'sClass': 'hidden-phone'
                        }, {
                                "aTargets": [3],
                                'sClass': 'hidden-phone hidden-tablet'
                        }, {
                                "aTargets": [4],
                                'sType': 'eu_date'
                        }],
                        sDom: "<'row-fluid'<'widget-header'<'span6'l><'span6'f>>>rt<'row-fluid'<'widget-footer'<'span6'><'span6'p>>"

                });

        },
        tableDTB_1: function () {
                var oTable = $('#exampleDTB-1')
                        .dataTable({
                        oLanguage: {
                                sSearch: "",
                                sLengthMenu: "_MENU_ entries to page",
                                sZeroRecords: 'No record found <button class="btn btn-danger resetTable">Reset filter</button>'
                        },
                        iDisplayLength: 3,
                        aaSorting: [
                                [1, 'asc']
                        ],
                        aoColumnDefs: [{
                                "aTargets": [2],
                                'sClass': 'hidden-phone'
                        }, {
                                "aTargets": [3],
                                'sClass': 'hidden-phone hidden-tablet'
                        }, {
                                "aTargets": [4],
                                'sType': 'eu_date'
                        }],
                        sPaginationType: 'full_numbers',
                        sDom: "<'row-fluid' <'widget-header' <'span4'l> <'span8'<'table-reset-wrapper'>f<'table-tool-wrapper'> > > >  rt <'row-fluid' <'widget-footer' <'span12'p> >",
                });
                //* inject  to datatable DTB
                $('#exampleDTB-1_wrapper .table-global-filter input')
                        .attr("placeholder", "enter search terms");
                $('#exampleDTB-1_wrapper .table-tool-wrapper')
                        .html($('.DTB_toolBar')
                        .html());
                $('#exampleDTB-1_wrapper .table-reset-wrapper')
                        .html($('.DTB_resetTable')
                        .html());

        },
        tableDTB_2: function () {
                var oTable = $('#exampleDTB-2')
                        .dataTable({
                        oLanguage: {
                                sSearch: "",
                                sLengthMenu: "_MENU_ entries to page",
                                sZeroRecords: 'No record found <button class="btn btn-danger resetTable">Reset filter</button>'
                        },
                        iDisplayLength: 3,
                        aaSorting: [
                                [1, 'asc']
                        ],
                        aoColumnDefs: [{
                                "aTargets": [2],
                                'sClass': 'hidden-phone'
                        }, {
                                "aTargets": [3],
                                'sClass': 'hidden-phone hidden-tablet'
                        }, {
                                "aTargets": [4],
                                'sType': 'eu_date'
                        }],
                        sPaginationType: 'full_numbers',
                        sDom: "<'row-fluid' <'widget-header no-border-bottom' <'span4'l> <'span8'<'table-reset-wrapper'>f<'table-tool-wrapper'> > > >  rti <'row-fluid' <'widget-footer' <'span12'p> >",
                });
                //* inject to datatable DTB
                $('#exampleDTB-2_wrapper .table-global-filter input')
                        .attr("placeholder", "enter search terms");
                $('#exampleDTB-2_wrapper .table-tool-wrapper')
                        .html($('.DTB_toolBar')
                        .html());
                $('#exampleDTB-2_wrapper .table-reset-wrapper')
                        .html($('.DTB_resetTable')
                        .html());

        },
        tableDTC: function () {
                var oTable = $('#exampleDTC')
                        .dataTable({
                        oLanguage: {
                                sSearch: "Global search: ",
                                sLengthMenu: "Show _MENU_ entries",
                                sZeroRecords: 'No record found <button class="btn btn-danger resetTable">Reset filter</button>'
                        },
                        iDisplayLength: 5,
                        aaSorting: [
                                [1, 'asc']
                        ],
                        aoColumnDefs: [{
                                "aTargets": [0],
                                'sClass': 'bold'
                        }, {
                                "aTargets": [1],
                                'sClass': 'text-center'
                        }, {
                                "aTargets": [2],
                                'sClass': 'hidden-phone'
                        }, {
                                "aTargets": [3],
                                'sClass': 'text-right hidden-phone hidden-tablet',
                                'sType': 'eu_date'
                        }, {
                                "aTargets": [4],
                                'sClass': 'text-right hidden-phone hidden-tablet',
                                'sType': 'eu_date'
                        }, {
                                "aTargets": [5],
                                'sClass': 'text-right',
                                'sType': 'eu_date'
                        }, {
                                "aTargets": [6],
                                'sClass': 'text-right'
                        }],
                        sPaginationType: 'full_numbers',
                        sDom: "<'row-fluid' <'widget-header' <'span4'l> <'span8'<'table-reset-wrapper'>f> > <'table-tool'> >  Rrt <'row-fluid' <'widget-footer' <'span4' <'table-action-wrapper'> i> <'span8'p> >",

                        fnFooterCallback: function (nRow, aaData, iStart, iEnd, aiDisplay) {
                                var iPageSum = 0;
                                for(var i = iStart; i < iEnd; i++) {
                                        iPageSum += aaData[aiDisplay[i]][6] * 1;
                                }
                                var nCells = nRow.getElementsByTagName('th');
                                nCells[6].innerHTML = parseInt(iPageSum * 100) / 100;
                        }
                });
                //* inject to datatable DTC
                $('#exampleDTC_wrapper .table-tool')
                        .html($('#DTC_toolBar')
                        .html());
                $('#exampleDTC_wrapper .table-reset-wrapper')
                        .html($('.DTC_resetTable')
                        .html());
                $('#exampleDTC_wrapper .table-action-wrapper')
                        .html($('#DTC_actionTable')
                        .html());
        },
        tableDTCF: function () {
                var oTable = $('#exampleDTCF')
                        .dataTable({
                        bAutoWidth: false,
                        bSortCellsTop: true,
                        BProcessing: true,
                        oLanguage: {
                                sSearch: "Global search: ",
                                sLengthMenu: "Show _MENU_ entries",
                                sZeroRecords: 'No record found <button class="btn btn-danger resetTable">Reset filter</button>'
                        },
                        iDisplayLength: 5,
                        aaSorting: [
                                [3, 'asc']
                        ],
                        aoColumnDefs: [{
                                "aTargets": [0],
                                'bSortable': false,
                                'sWidth': '25px'
                        }, {
                                "aTargets": [1],
                                'sWidth': '65px',
                                'sClass': 'bold'
                        }, {
                                "aTargets": [3],
                                'sClass': 'text-right hidden-phone hidden-tablet',
                                'sType': 'eu_date'
                        }, {
                                "aTargets": [4],
                                'sClass': 'text-right',
                                'sType': 'eu_date'
                        }, {
                                "aTargets": [5],
                                'sClass': 'text-right'
                        }],
                        sPaginationType: 'full_numbers',
                        sDom: "<'row-fluid' <'widget-header' <'span4'l> <'span8'<'table-tool-wrapper'><'table-tool-container'>> > > rti <'row-fluid' <'widget-footer' <'span6' <'table-action-wrapper'>> <'span6'p> >>",

                        fnFooterCallback: function (nRow, aaData, iStart, iEnd, aiDisplay) {
                                var iPageSum = 0;
                                for(var i = iStart; i < iEnd; i++) {
                                        iPageSum += aaData[aiDisplay[i]][5] * 1;
                                }
                                var nCells = nRow.getElementsByTagName('th');
                                nCells[5].innerHTML = parseInt(iPageSum * 100) / 100;
                        }
                })
                // Table Filter
                .columnFilter({
                        sPlaceHolder: 'head:after',
                        aoColumns: [
                        null, {
                                type: 'number'
                        }, {
                                type: 'text'
                        }, {
                                type: 'text'
                        }, {
                                type: 'text'
                        }, {
                                type: 'text'
                        }]
                });
                // inject to datatable DTCF
                $('#exampleDTCF_wrapper .table-tool-wrapper')
                        .html($('#DTCF_toolBar')
                        .html());
                $('#exampleDTCF_wrapper .table-action-wrapper')
                        .html($('#DTCF_actionTable')
                        .html());
        },
        tableDTSC: function () {
                var oTable = $('#exampleDTSC')
                        .dataTable({
                        sScrollY: "174px",
                        sAjaxSource: "assets/data/2500.txt",
                        bDeferRender: true,
                        bStateSave: true,
                        OScroller: {
                                LoadingIndicator: true
                        },

                        oLanguage: {
                                sSearch: 'Global search:',
                                sLengthMenu: 'Show _MENU_ entries',
                                sZeroRecords: 'No record found <button class="btn btn-danger resetTable">Reset filter</button>',
                        },
                        aaSorting: [
                                [0, 'desc']
                        ],
                        aoColumnDefs: [{
                                "aTargets": [2],
                                'sClass': 'hidden-phone'
                        }, {
                                "aTargets": [3],
                                'sClass': 'hidden-phone hidden-tablet'
                        }],
                        sDom: "<'row-fluid'<'widget-header' <'span6'<'table-header-title'>> <'span6'f>>>rtiS"
                })
                        .columnFilter({
                        sPlaceHolder: 'head:after',
                        aoColumns: [{
                                type: 'number'
                        }, {
                                type: 'text'
                        }, {
                                type: 'text'
                        }, {
                                type: 'text'
                        }, {
                                type: 'text'
                        }]
                });
                // inject to datatable DTSC
                $('#exampleDTSC_wrapper .table-header-title')
                        .html($('#DTSC_header_title')
                        .html());

        }

};