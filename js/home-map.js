$(function() {
    $('.map').maphilight();
    $('#councils_list li').mouseover(function(e) {
        $('#'+id+'area').mouseover();

    });
    $('#councils_list li').mouseout(function(e) {
        $('#'+id+'area').mouseout();
    });
});
