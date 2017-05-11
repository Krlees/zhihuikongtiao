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
        if (Value == '0') {
            return false;
        }

        getSub('/Api/get-district', $(this).val(), 'city', false);
    });
    $(document).on('change', '#city', function () {
        var Value = $(this).val();
        if (Value == '0') {
            return false;
        }

        getSub('/Api/get-district', $(this).val(), 'area', false);
    });
});
