function isIdCardNo(num) {

    var isDate6 = function (sDate) {
        if(!/^[0-9]{6}$/.test(sDate)) {
            return false;
        }
        var year, month, day;
        year = sDate.substring(0, 4);
        month = sDate.substring(4, 6);
        if (year < 1700 || year > 2500) return false
        if (month < 1 || month > 12) return false
        return true
    }
    var isDate8 = function (sDate) {
        if(!/^[0-9]{8}$/.test(sDate)) {
            return false;
        }
        var year, month, day;
        year = sDate.substring(0, 4);
        month = sDate.substring(4, 6);
        day = sDate.substring(6, 8);
        var iaMonthDays = [31,28,31,30,31,30,31,31,30,31,30,31]
        if (year < 1700 || year > 2500) return false
        if (((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0)) iaMonthDays[1]=29;
        if (month < 1 || month > 12) return false
        if (day < 1 || day > iaMonthDays[month - 1]) return false
        return true
    }

    var factorArr = new Array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2,1);
    var parityBit=new Array("1","0","X","9","8","7","6","5","4","3","2");
    var varArray = new Array();
    var intValue;
    var lngProduct = 0;
    var intCheckDigit;
    var intStrLen = num.length;
    var idNumber = num;
    // initialize
    if ((intStrLen != 15) && (intStrLen != 18)) {
        return false;
    }
    // check and set value
    for(i=0;i<intStrLen;i++) {
        varArray[i] = idNumber.charAt(i);
        if ((varArray[i] < "0" || varArray[i] > "9") && (i != 17)) {
            return false;
        } else if (i < 17) {
            varArray[i] = varArray[i] * factorArr[i];
        }
    }

    if (intStrLen == 18) {
        //check date
        var date8 = idNumber.substring(6,14);
        if (isDate8(date8) == false) {
            return false;
        }
        // calculate the sum of the products
        for(i=0;i<17;i++) {
            lngProduct = lngProduct + varArray[i];
        }
        // calculate the check digit
        intCheckDigit = parityBit[lngProduct % 11];
        // check last digit
        if (varArray[17] != intCheckDigit) {
            return false;
        }
    }
    else{ //length is 15
        //check date
        var date6 = idNumber.substring(6,12);
        if (isDate6(date6) == false) {

            return false;
        }
    }
    return true;

}

// 手机号码验证
jQuery.validator.addMethod("mobile", function(value, element) {
    var length = value.length;
    return this.optional(element) || (length == 11 && (/^1(3|4|5|7|8)\d{9}$/.test(value)));
}, "手机号码格式错误!");

// 特殊字符
jQuery.validator.addMethod("specialString", function(value, element) {
    return this.optional(element) || /^[u0391-uFFE5w]+$/.test(value);
}, "不允许包含特殊符号!");

// 中午
jQuery.validator.addMethod("chcharacter", function(value, element) {
    var reg = /[\u4E00-\u9FA5]/;
    return this.optional(element) || (reg.test(value));
}, "请输入中午");

// 身份证号码验证
jQuery.validator.addMethod("idcard", function(value, element) {
    return this.optional(element) || isIdCardNo(value);
}, "请正确输入身份证号码");

// 电话号码验证
jQuery.validator.addMethod("phone", function(value, element) {
    var tel = "/\d{4}-\d{7}$g/";
    return this.optional(element) || (tel.test(value));
}, "电话号码格式错误!");

// 邮政编码验证
jQuery.validator.addMethod("zipcode", function(value, element) {
    var tel = /^[0-9]{6}$/;
    return this.optional(element) || (tel.test(value));
}, "请正确填写邮政编码");