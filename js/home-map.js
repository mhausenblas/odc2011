$(function() {
    $('.map').maphilight();
    $('.cocotitle').mouseover(function(e) {
        var id=$(this).attr('id');
        $('#'+id+'area').mouseover();
        $('#'+id+'rss').show();
        $('#'+id+'twit').show();
    });
    $('.cocotitle').click(function(e) {
        var id=$(this).attr('id');
        $('#'+id+'lpa').toggle();
    });
    $('.cocotitle').mouseout(function(e) {
        var id=$(this).attr('id');
        $('#'+id+'area').mouseout();
        $('#'+id+'rss').hide();
        $('#'+id+'twit').hide();
    });
});
