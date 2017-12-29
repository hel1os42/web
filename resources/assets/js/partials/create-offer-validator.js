let tabValidator = {
    '#tab_step1': tab_step1_validator,
    '#tab_step2': tab_step2_validator,
    '#tab_step3': tab_step3_validator,
    '#tab_step4': tab_step4_validator
};

/* tab 1 */
clearInvalid($('[name="label"]'));
clearInvalid($('[name="description"]'));
clearInvalid($('#bonus_description'));

function tab_step1_validator(){
    validNotEmpty($('[name="label"]'));
    validNotEmpty($('[name="description"]'));
    if ($('#radio_ot3').is(':checked')) {
        validNotEmpty($('#bonus_description'));
    }
    $('.tab-step-control [href="#tab_step1"]').parents('li').toggleClass('invalid', !!$('#tab_step1').find('.invalid').length);
}

/* tab 2 */
clearInvalid($('[name="start_date"]'));
clearInvalid($('[name="finish_date"]'));
$('.day-info [type="checkbox"], .day-info .js-timepicker').each(function(){
    clearInvalid($(this));
});

function tab_step2_validator(){
    let $start = $('[name="start_date"]'),
        $finish = $('[name="finish_date"]');
    validNotEmpty($start);
    validNotEmpty($finish);
    validDateRange($start, $finish);
    $('.day-info [type="checkbox"]:checked').parents('p').find('.js-timepicker').each(function(){
        validNotEmpty($(this));
    });
    $('.tab-step-control [href="#tab_step2"]').parents('li').toggleClass('invalid', !!$('#tab_step2').find('.invalid').length);
    if ($('#tab_wdt2').find('.invalid').length) {
        $('[href="#tab_wdt2"]').trigger('click');
    }
}

/* tab 3 */

function tab_step3_validator(){

}

/* tab 4 */

function tab_step4_validator(){

}

/* system functions */
function validNotEmpty($input){
    if ($input.val() ===  '') $input.parents('p').addClass('invalid');
}

function validDateRange($start, $finish){
    let a = new Date($start.val()),
        b = new Date($finish.val());
    if (a.getTime() > b.getTime()) $start.parents('p').addClass('invalid');
}

