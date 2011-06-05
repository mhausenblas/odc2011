$(function() {
    $('.map').maphilight();
    $('#councils_list li').mouseover(function(e) {
        var id=$(this).attr('id');
        $('#'+id+'area').mouseover();

    });
    $('#councils_list li').mouseout(function(e) {
        var id=$(this).attr('id');
        $('#'+id+'area').mouseout();
    });
});
