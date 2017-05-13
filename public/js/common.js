function getSub(url, id, sub, init = true, is_hidden = true) {
    var initHtml = "";
    var chird = '#' + sub;
    var chirdChosen = '#' + sub + '_chosen';
    if (init) {
        initHtml = "<option value='" + id + "'>-请选择-</option>";
    }

    $(chird).chosen("destroy");
    $(chird).html(initHtml).chosen({width: "150px"});
    if (id > 0) {
        $.getJSON(url + "/" + id, {}, function (result) {


            var selectHtmls = "";
            if (result) {
                $.each(result, function (i, v) {
                    selectHtmls += "<option value='" + v.id + "'>" + v.name + "</option>";
                });
            }

            $(chird).chosen("destroy");
            $(chird).html(initHtml + selectHtmls).chosen({width: "150px"});

            // if (is_hidden) {
            //     $(chirdChosen).hide();
            // }
            // else {
            //     $(chirdChosen).show();
            // }

        });
    }

}