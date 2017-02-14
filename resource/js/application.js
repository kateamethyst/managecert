$(document).ready(function(){
    /**
     * aArgs
     * @type object
     */
    var aArgs = <?php echo htmlspecialchars(json_encode($aArgs),ENT_NOQUOTES, 'UTF-8'); ?>;

    /**
     * oApplication
     * @type object
     */
    var oApplication = {

        aInProgressReception : [],

        /**
         * Search application
         * @return boolean
         */
        searchApplication : function() {
            var iError = oDate.validateDate($('#start_date'), $('#end_date'));

            if (iError === 0) {
                if ($('#start_date').hasClass('readonly') === true) {
                    $('#start_date').val('전체');
                    var oCurrentDate = new Date();
                    var iMonth = ((oCurrentDate.getMonth() + 1) < 10 ? '0' + (oCurrentDate.getMonth() + 1) : (oCurrentDate.getMonth() + 1));
                    var iDate = ((oCurrentDate.getDate() + 1) < 10 ? '0' + oCurrentDate.getDate() : (oCurrentDate.getDate() + 1));
                    var sDate = oCurrentDate.getFullYear() + '-' + iMonth + '-' + iDate;
                    $('#end_date').val(sDate);
                }
                $('#search_application_form').submit();
            }
        },

        /**
         * Delete applciation
         * @return object
         */
        deleteApplication : function() {
            var aCheckedValues = $("input[name='application_id[]']:checked").map(function() {
                return $.trim(this.value);
            }).get();
            if (aCheckedValues.length === 0 ) {
                alert('항목을 선택해 주세요.');
            } else if (aCheckedValues.length === 1 && aCheckedValues[0] === 'on') {
                alert('항목을 선택해 주세요.');
            } else {
                var bAnswer = confirm('선택하신 항목을 삭제 하시겠습니까??');
                if (bAnswer === true) {
                   $('#formDelete').submit();
                }
            }
        },

        /**
         * Validate email
         * @param  string  sEmail  email address
         * @return boolean
         */
        validateEmail : function(sEmail) {
            var oRegex = new RegExp("[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?");
            return (sEmail.match(oRegex) === null) ? false : true;
        },

        /**
         * Validate user input
         * @return array
         */
        validateUserInput : function() {
            var sBirthday = $('#birthday').val().substring(0,4) + '-' + $('#birthday').val().substring(4,6) + '-' + $('#birthday').val().substring(6,8);
            var iError = 0;
            if ($("#reception ")[0].selectedIndex <= 0) {
                iError++;
                alert('접수처를 선택해주세요.');
            } else if ($("input[name='korean_name']").val() === '') {
                iError++;
                alert('이름(한글)을 입력해주세요.');
            } else if ($("input[name='english_name']").val() === '') {
                iError++;
                alert('이름(영문)을 입력해주세요.');
            } else if ($('#birthday').val() === '' ) {
                iError++;
                alert('생년월일을 정확히 입력해주세요.');
            } else if (parseInt($('#birthday').val().length) !== 8) {
                iError++;
                alert('생년월일을 정확히 입력해주세요.');
            } else if (!isNaN(new Date(sBirthday).getTime()) === false) {
                iError++;
                alert('생년월일을 정확히 입력해주세요.');
            } else if ($("input[name='number_item2']").val() === '' || $("input[name='number_item3']").val() === '') {
                iError++;
                alert('휴대전화를 정확히 입력해주세요.');
            } else if ($("input[name='number_item2']").val().length <= 2  || $("input[name='number_item2']").val().length > 4) {
                iError++;
                alert('휴대전화를 정확히 입력해주세요.');
            } else if ($("input[name='number_item3']").val().length !== 4 ) {
                iError++;
                alert('휴대전화를 정확히 입력해주세요.');
            } else if (oApplication.validateEmail( $("input[name='email']").val()) === false) {
                if ($("input[name='email']").val() !== '') {
                    iError++;
                    alert('이메일 형식이 올바르지 않습니다. 다시 확인해주세요.');
                }
            }

            return iError;
        },

        /**
         * Save application
         * @return object
         */
        saveApplication : function() {
            var iError = oApplication.validateUserInput();
            if (iError === 0) {

                var oData = {
                    'reception_id' : $('#reception option:selected').val(),
                    'korean_name' : $("input[name='korean_name']").val(),
                    'english_name' : $("input[name='english_name']").val(),
                    'cell_no' : $('#number_item1 option:selected').val() + '-' + $("input[name='number_item2']").val() + '-' + $("input[name='number_item3']").val()
                };
                $.ajax({
                    type : 'GET',
                    encode : 'json',
                    url : '[link=api/application]',
                    data : oData,
                    success : function(mResponse) {
                        if (mResponse.Data.result.length > 0) {
                            var bAnswer = confirm('해당 접수처에 동일한 이름과 휴대전화로 이미 등록되어 있습니다. 그래도 중복 등록하시겠습니까?');
                            if (bAnswer === true) {
                               $('#application_form').submit();
                            }
                        } else if (iError === 0) {
                            var bAnswer = confirm('등록 하시겠습니까?');
                            if (bAnswer === true) {
                                $('#application_form').submit();
                            }
                        }
                    }
                });
            }
        },

        /**
         * Update condition
         * @return object
         */
        updateCondition : function() {
            var oData = {
                'option' : 'updateCondition',
                'seq_no' :  $('#seq_no').val(),
                'status' : $('#conditions option:selected').val(),
                'department_code' : $('#department_code').val()
            };

            $.ajax({
                type : 'POST',
                encode : 'json',
                url : '[link=api/application]',
                data : oData,
                success : function(mResponse) {
                    if (mResponse.Data === true) {
                        alert('등록되었습니다.');
                        location.href = '[link=admin/application/details]?seq_no=' + oData.seq_no;
                    }
                }
            });
        },

        /**
         * Update Application
         * @return object
         */
        updateApplication : function() {
            var iError = oApplication.validateUserInput();

            if (iError === 0) {
               var oData = {
                    'ixnn_reception_seq_no' : $('#reception option:selected').val(),
                    'applicant_name_kr' : $("input[name='korean_name']").val(),
                    'applicant_name_en' : $("input[name='english_name']").val(),
                    'applicant_cell' :$('#number_item1 option:selected').val() + '-' + $("input[name='number_item2']").val() + '-' + $("input[name='number_item3']").val()
                };

                $.ajax({
                    type : 'GET',
                    encode : 'json',
                    url : '[link=api/application]',
                    data : oData,
                    success : function(mResponse) {
                        if (mResponse.Data.result.length > 0) {
                            var bAnswer = confirm('해당 접수처에 동일한 이름과 휴대전화로 이미 등록되어 있습니다. 그래도 중복 등록하시겠습니까?');
                            if (bAnswer === true) {
                               $('#application_update_form').submit();
                            }
                        } else if (iError === 0) {
                            var bAnswer = confirm('등록 하시겠습니까?');
                            if (bAnswer === true) {
                               $('#application_update_form').submit();
                            }
                        }
                    }
                });
            }
        },

        /**
         * Download excell
         * @return object
         */
        downloadExcel : function() {
            var aCheckedValues = $("input[name='status[]']:checked").map(function() {
                return $.trim(this.value);
            }).get();
            var sSort = '';
            var sUrl = location.href;
            if (sUrl.indexOf("sort") >= 0) {
                sSort = $('#sort').val();
            }
            oData = {
                'status' : aCheckedValues,
                'certificate_id' : $('#ixnn_certificate_seq_no option:selected').val(),
                'supplier_id' : $('#ixnn_supplier_seq_no option:selected').val(),
                'reception_id' : $('#ixnn_reception_seq_no option:selected').val(),
                'start_date' : $('#start_date').val(),
                'end_date' : $('#end_date').val(),
                'sort'  : sSort,
                'option' : 'getPaginated'
            };
            $.ajax({
                type : 'GET',
                encode : 'json',
                url : '[link=api/exceldownload]',
                data : oData,
                success : function(mResponse) {
                    if (mResponse.Data.error) {
                        alert(mResponse.Data.error);
                    } else {
                        var link = document.createElement("a");
                        link.setAttribute("href", mResponse.Data.href);
                        link.click();
                    }
                }
            });
        },

        /**
         * Sort application
         * @return object
         */
        sortApplication : function() {
            var sUrl = location.href;
            var sSort = 'D';

            if ($('#sort').val() === 'D') {
                sSort = 'A';
            }

            if (sUrl.indexOf("sort") >= 0) {
                var sNewUrl = oUrl.updateUrlParameter(sUrl, 'sort', sSort);
                location.href = sNewUrl;
            } else {
                var sNewUrl = oUrl.appendUrlParameter(sUrl, 'sort', sSort);
                location.href = sNewUrl;
            }
        },

        /**
         * Get reception
         * @return object
         */
        getReception : function() {
            oData = {
                'supplier_id' : $('#supplier_id option:selected').val(),
                'option' : 'all'
            };
            $.ajax({
                type : 'GET',
                encode : 'json',
                url : '[link=api/reception]',
                data : oData,
                success : function(mResponse) {
                    // console.log('Params:', oData);
                    // console.log('Response:', mResponse);
                    if (mResponse.Data.result.length === 0) {
                        $('#reception_id').html('<option disabled selected>수신을 먼저 등록하십시오.</option>')
                    } else {
                        var sHtml = '';
                        $.each(mResponse.Data.result, function(mKey, mValue) {
                            sHtml = sHtml + '<option value = "' + mValue.seq_no + '" >' + trapping.escapeTags(mValue.name) + '</option>';
                        });
                        sHtml = '<option value="0">전체</option>' + sHtml;
                        $('#reception_id').html(sHtml);

                        if (aArgs.hasOwnProperty('reception_id') === true) {
                            $('#reception_id').val(aArgs.reception_id);
                        }
                    }
                }
            });
        },

        /**
         * Get inprogress reception
         * @return object
         */
        getInProgressReception : function() {
            $.ajax({
                type : 'GET',
                encode : 'json',
                url : '[link=api/reception]',
                data : {'option' : 'inprogress'},
                success : function(mResponse) {
                    if (mResponse.Data.result.length > 0) {
                        oApplication.aInProgressReception = mResponse.Data.result;
                    }
                }
            });
        },

        /**
         * Get reception information
         * @param  string sReception Reception
         * @return object
         */
        getReceptionInfo : function (sReception) {
            $.each(oApplication.aInProgressReception, function(iKey, aReception) {
                if (parseInt(aReception.seq_no) === parseInt(sReception)) {
                    $('#belong').html(aReception.department_name);
                    $('#code').html(aReception.department_code);
                    $('#education_type').html(aReception.education_type);
                    $('#nth_test').html(aReception.nth_test);
                }
            });
        },

        /**
         * Get reception auto generated 
         * @return object
         */
        getReceptionAutoGenInfo : function() {
            var sReception = $('#reception option:selected').val();
            if (oApplication.aInProgressReception.length === 0) {
                oApplication.getInProgressReception();
                setTimeout(function() { oApplication.getReceptionInfo(sReception); }, 1000);
            } else {
                oApplication.getReceptionInfo(sReception);
            }
        },

        /**
         * Print Certificate
         * @return object
         */
        printCertificate : function() {
            var oData = {
                'id' : $('#certificate_no').val()
            }
            $.ajax({
                type : 'POST',
                encode : 'json',
                url : '[link=api/application]',
                data : oData,
                success : function(mResponse) {
                    var printContents = document.getElementById('popup').innerHTML;
                    document.body.innerHTML = printContents;
                    window.print();
                    window.close();
                }
            });
        },

        /**
         * Check status
         * @param  {[type]} aStatus [description]
         * @return object
         */
        checkStatus : function(aStatus) {
            $('.condiAllChk').removeAttr('checked');
            aStatus.forEach(function(mValue) {
                if (mValue.length > 0) {
                    $('input[name="status[]"][value="' + mValue + '"]').attr('checked', true);
                    $('input[name="status[]"][value="' + mValue + '"]').addClass('eSelected');
                }
            });

            if (aArgs.status.length === 7 || aArgs.status.indexOf('on') >= 0) {
                $('.condiAllChk').attr('checked', true);
                var aCondiCheckBox = $('input[name="status[]"].condiChk');
                for(var iIndex = 0; iIndex < aCondiCheckBox.length; iIndex++) {
                    $(aCondiCheckBox[iIndex]).addClass('eSelected');
                }
            }
        },

        /**
         * Check date
         * @return object
         */
        checkDate : function() {
            if ($(this).attr('id') === 'allDate') {
                $('#start_date').attr('readonly', true);
                $('#end_date').attr('readonly', true);
                $('.fText.gDate').addClass('readonly');
                $(this).attr('data-from', $('#start_date').val());
                //$(this).addClass('allDate')
            } else {
                $('#start_date').attr('readonly', false);
                $('#end_date').attr('readonly', false);
                $('.fText.gDate').removeClass('readonly');
            }
        },

        /**
         * Get reception by chosen certificate
         * @return object
         */
        getReceptionByCertId : function() {
            var oData = {
                'certificate_id'  : $('#certificate_id :selected').val(),
                'mall_version' : $('#mall_version').val(),
                'option' : 'inprogress'
            };

            $.ajax({
                type : 'GET',
                encode : 'json',
                url : '[link=api/reception]',
                data : oData,
                success : function(mResponse) {
                    if (mResponse.Data.hasOwnProperty('result') === true && mResponse.Data.result.length > 0) {
                        var sHtml = '';
                        $.each(mResponse.Data.result, function(mKey, mValue) {
                            sHtml = sHtml + '<option value = "' + mValue.seq_no + '" >' + trapping.escapeTags(mValue.name) + '</option>';
                        });
                        sHtml = '<option value="0">전체</option>' + sHtml;
                        $('#reception').html(sHtml);
                    } else {
                        $('#reception').html('<option disabled selected>수신을 먼저 등록하십시오.</option>')
                    }
                },
                error : function (mResponse) {
                    console.log('error', mResponse);
                }
            });
        },

        /**
         * Init
         * @return object
         */
        init : function() {
            if (aArgs.hasOwnProperty('status') === true) {
                aArgs.status = aArgs.status.filter(Boolean);
                oApplication.checkStatus(aArgs.status);
            }
            var oSupplierId = $('#supplier_id');
            $('#btnApplicationSearch').click(oApplication.searchApplication);
            $('.btnDeleteApplication').click(oApplication.deleteApplication);
            $('#btnSaveApplication').click(oApplication.saveApplication);
            $('#btnSaveCondition').click(oApplication.updateCondition);
            $('#btnUpdateApplication').click(oApplication.updateApplication);
            $('.btnDownloadExcell').click(oApplication.downloadExcel);
            $('#btnSortApplication').click(oApplication.sortApplication);
            $('#reception').change(oApplication.getReceptionAutoGenInfo);
            $('#btnPrintCertificate').click(oApplication.printCertificate);
            $('#certificate_id').change(oApplication.getReceptionByCertId);
            $('body').delegate('.btnDate.eDateBtn.btnSrhDate', 'click', oApplication.checkDate);
            oSupplierId.change(oApplication.getReception);
            oSupplierId.trigger('change');
        }

    };

    oApplication.init();
});
