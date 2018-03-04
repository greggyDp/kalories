$(function() {
    $('input[name="createdAtFilterFrom"]')
        .datetimepicker()
        .on('dp.hide',function(event){
            var pathname = window.location.pathname;
            var urlParams = new URLSearchParams(window.location.search);
            var $newDate = event.date.format('YYYY-MM-DD');
            
            if (urlParams.get('createdAtFrom') === $newDate) {
                return;
            }

            if (urlParams.has('createdAtFrom')) {
                urlParams.set('createdAtFrom', $newDate);
            } else {
                urlParams.append('createdAtFrom', $newDate);
            }

            window.location.href = pathname + '?' + urlParams.toString();
        });

    $('input[name="createdAtFilterTo"]')
        .datetimepicker()
        .on('dp.hide',function(event){
        var pathname = window.location.pathname;
        var urlParams = new URLSearchParams(window.location.search);
        var $newDate = event.date.format('YYYY-MM-DD');

        if (urlParams.has('createdAtFrom') === false) {
            return;
        }

        if (urlParams.get('createdAtTo') === $newDate) {
            return;
        }

        if (urlParams.has('createdAtTo')) {
            urlParams.set('createdAtTo', $newDate);
        } else {
            urlParams.append('createdAtTo', $newDate);
        }

        window.location.href = pathname + '?' + urlParams.toString();
    });
});
