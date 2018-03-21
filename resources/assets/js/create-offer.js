(function(){

	/* tabs */
	createTabs('.js-tabs');

	/* "Working Days & Time" checking days */
	checkingDays('.day-info');

	/* control range */
	controlRange('.js-numeric');

	/* date and time pickers */
	datePicker('.js-datepicker');
	timePicker('.js-timepicker');

	/* image uploader */
	imageUploader('.js-imgupload');

	/* max-redemption infinity */
	maxRedemptionInfinity('.max-redemption input');

	/* synchronization of wokr time */
	wokrTimeSynchronization();

	/* offer type = discount */
	offerTypeDiscount();

	/* validations and submit */
	allValidations();





	/* functions */

	function getParent(elem, className){
		let p = elem.parentElement;
		while (p && !p.classList.contains(className)) p = p.parentElement;
		return p;
	}

	function createTabs(selector){
		document.querySelectorAll(selector).forEach(function(ul){
			ul.firstElementChild.classList.add('active');
			for (let i = 0; i < ul.children.length; i++) {
				if (!ul.children[i].classList.contains('active')) document.getElementById(ul.children[i].dataset.target).style.display = 'none';
				ul.children[i].addEventListener('click', function(){
					let ul = this.parentElement;
					for (let j = 0; j < ul.children.length; j++) {
						ul.children[j].classList.remove('active');
						document.getElementById(ul.children[j].dataset.target).style.display = 'none';
					}
					this.classList.add('active');
					document.getElementById(this.dataset.target).style.display = 'block';
					ul.dispatchEvent(new Event('tabchanged'));
				});
			}
		});
		/* data-tab-target */
		document.querySelectorAll('[data-tab-target]').forEach(function(button){
			button.addEventListener('click', function(){
				document.querySelector('[data-target="' + this.dataset.tabTarget + '"]').dispatchEvent(new Event('click'));
				document.querySelector('.tabs-steps').scrollIntoView(true);
			});
		});
	}

	function checkingDays(selector){
		document.querySelectorAll(selector).forEach(function(p){
			let cb = p.querySelector('[type="checkbox"]');
			if (!cb.checked) p.classList.add('passive');
			cb.addEventListener('change', dayCheck);
			dayCheck.call(cb);
			function dayCheck(){
				let p = getParent(this, 'day-info');
				if (p) {
					p.classList[cb.checked ? 'remove' : 'add']('passive');
					let inputs = p.querySelectorAll('.js-timepicker');
					let passive = p.classList.contains('passive');
					inputs[0].value = passive ? '00:00' : '';
					inputs[1].value = passive ? '23:59' : '';
				}
			}
		});
	}

	function controlRange(selector){
		document.querySelectorAll(selector).forEach(function(input){
			input.addEventListener('keyup', function(){
				numericValidate(this);
			});
			input.addEventListener('change', function(){
				if (this.value === '') {
					let def = parseInt(input.dataset.default);
					if (isNaN(def)) def = 0;
					this.value = def;
				}
			});
			let p = getParent(input, 'control-range');
			if (p) {
				p.querySelector('.less').addEventListener('mousedown', numericClick);
				p.querySelector('.more').addEventListener('mousedown', numericClick);
				p.querySelector('.less').addEventListener('mouseleave', function(){ this.classList.remove('action'); });
				p.querySelector('.more').addEventListener('mouseleave', function(){ this.classList.remove('action'); });
				p.querySelector('.less').addEventListener('mouseup', function(){ this.classList.remove('action'); });
				p.querySelector('.more').addEventListener('mouseup', function(){ this.classList.remove('action'); });
			}
		});
		function numericValidate(input){
			if (input.value === '') return;
			let def = parseInt(input.dataset.default);
			let min = parseInt(input.dataset.min);
			let max = parseInt(input.dataset.max);
			let val = parseInt(input.value);
			if (isNaN(def)) def = 0;
			if (isNaN(min)) min = 0;
			if (isNaN(max)) max = 100;
			if (isNaN(val)) val = def;
			input.value = Math.min(Math.max(val, min), max);
			input.dispatchEvent(new Event('change'));
		}
		function numericClick(){
			this.classList.add('action');
			numericClickAction(this, 500);
		}
		function numericClickAction(button, t){
			if (!button.classList.contains('action')) return;
			let p = getParent(button, 'control-range');
			if (p) {
				let input = p.querySelector('input');
				input.value = parseInt(input.value) + (button.classList.contains('more') ? 1 : -1);
				input.dispatchEvent(new Event('change'));
				numericValidate(input);
			}
			t = Math.max(Math.round(t / 1.3), 20);
			setTimeout(numericClickAction, t, button, t);
		}
	}

	function maxRedemptionInfinity(selector){
		document.querySelectorAll(selector).forEach(function(input){
			checkInfinity(input);
			input.addEventListener('keydown', function(){ checkInfinity(input); });
			input.addEventListener('keyup', function(){ checkInfinity(input); });
			input.addEventListener('change', function(){ checkInfinity(input); });
		});
		function checkInfinity(input){
			input.parentElement.classList[input.value === '0' ? 'add' : 'remove']('infinity');
		}
	}

	function imageUploader(selector){
		document.querySelectorAll(selector).forEach(function(input){
			input.addEventListener('change', function(){
				let err = false;
				let size = this.files[0].size;
				let type = this.files[0].type;
				if (size > 512000) err = imageUploaderError(this, 'The file must be less than 512 kb');
				if (size < 1024) err = imageUploaderError(this, 'The file must be greater than 1 kb');
				if (type !== 'image/png' && type !== 'image/jpeg') err = imageUploaderError(this, 'You can use only jpeg or png');
				if (!err) {
					let img = this.parentElement.querySelector('img');
					img.src = window.URL.createObjectURL(this.files[0]);
					this.parentElement.querySelector('.text-add').style.display = 'none';
					this.parentElement.querySelector('img').style.display = 'block';
				}
			});
		});
		function imageUploaderError(input, msg){
			input.value = '';
			input.parentElement.querySelector('.text-add').style.display = 'block';
			input.parentElement.querySelector('img').style.display = 'none';
			alert(msg);
			return true;
		}
		window.addEventListener('dragover', function() {
			document.querySelectorAll('.control-file').forEach(function(box){
				box.classList.add('hover')
			});
			return false;
		});
		window.addEventListener('drop', function() {
			document.querySelectorAll('.control-file').forEach(function(box){
				box.classList.remove('hover')
			});
		});
	}

	function wokrTimeSynchronization(){
		document.getElementById('check_wd8').addEventListener('change', checkRelations);
		document.getElementById('check_wd9').addEventListener('change', checkRelations);
		document.getElementById('time_wd8f').addEventListener('change', timeRelations);
		document.getElementById('time_wd8t').addEventListener('change', timeRelations);
		document.querySelectorAll('[data-relation^="time_wd8f_"]').forEach(function(input){
			input.addEventListener('change', function(){ document.getElementById('time_wd8f').value = this.value; });
		});
		document.querySelectorAll('[data-relation^="time_wd8t_"]').forEach(function(input){
			input.addEventListener('change', function(){ document.getElementById('time_wd8t').value = this.value; });
		});
		function checkRelations(){
			let state = this.checked;
			document.querySelectorAll('[data-relation="' + this.getAttribute('id') + '"]').forEach(function(checkbox){
				checkbox.checked = state;
				checkbox.dispatchEvent(new Event('change'));
			});
		}
		function timeRelations(){
			let val = this.value;
			if (document.getElementById('check_wd8').checked){
				document.querySelectorAll('[data-relation="' + this.getAttribute('id') + '_wd"]').forEach(function(input){
					input.value = val;
					input.dispatchEvent(new Event('change'));
				});
			}
			if (document.getElementById('check_wd9').checked){
				document.querySelectorAll('[data-relation="' + this.getAttribute('id') + '_we"]').forEach(function(input){
					input.value = val;
					input.dispatchEvent(new Event('change'));
				});
			}
		}
	}

	function offerTypeDiscount(){
		document.querySelectorAll('[id^="radio_ot"]').forEach(function(radio){
			radio.addEventListener('change', function(){
				document.getElementById('discount_value').style.visibility = document.getElementById('radio_ot1').checked ? 'visible' : 'hidden';
			});
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
                        if (xhr.status === 401) UnAuthorized();
						else if (xhr.status === 200) {
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
                            if (xhr.status === 401) UnAuthorized();
							else if (xhr.status === 200) {
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

})();