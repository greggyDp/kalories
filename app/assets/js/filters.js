$(function() {
    $(function() {
        $('input[name="createdAtFilter"]')
            .datetimepicker()
            .on('dp.hide',function(event){
            var pathname = window.location.pathname;
            var urlParams = new URLSearchParams(window.location.search);
            var $newDate = event.date.format('YYYY-MM-DD');

            if (urlParams.has('createdAt')) {
                if (urlParams.get('createdAt') === $newDate) {
                    return;
                }
                urlParams.set('createdAt', $newDate);
            } else {
                if (urlParams.has('page')) {
                    urlParams.append('createdAt', $newDate);
                } else {
                    urlParams.append('createdAt', $newDate);
                }
            }

            window.location.href = pathname + '?' + urlParams.toString();
        });
    });
});