function clearInvalid($input){
    $input.on('change', function(){
        $(this).parents('p').removeClass('invalid');
    });
}











	function allValidations(){

		validationOnFly();
		validationOnTabChange();
		formSubmit();

		function validationOnFly(){
			document.querySelectorAll('.valid-not-empty').forEach(function(box){
				box.querySelector('input, textarea, select').addEventListener('blur', function(){
					box.classList[this.value === '' ? 'add' : 'remove']('invalid');
				});
			});
			document.querySelectorAll('.valid-dates').forEach(function(box){
				box.getElementsByTagName('input')[1].addEventListener('blur', function(){
					if (!document.getElementById('datepicker')){
						let date1 = box.getElementsByTagName('input')[0].value;
						let date2 = this.value;
						date1 = date1 ? new Date(date1) : '';
						date2 = date2 ? new Date(date2) : '';
						box.classList[date1 && date2 && date2 >= date1  ? 'remove' : 'add']('invalid');
					}
				});
			});
		}
		function validationOnTabChange(){
			document.querySelector('.tabs-steps').addEventListener('tabchanged', function(){
				let li = this.querySelector('li.active').previousElementSibling;
				let index = 0;
				while (li) { li = li.previousElementSibling; index++; }
				if (index > 0) validTab1();
				if (index > 1) validTab2();
				if (index > 2) validTab3();
			});
		}
		function offerSubmitValidation(){
			return validTab1() && validTab2() && validTab3();
		}
		function validNotEmpty(parent){
			let err = 0;
			parent.querySelectorAll('.valid-not-empty').forEach(function(box){
				let val = box.querySelector('input, textarea, select').value;
				box.classList[val === '' ? 'add' : 'remove']('invalid');
				if (box.classList.contains('invalid')) err++;
			});
			return err;
		}
		function validDates(parent){
			let err = 0;
			parent.querySelectorAll('.valid-dates').forEach(function(box){
				if (!document.getElementById('datepicker')){
					let date1 = box.getElementsByTagName('input')[0].value;
					let date2 = box.getElementsByTagName('input')[1].value;
					date1 = date1 ? new Date(date1) : '';
					date2 = date2 ? new Date(date2) : '';
					box.classList[date1 && date2 && date2 >= date1  ? 'remove' : 'add']('invalid');
					if (box.classList.contains('invalid')) err++;
				}
			});
			return err;
		}
		function validTimes(parent){
			let err = 0;
			let tab = parent.querySelector('#tab_wdt2');
			tab.querySelectorAll('.day-info').forEach(function(box){
				let cb = box.querySelector('[type="checkbox"]');
				let inp = box.querySelectorAll('.js-timepicker');
				let invalid = cb.checked && (inp[0].value === '' || inp[1].value === '');
				box.classList[invalid ? 'add' : 'remove']('invalid');
				if (box.classList.contains('invalid')) err++;
			});
			return err;
		}
		function validTab1(){
			let li = document.querySelectorAll('.tabs-steps li')[0];
			li.classList.remove('valid');
			li.classList.remove('invalid');
			let err = validNotEmpty(document.querySelector('#tab_step1'));
			li.classList.add(err ? 'invalid' : 'valid');
			return li.classList.contains('valid');
		}
		function validTab2(){
			validTab1();
			let li = document.querySelectorAll('.tabs-steps li')[1];
			li.classList.remove('valid');
			li.classList.remove('invalid');
			let tab = document.getElementById('tab_step2');
			let err = validDates(tab);
			let wterr = validTimes(tab);
			err += wterr;
			li.classList.add(err ? 'invalid' : 'valid');
			if (wterr) {
				tab.querySelector('[data-target="tab_wdt2"]').dispatchEvent(new Event('click'));
			}
			return li.classList.contains('valid');
		}
		function validTab3(){
			validTab2();
			let li = document.querySelectorAll('.tabs-steps li')[2];
			li.classList.remove('valid');
			li.classList.remove('invalid');
			let err = validNotEmpty(document.getElementById('tab_step3'));
			li.classList.add(err ? 'invalid' : 'valid');
			return li.classList.contains('valid');
		}


		function showLoader(){
			let offerImg = document.getElementById('offerImg');
			document.body.appendChild(offerImg);
			offerImg.style.display = 'none';
			let overlay = document.getElementById('formOverlay');
			overlay.style.display = 'block';
			overlay.querySelector('.msg').innerText = 'Information sending...';
		}

		function makeFormData(form){
			let data = '';
			let input = form.querySelectorAll('[name]');
			let n = input.length;
			for (let i = 0; i < n; i++){
				if (input[i].getAttribute('type') === 'radio' && !input[i].checked) continue;
				data += input[i].getAttribute('name') + '=' + encodeURIComponent(input[i].value);
				if (i < n - 1) data += '&';
			}
			return data;
		}





		function formSubmit(){
			document.getElementById('createOfferForm').onsubmit = function(){
				let form = this;
				/* validation */
				if (!offerSubmitValidation()){
					alert('You have invalid values of your Offer! Check the form and try again.');
					document.querySelector('.tabs-steps li.invalid').dispatchEvent(new Event('click'));
					return false;
				}
				showLoader();

				/* fetch do not supports in IE9-11 */
				let xhr = new XMLHttpRequest();
				xhr.open('POST', form.getAttribute('action'), true);
				xhr.send(makeFormData(form));
				xhr.onreadystatechange = function(){
					if (xhr.readyState === 4){
						if (xhr.status === 200) {
							/* load image */
							uploadImageToServer(document.getElementById('offerImg'));
						} else {
							alert('Something wrong...');
							/* куда-то переходим? */
							window.location.pathname = '/';
							window.location.hash = '';
							window.location.search = '';
						}
					}
				};

				function uploadImageToServer(input){
					document.querySelector('#formOverlay .msg').innerText = 'Image sending...';
					/* this is not realised */

					let xhr = new XMLHttpRequest();
					xhr.setRequestHeader("Content-type", "multipart/form-data");
					xhr.open('POST', form.getAttribute('action'), true);
					xhr.send("file=" + document.getElementById('offerImg').files[0]);
					xhr.onreadystatechange = function(){
						if (xhr.readyState === 4){
							if (xhr.status === 200) {
								/* куда-то переходим? */
								window.location.pathname = '/';
								window.location.hash = '';
								window.location.search = '';
							} else {
								/* куда-то переходим? */
								alert('Something wrong...');
								window.location.pathname = '/';
								window.location.hash = '';
								window.location.search = '';
							}
						}
					};
				}

				return false;
			};
		}
	}
