jQuery(document).ready(function () {
    var date = function (date) {
        "use strict"; //let's avoid tom-foolery in this function
        // Convert to a number YYYYMMDD which we can use to order
        var dateParts = date.split(/[,\s]/);
        return (dateParts[3] * 10000) + ($.inArray(dateParts[2].toUpperCase(), ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"]) * 100) + (dateParts[0] * 1);
    };
    var size = function (data) {
        if (data.indexOf('N/A') > -1) {
            return 0;
        }
        var units = data.replace(/[\d\.\s]/g, '').toLowerCase();
        var multiplier = 1;
        if (units === 'kb') {
            multiplier = 1000;
        }
        else if (units === 'mb') {
            multiplier = 1000000;
        }
        else if (units === 'gb') {
            multiplier = 1000000000;
        }
        return parseFloat(data) * multiplier;
    };

    var compare = function (a, b, func, order) {
        if (typeof order == 'undefined') {
            order = 'asc';
        }
        if (order != 'asc') {
            var tmp = b;
            b = a;
            a = tmp;
        }

        a = $(a);
        var aDir = a.hasClass('folder') ? 0 : 1;
        b = $(b);
        var bDir = b.hasClass('folder') ? 0 : 1;
        if (aDir != bDir) {
            return aDir < bDir ? -1 : 1;
        }

        a = a.html();
        b = b.html();
        if (typeof func != 'undefined' && func != null) {
            a = func(a);
            b = func(b);
        }

            return a < b ? -1 : (a > b ? 1 : 0);
    };

    jQuery.fn.dataTable.ext.type.order['file-name-asc'] = function (a, b) {
        return compare($(a).html().replace(/<span class="scexpitemicon"><\/span>/g, ''), $(b).html().replace(/<span class="scexpitemicon"><\/span>/g, ''), null, 'asc');
    };
    jQuery.fn.dataTable.ext.type.order['file-name-desc'] = function (a, b) {
        return compare($(a).html().replace(/<span class="scexpitemicon"><\/span>/g, ''), $(b).html().replace(/<span class="scexpitemicon"><\/span>/g, ''), null, 'desc');
    };

    jQuery.fn.dataTableExt.oSort['date-modified-asc'] = function (a, b) {
        return compare(a, b, date, 'asc');
    };
    jQuery.fn.dataTableExt.oSort['date-modified-desc'] = function (a, b) {
        return compare(a, b, date, 'desc');
    };

    jQuery.fn.dataTable.ext.type.order['file-size-asc'] = function (a, b) {
        return compare(a, b, size, 'asc');
    };
    jQuery.fn.dataTable.ext.type.order['file-size-desc'] = function (a, b) {
        return compare(a, b, size, 'desc');
    };
});