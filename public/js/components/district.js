$(function () {

    var cityEle = $("#city");
    var areaEle = $("#area");
    if (!cityEle.val()) {
        $('#city_chosen').hide();
    }
    if (!areaEle.val()) {
        $('#area_chosen').hide();
    }
    $(document).on('change', '#province', function () {
        var Value = $(this).val();


        getSub('/Api/get-district/2', $(this).val(), 'city', true);
    });
    $(document).on('change', '#city', function () {
        var Value = $(this).val();


        getSub('/Api/get-district/3', $(this).val(), 'area', true);
    });
});
