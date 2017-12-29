function imageUploader(selector){
	$(selector).on('change', function(){
        let err = false,
            size = this.files[0].size,
            type = this.files[0].type;
        if (size > 512000) err = iuError($(this), 'The file must be less than 512 kb');
        if (size < 1024) err = iuError($(this), 'The file must be greater than 1 kb');
        if (type !== 'image/png' && type !== 'image/jpeg') err = iuError($(this), 'You can use only jpeg or png');
        if (!err) {
            $(this).parent().find('.text-add').hide();
            let $img = $(this).parent().find('img');
            $img.attr('src', window.URL.createObjectURL(this.files[0])).show();
        }
	});

	function iuError($input, msg){
		$input.val('').parent().find('.text-add').show();
		$input.parent().find('img').hide();
		alert(msg);
		return true;
	}
	$(window).on('dragover', function() {
		$('.control-file').addClass('hover');
		return false;
	});
	$(window).on('drop', function() {
		$('.control-file').removeClass('hover');
	});
}
