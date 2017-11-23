/*
* function datePicker(selector);
* function timePicker(selector);
* */

window.addEventListener('keyup', function(e){
	if (e.keyCode === 27) removePickers();
});
window.addEventListener('mouseup', function(e){
	let parent = e.target;
	while (parent && !parent.classList.contains('picker') && parent.tagName.toLowerCase() !== 'body') parent = parent.parentElement;
	if (parent && parent.classList && !parent.classList.contains('picker')) removePickers();
});

function removePickers(){
	document.querySelectorAll('.picker').forEach(function(picker){
		picker.parentElement.removeChild(picker);
	});
}

function zero(n){
	return n > 9 ? n : '0' + n;
}

function setPickerPosition(input, pickerId){
	let picker = document.getElementById(pickerId);
	let offset = { x: input.offsetLeft, y: input.offsetTop };
	let parent = input;
	do {
		parent = parent.offsetParent;
		offset.x += parent.offsetLeft;
		offset.y += parent.offsetTop;
	} while (parent && parent.tagName.toLowerCase() !== 'body');
	let overSizedX = offset.x + picker.offsetWidth > document.body.offsetWidth;
	let overSizedY = offset.y + picker.offsetHeight > document.body.offsetHeight;
	picker.style.left = offset.x + (overSizedX ? input.offsetWidth - picker.offsetWidth : 0) + 'px';
	picker.style.top = offset.y + (overSizedY ? - picker.offsetHeight - 8 : input.offsetHeight + 8) + 'px';
}



/* datepicker */

function datePicker(selector){
	let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	let days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
	document.querySelectorAll(selector).forEach(function(input){
		input.addEventListener('focus', function(){ showPicker(this); });
		input.addEventListener('click', function(){ showPicker(this); });
	});
	function showPicker(input){
		removePickers();
		let date = new Date();
		if (input.value) { date = new Date(input.value); }
		document.body.appendChild(createPicker(date, input));
		setPickerPosition(input, 'datepicker');
	}
	function createPicker(date, input){
		let picker = document.createElement('div');
		let html = '<p class="controls"><span class="prevYear" data-shift="-12" title="Previous Year">&lt;&lt;</span>';
		html += '<span class="prevMonth"  data-shift="-1" title="Previous Month">&lt;</span>';
		html += '<strong>' + months[date.getMonth()] + ', ' + date.getFullYear() + '</strong>';
		html += '<span class="nextYear"  data-shift="12" title="Next Year">&gt;&gt;</span>';
		html += '<span class="nextMonth"  data-shift="1" title="Next Month">&gt;</span>';
		html += '</p><table>' + getTable(date) + '</table>';
		picker.innerHTML = html;
		picker.setAttribute('id', 'datepicker');
		picker.classList.add('picker');
		picker.addEventListener('click', function(e){
			let tdate;
			if (e.target.tagName.toLowerCase() === 'td') {
				tdate = new Date(e.target.dataset.year + '/' + (parseInt(e.target.dataset.month) + 1) + '/' + e.target.dataset.date);
				if (e.target.classList.contains('not-this-month')) {
					picker.querySelector('table').innerHTML = getTable(tdate);
					picker.querySelector('.controls strong').innerText = months[tdate.getMonth()] + ', ' + tdate.getFullYear()
				} else {
					input.value = days[day(tdate.getDay())] + ' ' + tdate.getFullYear() + '/' + zero(tdate.getMonth() + 1) + '/' + zero(tdate.getDate());
					input.dispatchEvent(new Event('change'));
					input.focus();
					removePickers();
				}
			}
			if (e.target.tagName.toLowerCase() === 'span' && e.target.parentElement.classList.contains('controls')) {
				let td = picker.querySelector('td:not(.not-this-month)');
				tdate = new Date(td.dataset.year + '/' + (parseInt(td.dataset.month) + 1) + '/' + '1');
				tdate.setMonth(tdate.getMonth() + parseInt(e.target.dataset.shift));
				picker.querySelector('table').innerHTML = getTable(tdate);
				picker.querySelector('.controls strong').innerText = months[tdate.getMonth()] + ', ' + tdate.getFullYear();
			}
		});
		return picker;
	}
	function getTable(date){
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
function timePicker(selector){
	document.querySelectorAll(selector).forEach(function(input){
		input.addEventListener('focus', function(){ showPicker(this); });
		input.addEventListener('click', function(){ showPicker(this); });
	});
	function showPicker(input){
		removePickers();
		document.body.appendChild(createPicker(input));
		setPickerPosition(input, 'timepicker');
	}
	function createPicker(input){
		let picker = document.createElement('div');
		let html = '<table><tr><th colspan="6">Hour</th><th class="sep">&nbsp;</th><th>Min</th></tr>';
		for (let i = 0; i < 4; i++){
			html += '<tr>';
			for (let j = 0; j < 6; j++) html += '<td class="hour">' + (i * 6 + j) + '</td>';
			html += '<td class="sep">&nbsp;</td><td class="min">:' + zero(i * 15) + '</td></tr>'
		}
		html += '</table><p class="controls"><strong>__:__</strong> ';
		html += '<span class="midnight">23:59</span></p>';
		picker.innerHTML = html;
		picker.setAttribute('id', 'timepicker');
		picker.classList.add('picker');
		picker.addEventListener('click', function(e){
			if (e.target.classList.contains('hour')) changeClass(e.target, '.hour', picker);
			if (e.target.classList.contains('min')) changeClass(e.target, '.min', picker);
			let h = picker.querySelector('.hour.active');
			let m = picker.querySelector('.min.active');
			let hval = h ? zero(parseInt(h.innerText)) : '__';
			let mval = m ? m.innerText : ':__';
			picker.querySelector('.controls strong').innerText = hval + mval;
			let val;
			if (h && m) val = picker.querySelector('.controls strong').innerText;
			if (e.target.classList.contains('midnight')) val = e.target.innerText;
			if (val) {
				input.value = val;
				input.dispatchEvent(new Event('change'));
				input.focus();
				removePickers();
			}
		});
		return picker;
	}
	function changeClass(td, cls, picker){
		td.classList.toggle('active');
		picker.querySelectorAll(cls + '.active').forEach(function(e){
			if (e.innerText !== td.innerText) e.classList.remove('active');
		});
	}
}
