/**
 * Created by User on 7/31/2017.
 */
$('#report_table').DataTable({
    'paging': true,
    'columns': [
        {'width':'5%'},
        null,
        null,
        null
    ],
    'autoWidth': false,
    'lengthMenu': [[10, 20, 50, -1], [10, 20, 50, "All"]],
    'responsive': true,
    'scrollCollapse': true
});