/*
* function datePicker(selector);
* function timePicker(selector);
* */

$(window).on('keyup', function(e){
	if (e.keyCode === 27) $('.picker').remove();
}).on('mousedown', function(e){
	if ($(e.target).parents('.picker').length === 0) $('.picker').remove();
});

function add0(n){
	return n > 9 ? n : '0' + n;
}

function setPickerPosition($input, pickerId){
	let $picker = $(pickerId),
		offset = { x: $input.offset().left, y: $input.offset().top },
		overSizedX = offset.x + $picker.outerWidth() > $(document.body).outerWidth(),
		overSizedY = offset.y + $picker.outerHeight() + $input.outerHeight() + 8 > $(document.body).outerHeight(),
		left =  offset.x + (overSizedX ? $input.outerWidth() - $picker.outerWidth() : 0) + 'px',
		top = offset.y + (overSizedY ? - $picker.outerHeight() - 8 : $input.outerHeight() + 8) + 'px';
    $picker.css({left, top});
}


/* datepicker */

function datePicker($input, minDate){
	let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $('.picker').remove();
    let date = new Date();
    if ($input.val()) { date = new Date($input.val()); }
    $(document.body).append(createPicker(date, $input, minDate));
    setPickerPosition($input, '#datepicker');
	function createPicker(date, $input, minDate){
		let picker = document.createElement('div');
		let html = '<p class="controls"><span class="prevYear" data-shift="-12" title="Previous Year">&lt;&lt;</span>';
		html += '<span class="prevMonth"  data-shift="-1" title="Previous Month">&lt;</span>';
		html += '<strong>' + months[date.getMonth()] + ', ' + date.getFullYear() + '</strong>';
		html += '<span class="nextYear"  data-shift="12" title="Next Year">&gt;&gt;</span>';
		html += '<span class="nextMonth"  data-shift="1" title="Next Month">&gt;</span>';
		html += '</p><table>' + getTable(date, minDate) + '</table>';
		$(picker).html(html).attr('id', 'datepicker').addClass('picker').on('click', function(e){
			let tdate;
			if ($(e.target).is('td')) {
			    let y = $(e.target).data('year'),
                    m = add0(parseInt($(e.target).data('month')) + 1),
                    d = add0($(e.target).data('date'));
				tdate = new Date(y + '/' + m + '/' + d);
				if ($(e.target).hasClass('not-this-month')) {
                    $(picker).find('table').html(getTable(tdate, minDate));
                    $(picker).find('.controls strong').text(months[m - 1] + ', ' + y);
                } else if ($(e.target).hasClass('not-active')) {
				    return false;
				} else {
					//$input.val(days[day(tdate.getDay())] + ' ' + y + '/' + m + '/' + d).trigger('change').focus();
					$input.val(y + '-' + m + '-' + d).trigger('change').focus();
					$('.picker').remove();
				}
			}
			if ($(e.target).is('span') && $(e.target).parent().hasClass('controls')) {
				let $td = $(picker).find('td').not('.not-this-month').eq(0);
				tdate = new Date($td.data('year') + '/' + add0(parseInt($td.data('month')) + 1) + '/' + '01');
				tdate.setMonth(tdate.getMonth() + parseInt($(e.target).data('shift')));
				$(picker).find('table').html(getTable(tdate, minDate));
                $(picker).find('.controls strong').text(months[tdate.getMonth()] + ', ' + tdate.getFullYear());
			}
		});
		return $(picker);
	}
	function getTable(date, minDate){
		let d = new Date(date.getTime());
		let now = new Date();
		d.setDate(1);
		d.setDate(1 - day(d.getDay()));
		let html = '<tr><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th class="weekend">S</th><th class="weekend">S</th></tr>';
		do {
			html += '<tr>';
			for (let i = 0; i < 7; i++) {
				let cls = [];
				if (d.getMonth() !== date.getMonth()) cls.push('not-this-month');
				if (day(d.getDay()) > 4) cls.push('weekend');
				if (equalDates(d, now)) cls.push('today');
				if (minDate && minDate > d && d.getMonth() === date.getMonth()) cls.push('not-active');
				/*if (equalDates(d, date_se)) cls.push('selected');*/
				cls = cls.length ? ' class="' + cls.join(' ') + '"' : '';
				let data = ' data-year="' + d.getFullYear() + '" data-month="' + d.getMonth() + '" data-date="' + d.getDate() + '"';
				html += '<td' + cls + data + '>' + d.getDate() + '</td>';
				d.setDate(d.getDate() + 1);
			}
			html += '</tr>';
		} while (d.getMonth() === date.getMonth());
		return html;
	}
	function day(n){
		return n - 1 === -1 ? 6 : n - 1;
	}
	function equalDates(a, b){
		return a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();
	}
}



/* timepicker */
function timePicker($input){
    $('.picker').remove();
    $(document.body).append(createPicker($input));
    setPickerPosition($input, '#timepicker');
	function createPicker($input){
		let picker = document.createElement('div');
		let html = '<table><tr><th colspan="6">Hour</th><th class="sep">&nbsp;</th><th>Min</th></tr>';
		for (let i = 0; i < 4; i++){
			html += '<tr>';
			for (let j = 0; j < 6; j++) html += '<td class="hour">' + (i * 6 + j) + '</td>';
			html += '<td class="sep">&nbsp;</td><td class="min">:' + add0(i * 15) + '</td></tr>'
		}
		html += '</table><p class="controls"><strong>__:__</strong> ';
		html += '<span class="midnight">23:59</span></p>';
		$(picker).html(html).attr('id', 'timepicker').addClass('picker').on('click', function(e){
			if ($(e.target).hasClass('hour')) changeClass($(e.target), '.hour', $(picker));
			if ($(e.target).hasClass('min')) changeClass($(e.target), '.min', $(picker));
			let h = picker.querySelector('.hour.active'),
			    m = picker.querySelector('.min.active'),
			    hval = h ? add0(parseInt(h.innerText)) : '__',
			    mval = m ? m.innerText : ':__';
			$(picker).find('.controls strong').text(hval + mval);
			let val;
			if (h && m) val = $(picker).find('.controls strong').text();
			if ($(e.target).hasClass('midnight')) val = $(e.target).text();
			if (val) {
				$input.val(val).trigger('change').focus();
                $('.picker').remove();
			}
		});
		return $(picker);
	}
	function changeClass($td, cls, $picker){
		$td.toggleClass('active');
		$picker.find(cls + '.active').each(function(){
			if ($(this).text() !== $td.text()) $(this).removeClass('active');
		});
	}
}
