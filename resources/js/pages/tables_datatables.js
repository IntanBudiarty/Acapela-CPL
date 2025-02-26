/*
 *  Document   : tables_datatables.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Plugin Init Example Page
 */

// DataTables, for more examples you can check out https://www.datatables.net/
class pageTablesDatatables {
    /*
     * Init DataTables functionality
     *
     */
    static initDataTables() {
        // Override a few default classes
        jQuery.extend(jQuery.fn.dataTable.ext.classes, {
            sWrapper: "dataTables_wrapper dt-bootstrap5",
            sFilterInput: "form-control",
            sLengthSelect: "form-select"
        });

        // Override a few defaults
        jQuery.extend(true, jQuery.fn.dataTable.defaults, {
            language: {
                lengthMenu: "_MENU_",
                search: "_INPUT_",
                searchPlaceholder: "Cari..",
                emptyTable: "'Kosong' seperti perasaan dia",
                info: "Halaman <strong>_PAGE_</strong> dari <strong>_PAGES_</strong>",
                paginate: {
                    first: '<i class="fa fa-angle-double-left"></i>',
                    previous: '<i class="fa fa-angle-left"></i>',
                    next: '<i class="fa fa-angle-right"></i>',
                    last: '<i class="fa fa-angle-double-right"></i>'
                }
            }
        });

        // Override buttons default classes
        jQuery.extend(true, jQuery.fn.DataTable.Buttons.defaults, {
            dom: {
                button: {
                    className: 'btn btn-sm btn-primary'
                },
            }
        });

        // Init full DataTable
        jQuery('.js-dataTable-full').DataTable({
            pageLength: 20,
            lengthMenu: [[5, 10, 20, 40, 80], [5, 10, 20, 40, 80]],
            autoWidth: true,
            columnDefs: [
                {type: 'natural', targets: '_all'}
            ]
        });

        // Init DataTable with Buttons
        jQuery('.js-dataTable-buttons').DataTable({
            pageLength: 20,
            lengthMenu: [[5, 10, 20, 40, 80], [5, 10, 20, 40, 80]],
            autoWidth: true,
            columnDefs: [
                {type: 'natural', targets: '_all'}
            ],
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            dom: "<'row'<'col-sm-12'<'text-center py-2 mb-2'B>>>" +
                "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
        });

        // Init DataTable with Buttons
        jQuery('.js-dataTable-nilai').DataTable({
            pageLength: 20,
            lengthMenu: [[5, 10, 20, 40, 80], [5, 10, 20, 40, 80]],
            scrollX: true,
            scrollCollapse: true,
            columnDefs: [
                {type: 'natural', targets: '_all'}
            ],
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            dom: "<'row'<'col-sm-12'<'text-center py-2 mb-2'B>>>" +
                "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
        });
    }

    /*
     * Init functionality
     *
     */
    static init() {
        this.initDataTables();
    }
}

// Initialize when page loads
Dashmix.onLoad(pageTablesDatatables.init());
