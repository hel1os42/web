function controlRange(selector){
	$(selector).each(function(){
		$(this).on('keyup', numericValidate).on('change', function(){
			if ($(this).val() === '') {
				let def = parseInt($(this).attr('data-default'));
				if (isNaN(def)) def = 0;
				$(this).val(def);
			}
		});
		$(this).parents('.control-range').find('.less, .more')
            .on('mousedown', function(){ numericClickAction($(this).addClass('action'), 500); })
            .on('mouseleave mouseup', function(){ $(this).removeClass('action'); });
	});
	function numericValidate(){
		if ($(this).val() === '') return;
		let def = parseInt($(this).attr('data-default')),
		    min = parseInt($(this).attr('data-min')),
		    max = parseInt($(this).attr('data-max')),
		    val = parseInt($(this).val());
		if (isNaN(def)) def = 0;
		if (isNaN(min)) min = 0;
		if (isNaN(max)) max = 100;
		if (isNaN(val)) val = def;
		console.log(min, max, val);
		$(this).val(Math.min(Math.max(val, min), max)).trigger('change');
	}
	function numericClickAction($button, t){
		if (!$button.hasClass('action')) return;
		let $input = $button.parents('.control-range').find('input');
        $input.val(parseInt($input.val()) + ($button.hasClass('more') ? 1 : -1)).trigger('change');
        numericValidate.call($input);
		t = Math.max(Math.round(t / 1.3), 20);
		setTimeout(numericClickAction, t, $button, t);
	}
}

function maxRedemptionInfinity(selector){
	$(selector).each(function(){
		checkInfinity.call($(this));
		$(this).on('keydown keyup change', checkInfinity);
	});
	function checkInfinity(){
        $(this).parent().toggleClass('infinity', $(this).val() === '0');
	}
}
