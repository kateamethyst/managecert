$(document).ready(function() {
    $('#file').change(function() {
            var oCopy = $(this).clone();
            var mTempForm = $('<form/>');
            var oUploadIframe = $('<iframe src="javascript:false;" name="eUploadIframe" style="display:none;"/>');
            mTempForm.attr('enctype', 'multipart/form-data');
            mTempForm.attr('id', 'tempForm');
            mTempForm.attr('class', $(this).attr('id'));
            mTempForm.appendTo('body');
            $(this).appendTo(mTempForm);
            //var oClonedFile = $(this).clone();
            oUploadIframe.appendTo('body');
            /* jshint ignore:start */
            [SDKJS]
            /* jshint ignore:end */
            Cafe24_SDK_Upload(mTempForm, {'callback' : function(aResponse) {
                //console.log('sample', aResponse);
            }});
            //$('#tempForm').submit();

        //oAction.uploadIcon(this, iIndex);
    });
    $('body').delegate('#tempForm', 'submit', function(event) {
        //self.showLoader('pageLoader');
        //dont use self.mBody
        //must be the version of jquery causing the file upload failing
        event.preventDefault();
        
        var mFileUpload = $(this).find('input[name="FILE_UPLOAD_INSTANCE"]');
        var oData = {
            'FILE_UPLOAD_INSTANCE' : mFileUpload[0].value || '',
        };

        console.log('oData', oData);

        var oAjax = {
            url : '[link=api/migration]',
            type : 'POST',
            data : oData,
            success : function(oResponse) {
                console.log('samople', oResponse);
            },
            error : function(oResponse) {
                console.log('samople', oResponse);
            }
        };

        $.ajax(oAjax);
    });
});
