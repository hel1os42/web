function imageUploader(selector){

	document.querySelectorAll(selector).forEach(function(box){
		console.log('imageUploader init');
		box.classList.add('image-uploader-box');
		box.classList.add('img-hide');

		let maxSize = box.dataset.maxsize;
		let name = box.dataset.name;
		if (!name) name = '';

		let imgWrap = createElement('div', box, { class: 'image-wrap' });
		let img = createElement('img', imgWrap, { class: 'image' });
		if (box.dataset.cropratio) img.dataset.cropratio = box.dataset.cropratio;

		let attr = { type: 'file', name };
		if (maxSize) attr['data-maxsize'] = maxSize;
		let inputFile = createElement('input', box, attr);
		inputFile.addEventListener('change', changeInputFile);

		createElement('div', box, { class: 'dnd-hint' });
		window.addEventListener('dragover', function(){ if (!box.classList.contains('cropper')) box.classList.add('dnd-hover'); });
		window.addEventListener('drop', function(){ if (!box.classList.contains('cropper')) box.classList.remove('dnd-hover'); });

	});

	function createElement(tag, parent, attr = {}){
		let elem = document.createElement(tag);
		for (let key in attr) elem.setAttribute(key, attr[key]);
		if (parent) parent.appendChild(elem);
		return elem;
	}

	function changeInputFile(){
		if (!this.files.length) return false;
		let err = false,
			size = this.files[0].size,
			type = this.files[0].type;
		let maxSize = this.dataset.maxsize;
		if (maxSize) maxSize = parseInt(maxSize);
		if (maxSize && size > maxSize) err = iuError(this, 'The file must be less than ' + Math.round(maxSize / 1024) + ' kb');
		if (type !== 'image/png' && type !== 'image/jpeg') err = iuError(this, 'You can use only JPG/JPEG or PNG');
		if (!err) {
			let box = this.parentElement;
			let img = box.querySelector('.image');
			/*img.addEventListener('load', function(){
				if (box.dataset.cropratio) {
					imageCropperRemove(this);
					imageCropperInit(this);
				}
			});*/
			img.setAttribute('src', window.URL.createObjectURL(this.files[0]));
			box.classList.remove('img-hide');
			box.classList.add('changed');
		}
	}

	function iuError(inputFile, msg){
		inputFile.value = '';
		alert(msg);
		return true;
	}

}
