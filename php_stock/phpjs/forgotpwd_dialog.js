var msForgotPwdDialog;

function msForgotPwdDialogShow() {
	var $ = jQuery, $dlg = msForgotPwdDialog || $("#msForgotPwdDialog")
	if (!$dlg)
		return;
	msForgotPwdDialog = $dlg.modal("show");
}

/* center modal */
function centerModals(){
  $('.modal').each(function(i){
    var $clone = $(this).clone().css('display', 'block').appendTo('body');
    var top = ($clone.height() - $clone.find('.modal-content').height()) / 2;
    top = top > 0 ? top : 0;
    $clone.remove();
    $(this).find('.modal-content').css("margin-top", top);
  });
}
$('.modal').on('show.bs.modal', centerModals);
$(window).on('resize', centerModals);
