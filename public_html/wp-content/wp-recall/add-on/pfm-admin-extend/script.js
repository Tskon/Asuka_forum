//Список ачивок для ручной выдачи
jQuery(document).on('click', '.pfm-post-need-approve a', function () {

    var id = jQuery(this).data('id');
    var nonce = jQuery(this).data('nonce');
    var clicked = jQuery(this);
    jQuery.post(Rcl.ajaxurl, {
        action: 'pfm_approve_post_frontend',
        nonce: nonce,
        id : id
    }, function (result) {
        if (result.content) {
            jQuery(clicked).parent().remove();
            rcl_notice('Сообщение одобрено', 'success', 3000);
        } else {
            rcl_notice(result.error, 'error', 3000);
        }

    });

    return false;

});