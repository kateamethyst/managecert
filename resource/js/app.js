$(document).ready(function(){

    /**
     * Date
     * @var date
     */
    var dNow = new Date();

    /**
     * Year
     * @var String
     */
    var sYear = '' + dNow.getFullYear();
    /**
     * Update parameter
     * @type object
     */
    oUrl = {
        updateUrlParameter : function(sUrl, sParam, sValue) {
            var regex = new RegExp('(' + sParam + '=)[^\&]+');
            return sUrl.replace(regex, '$1' + sValue);
        },

        appendUrlParameter : function(sUrl, sParam, sValue) {
            if (sUrl.indexOf("?") === -1) {
                sUrl += "?";
            } else {
               sUrl += "&";
            }
            return sUrl + sParam + '=' + sValue;
        }
    }

    /**
     * Birthday and number validation
     * @type {Oobject
     */
    oNumber = {
        validateNumber : function (oEvent, sText, iLength) {
            if (oEvent.type === 'keydown' || oEvent.type === 'keypress') {
                return;
            }
            if (oEvent.keyCode == 8 || oEvent.keyCode == 46 || oEvent.keyCode == 37 || oEvent.keyCode == 39) {
                return true;
            } else {
                sText.val(sText.val().replace(/[^0-9]/g, ''));
                if (sText.val().length > iLength) {
                    sText.val($(this).val().substring(0, iLength));
                }
            }
            
        }
    }

    oDate = {
        validateDate : function (oStartDate, oEndDate) {
            var iError = 0;
            if (oStartDate.val() === '' || oStartDate.val() === null) {
                iError++;
                alert('시작일은 종료일 이전이여야 합니다.');
            } else if (oEndDate.val() === '' || oEndDate.val() === null) {
                iError++;
                alert('시작일은 종료일 이전이여야 합니다.');
            } else if (oStartDate.val() !== '전체') {
                if (new Date(oStartDate.val()) > new Date(oEndDate.val())) {
                    iError++;
                    alert('시작일은 종료일 이전이여야 합니다.');
                } else if (!isNaN(new Date(oStartDate.val()).getTime()) === false || !isNaN(new Date(oEndDate.val()).getTime()) === false) {
                    iError++;
                    alert('시작일은 종료일 이전이여야 합니다.');
                }
            }
            return iError;
        }
    }

    /**
     * Trapping Purposes
     */
    trapping = {};

    trapping.tags = {
        '&' : '&amp;',
        '<' : '&lt;',
        '>' : '&gt;',
        '"' : '&quot;',
        "'" : '&quot;'
    };

    trapping.escapeTags = function(sString) {
        sString = sString.replace(/[&<>]/gi, trapping.replace);
        return sString;
    }

    trapping.replace = function(sTag) {
        return trapping.tags[sTag] || sTag;
    }

    /**
     * Calendar Input
     * @var {String}
     */
    $('.img_cal1').Calendar({
        input_target : 'input[name=start_date]',
        years_between : [2010, sYear]
    });

    $('.img_cal2').Calendar({
        input_target : 'input[name=end_date]',
        years_between : [2010, sYear]
    });

    $('.allChk.tableChk').click(function(event) {
        event.stopImmediatePropagation();
        var oParentTable = $(this).closest('table')[0] || $('table');
        var aCheckboxes = $(oParentTable).find('.rowChk, .allChk');
        var mValue = this.checked;

        for (var iIndex = 0; iIndex < aCheckboxes.length; iIndex++) {
            if (aCheckboxes[iIndex].type === 'checkbox') {
                aCheckboxes[iIndex].checked = mValue;
            }
        }
    });

    $('#limit').change(function(){
        var sUrl = location.href;
        var slimitUrl = '';
        var sNewUrl = '';

        if (sUrl.indexOf("limit") >= 0) {
            slimitUrl = oUrl.updateUrlParameter(sUrl, 'limit', $(this).val());
        } else {
            slimitUrl = oUrl.appendUrlParameter(sUrl, 'limit', $(this).val());
        }

        if (slimitUrl.indexOf("page") >= 0) {
            sNewUrl = oUrl.updateUrlParameter(slimitUrl, 'page', 1);
        } else {
            sNewUrl = oUrl.appendUrlParameter(slimitUrl, 'page', 1);
        }

        location.href = sNewUrl;
    });

    $('.btnDate').live('click', function () {
        var d = new Date();
        var aMonths = new Array('01', '02', '03', '04', '05', '06', '07', '08', '09', '09', '11', '12');

        $('.btnDate').attr('class', 'btnDate eDateBtn btnSrhDate');
        $(this).attr('class', 'btnDate eDateBtn btnSrhDate selected');
        $('#end_date').val(d.getFullYear() + '-' + aMonths[d.getMonth()] + '-' + ("0" + d.getDate()).slice(-2));
        $('#start_date').val($(this).attr('data-from'));
        $('input[name=selected_date]').val($(this).attr('data-name'));
        console.log($('input[name=selected_date]').val($(this).attr('data-name')));
    });

    $('.allChk').click(function(event) {
        event.stopPropagation();

        var oParentTd = $(this).closest('td')[0] || $('td');
        var aCheckboxes = $(oParentTd).find('.rowChk, .allChk');
        var mValue = this.checked;

        for (var iIndex = 0; iIndex < aCheckboxes.length; iIndex++) {
            if (aCheckboxes[iIndex].type === 'checkbox') {
                $(aCheckboxes[iIndex]).attr('checked', mValue);
                if (mValue === true) {
                    $(aCheckboxes[iIndex]).parent('label.gLabel').addClass('eSelected');
                } else {
                    $(aCheckboxes[iIndex]).parent('label.gLabel').removeClass('eSelected');
                }
            }
        }
    });

    $('.rowChk').click(function() {
        var oParentTr = $(this).closest('tr')[0];
        var oAllChk = $(oParentTr).find('.allChk')[0];
        var iChild = $(oParentTr).find('.rowChk').length;
        var iChecked = $(oParentTr).find('.rowChk:checked').length;
        if (iChild === iChecked) {
            $(oAllChk).attr('checked', true);
        } else {
            $(oAllChk).attr('checked', false);
        }
    });

    $('body').delegate('#birthday', 'keypress keydown keyup change paste', function(event) {
        event.stopImmediatePropagation();
        oNumber.validateNumber(event, $(this), 8);
    });

    $('body').delegate('#number_item2', 'keypress keydown keyup change paste', function(event) {
        event.stopImmediatePropagation();
        oNumber.validateNumber(event, $(this), 4);
    });

    $('body').delegate('#number_item3', 'keypress keydown keyup change paste', function(event) {
        event.stopImmediatePropagation();
        oNumber.validateNumber(event, $(this), 4);
    });
});
