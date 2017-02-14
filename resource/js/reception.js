$(document).ready(function(){
    /**
     * aArgs
     * @type object
     */
    var aArgs = <?php echo json_encode($aArgs) ?>;

    /**
     * oReception
     * @type object
     */
    var oReception = {
        aDepartment : [],

        /**
         * Search reception 
         * @return object
         */
        searchReception : function() {
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

                $('#search_form').submit();
            }
        },

        /**
         * Save reception 
         * @return object
         */
        saveReception : function() {
            oData = {
                'name' : $('#reception_name').val()
            };
            var iError = 0;

            $.ajax({
                type : 'GET',
                encode : 'json',
                url : '[link=api/reception]',
                data : oData,
                success : function(mResponse) {
                    if (mResponse.Data.result.length > 0) {
                        alert('동일한 접수처가 이미 등록되어 있습니다. 접수처 명칭을 다르게 지정해주세요.');
                        iError++;
                    } else if ($('#reception_name').val() === '') {
                        alert('접수처를 입력해주세요.');
                        iError++;
                    } else if ($("#dep_seq_no ")[0].selectedIndex <= 0) {
                        alert('소속을 선택해주세요.');
                        iError++;
                    }
                    if (iError === 0) {
                        var bAnswer = confirm('등록 하시겠습니까?');
                        if (bAnswer === true) {
                           $('#reception_form').submit();
                        }
                    }
                }
            });
        },

        /**
         * Get department
         * @return object
         */
        getDepartment : function() {
            $.ajax({
                type : 'GET',
                encode : 'json',
                url : '[link=api/department]',
                success : function(mResponse){
                    oReception.aDepartments = mResponse.Data.result;
                }
            });
        },

        /**
         * Update reception
         * @return object
         */
        updateReception : function() {
            var bAnswer = confirm('등록 하시겠습니까?');
            if (bAnswer === true) {
               $('#reception_form').submit();
            }
        },

        /**
         * Delete 
         * @return {[type]} [description]
         */
        deleteReception : function() {
            var aCheckedValues = $("input[name='reception[]']:checked").map(function() {
                return $.trim(this.value);
            }).get();
            if (aCheckedValues.length === 0 ) {
                alert('항목을 선택해 주세요.');
            } else if (aCheckedValues.length === 1 && aCheckedValues[0] === 'on') {
                alert('항목을 선택해 주세요.');
            } else {
                var bAnswer = confirm('선택하신 항목을 삭제 하시겠습니까?');
                if (bAnswer === true) {
                   $('#formDelete').submit();
                }
            }
        },

        /**
         * Display department code
         * @return object
         */
        displayDepartmentCode : function() {
            var department_code = $('#department_code');
            var seq_no = $('#dep_seq_no').val();
            $.each(oReception.aDepartments, function(iKey, aDepartment) {
                if (iKey === seq_no) {
                    department_code.html(aDepartment[0]['department_code']);
                }
            });
        },

        /**
         * Check status
         * @param  array   aStatus    
         * @param  string  sSelector  
         * @param  string  sInputName 
         * @param  integer iOptions   
         * @return object
         */
        checkStatus : function(aStatus, sSelector, sInputName, iOptions) {
            $(sSelector).removeAttr('checked');
            aStatus.forEach(function(mValue) {
                if (mValue.length > 0) {
                    $('input[name="' + sInputName + '[]"][value="' + mValue + '"]').attr('checked', true);
                }
            });

            if (aArgs[sInputName].length === iOptions) {
                $(sSelector).attr('checked', true);
            }
        },

        /**
         * Check date
         * @return object
         */
        checkDate : function() {
            console.log('sdsd');
            if ($(this).attr('id') === 'allDate') {
                $('#start_date').attr('readonly', true);
                $('#end_date').attr('readonly', true);
                $('.fText.gDate').addClass('readonly');
                $(this).attr('data-from', $('#start_date').val());
            } else {
                $('#start_date').attr('readonly', false);
                $('#end_date').attr('readonly', false);
                $('.fText.gDate').removeClass('readonly');
            }
        },

        /**
         * Init
         * @return object
         */
        init : function() {
            if (aArgs.hasOwnProperty('status') === true) {
                aArgs.status = aArgs.status.filter(Boolean);
                oReception.checkStatus(aArgs.status, '.condiAllChk', 'status', 3);
            }

            if (aArgs.hasOwnProperty('department_id') === true) {
                aArgs.department_id = aArgs.department_id.filter(Boolean);
                oReception.checkStatus(aArgs.department_id, '.depAllChk', 'department_id', 4);
            }

            $('body').delegate('.btnDate.eDateBtn.btnSrhDate', 'click', oReception.checkDate);
            $('#btnReceptionSearch').click(oReception.searchReception);
            $('#btnSaveReception').click(oReception.saveReception);
            $('#reception_form').show(oReception.getDepartment);
            $('#btnUpdateReception').click(oReception.updateReception);
            $('.btnDeleteReception').click(oReception.deleteReception);
            $('#dep_seq_no').change(oReception.displayDepartmentCode);
        }
    };
    oReception.init();
});
