$(function(){
    $('input[name="dateFrom"]').datepicker({dateFormat: "yy-mm-dd"});
    $('input[name="dateTo"]').datepicker({dateFormat: "yy-mm-dd"});
    $(document).on('click', '#loadMore', function(){
        $(this).hide();
        $('#preloaderImg').show();
        var loadMoreUrl = $(this).data('href');
        $.get(
            loadMoreUrl,
            { code: $(this).data('code'), userName: $(this).data('userName') },
            function(res){
                $('#preloaderImg').hide();
                $('#mainTable tbody tr.loadmoretr').replaceWith(res);
                $('.dateRange').trigger('change');
                $('html, body').animate({scrollTop:$(document).height()}, 'slow');
            }
        );
    });
    $(document).on('click', '#loadmoreFollowers', function(){
        $(this).hide();
        $('#preloaderImg').show();
        var loadMoreUrl = $(this).data('href');
        $.get(
            loadMoreUrl,
            { code: $(this).data('code'), userName: $(this).data('userName') },
            function(res){
                $('#preloaderImg').hide();
                $('#followersTable tbody tr.loadmoretr').replaceWith(res);
                $('.dateRange').trigger('change');
                $('html, body').animate({scrollTop:$(document).height()}, 'slow');
            }
        );
    });
    $('#summary_user_info_link').on('click', function(){
        if($('#summary_user_info_data').is(':hidden')) {
            $('#summary_user_info_data').show()
            $('#summary_user_info_link span').text('-');
        } else {
            $('#summary_user_info_data').hide()
            $('#summary_user_info_link span').text('+');
        }
    });

    $('.dateRange').on('change', function() {
        var from = $('input[name="dateFrom"]').datepicker('getDate');
        if(from) {
            from = from.getTime()/1000;
        }
        var to = $('input[name="dateTo"]').datepicker('getDate');
        if(to) {
            to = to.getTime()/1000;
        }
        $('#mainTable tr.trContainer').addClass('hideByDate');
        $('.createTime').each(function(){
            var int = parseInt($(this).text());
            if (((from && int >= from) || !from) && ((to && int <= to) || !to)) {
                $(this).parents('tr').removeClass('hideByDate');
            }
        });
        $('tr').not('.hideByDate').show();
        $('.hideByDate').hide();
    });

    $('.dateRange').trigger('change');
})
