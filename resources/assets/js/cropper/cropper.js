/*
* imageCropperInit(img) - создание кропалки
*
* imageCropperSetPosition(img) - установление позиции кропалки поверх картинки
*
* imageCropperRemove(img) - удаление кропалки
*
* imageCropperCrop(img, newImg) - кропанье в новое изображение
*
* */

window.addEventListener('resize', function(){
	document.querySelectorAll('.image-cropper').forEach(function(cropper){
		let img = cropper.previousElementSibling;
		if (img && img.tagName.toLowerCase() === 'img') {
			imageCropperSetPosition(img);
		}
	});
});

function imageCropperInit(img){
	let Cropper = document.createElement('span');
	Cropper.classList.add('image-cropper');
	let html = '<span class="crop-area">';
	if (img.dataset.circle) html += '<span class="circle"></span>';
	html += '<span class="move-area"></span>';
	let cropRatio = _getCropRatio(img);
	let directions = ['nw', 'ne', 'sw', 'se'];
	let directionsWNES = ['w', 'n', 'e', 's'];
	if (!cropRatio) directions = directions.concat(directionsWNES);
	directions.forEach(function(e){ html += '<span class="point point-' + e + '" data-point="' + e + '"></span>'; });
	html += '</span>';
	directionsWNES.forEach(function(e){ html += '<span class="shade shade-' + e + '"></span>'; });
	Cropper.innerHTML = html;
	img.parentElement.insertBefore(Cropper, img.nextElementSibling);
	imageCropperSetPosition(img);
	Cropper.querySelector('.move-area').addEventListener('mousedown', _cropperMoveStart);
	Cropper.querySelectorAll('.point').forEach(function(point){
		point.addEventListener('mousedown', _cropperCropStart);
	});
	return Cropper;
}

function imageCropperSetPosition(img){
	let Cropper = img.nextElementSibling;
	if (!Cropper || !Cropper.classList.contains('image-cropper')) return null;
	let cropRatio = _getCropRatio(img);
	Cropper.style.left = img.offsetLeft + 'px';
	Cropper.style.top = img.offsetTop + 'px';
	Cropper.style.width = img.clientWidth + 'px';
	Cropper.style.height = img.clientHeight + 'px';
	let left = 0, top = 0, width = 0, height = 0;
	let coords = img.dataset.crop;
	if (!coords)	{
		let imgWidth = img.clientWidth;
		let imgHeight = img.clientHeight;
		/*let cropWidth = Math.round(imgWidth * 0.8);
		let cropHeight = Math.round(imgHeight * 0.8);*/
		let cropWidth = Math.round(imgWidth);
		let cropHeight = Math.round(imgHeight);
		if (cropRatio){
			let w = Math.round(cropHeight * cropRatio);
			let h = Math.round(cropWidth / cropRatio);
			let vertical = cropWidth / cropHeight > cropRatio;
			/*left = Math.round(vertical ? (imgWidth - w) / 2 : imgWidth / 10);
			top = Math.round(vertical ? imgHeight / 10 : (imgHeight - h) / 2);*/
			left = Math.round(vertical ? (imgWidth - w) / 2 : 0);
			top = Math.round(vertical ? 0 : (imgHeight - h) / 2);
			width = (vertical ? w : cropWidth);
			height = (vertical ? cropHeight : h);
		} else {
			left = Math.round(imgWidth / 10);
			top = Math.round(imgHeight / 10);
			width = cropWidth;
			height = cropHeight;
		}
	}	else {
		coords = coords.split(' ').map(function(e){ return parseInt(e); });
		left = Math.round(coords[0] / img.naturalWidth * img.clientWidth);
		top = Math.round(coords[1] / img.naturalHeight * img.clientHeight);
		width = Math.round(coords[2] / img.naturalWidth * img.clientWidth);
		height = Math.round(coords[3] / img.naturalHeight * img.clientHeight);
	}
	let CropArea = Cropper.querySelector('.crop-area');
	CropArea.style.left = left + 'px';
	CropArea.style.top = top + 'px';
	CropArea.style.width = width + 'px';
	CropArea.style.height = height + 'px';
	_setShadePositions(Cropper);
	_setDatasetCrop(img, left, top, width, height);
	return Cropper;
}

function imageCropperRemove(img){
	let Cropper = img.nextElementSibling;
	if (!Cropper || !Cropper.classList.contains('image-cropper')) return false;
	Cropper.parentElement.removeChild(Cropper);
	delete img.dataset.crop;
	return true;
}

function imageCropperCrop(img, newImg){
	let coords = img.dataset.crop;
	if (!coords) {
		console.log('Coordinates not found');
		return false;
	}
	coords = coords.split(' ').map(function(e){ return parseInt(e); });
	let left = coords[0];
	let top = coords[1];
	let width = coords[2];
	let height = coords[3];
	let canvas1 = document.createElement('canvas');
	let canvas2 = document.createElement('canvas');
	canvas1.width = img.naturalWidth;
	canvas1.height = img.naturalHeight;
	canvas2.width = width;
	canvas2.height = height;
	let ctx1 = canvas1.getContext('2d');
	let ctx2 = canvas2.getContext('2d');
	ctx1.drawImage(img, 0, 0);
	ctx2.putImageData(ctx1.getImageData(left, top, width, height), 0, 0);
	if (!newImg) newImg = document.createElement('img');
	newImg.setAttribute('src', canvas2.toDataURL("image/jpeg", 0.8));
	return newImg;
}

function _setShadePositions(Cropper){
	let CropArea = Cropper.querySelector('.crop-area');
	let left = parseInt(CropArea.style.left);
	let top = parseInt(CropArea.style.top);
	let Shade = Cropper.querySelector('.shade-w');
	Shade.style.top = top + 'px';
	Shade.style.width = left + 'px';
	Shade.style.height = CropArea.clientHeight + 'px';
	Shade = Cropper.querySelector('.shade-n');
	Shade.style.width = Cropper.clientWidth + 'px';
	Shade.style.height = top + 'px';
	Shade = Cropper.querySelector('.shade-e');
	Shade.style.top = top + 'px';
	Shade.style.width = Cropper.clientWidth - left - CropArea.clientWidth + 'px';
	Shade.style.height = CropArea.clientHeight + 'px';
	Shade = Cropper.querySelector('.shade-s');
	Shade.style.width = Cropper.clientWidth + 'px';
	Shade.style.height = Cropper.clientHeight - top - CropArea.clientHeight + 'px';
}

function _cropperMoveStart(e){
	window.Cropper = this.parentElement.parentElement;
	window.addEventListener('mousemove', _cropperMove);
	window.addEventListener('mouseup', _cropperMoveEnd);
	window.CropperInf = { state: 'move' };
	window.CropperInf.startPoint = { x: e.pageX, y: e.pageY };
	let CropArea = window.Cropper.querySelector('.crop-area');
	window.CropperInf.cropAreaPosition = { x: parseInt(CropArea.style.left), y: parseInt(CropArea.style.top) };
}
function _cropperMove(e){
	if (!window.Cropper) return false;
	if (window.CropperInf.state !== 'move') return false;
	let CropArea = window.Cropper.querySelector('.crop-area');
	let left = e.pageX - window.CropperInf.startPoint.x + window.CropperInf.cropAreaPosition.x;
	let top = e.pageY - window.CropperInf.startPoint.y + window.CropperInf.cropAreaPosition.y;
	left = Math.max(Math.min(left, window.Cropper.clientWidth - CropArea.clientWidth), 0);
	top = Math.max(Math.min(top, window.Cropper.clientHeight - CropArea.clientHeight), 0);
	CropArea.style.left = left + 'px';
	CropArea.style.top = top + 'px';
	_setShadePositions(window.Cropper);
	_setDatasetCrop(window.Cropper.previousElementSibling, left, top, CropArea.clientWidth, CropArea.clientHeight);
}
function _cropperMoveEnd(){
	try {
		window.removeEventListener('mousemove', _cropperMove);
		window.removeEventListener('mouseup', _cropperMoveEnd);
	} catch (e) { /*console.dir(e);*/ }
	window.Cropper = null;
	window.CropperInf = null;
}

function _cropperCropStart(e){
	window.Cropper = this.parentElement.parentElement;
	window.addEventListener('mousemove', _cropperCrop);
	window.addEventListener('mouseup', _cropperCropEnd);
	window.CropperInf = { state: 'crop' };
	window.CropperInf.startPoint = { x: e.pageX, y: e.pageY };
	window.CropperInf.direction = this.dataset.point;
	window.CropperInf.cropRatio = _getCropRatio(window.Cropper.previousElementSibling);
	let CropArea = window.Cropper.querySelector('.crop-area');
	window.CropperInf.cropAreaPosition = {
		x: parseInt(CropArea.style.left),
		y: parseInt(CropArea.style.top),
		w: CropArea.clientWidth,
		h: CropArea.clientHeight
	};
}
function _cropperCrop(e){
	if (!window.Cropper) return false;
	if (window.CropperInf.state !== 'crop') return false;
	let cropRatio = window.CropperInf.cropRatio;
	let CropArea = window.Cropper.querySelector('.crop-area');
	let startPoint = window.CropperInf.startPoint;
	let imgWidth = window.Cropper.clientWidth;
	let imgHeight = window.Cropper.clientHeight;
	let left = window.CropperInf.cropAreaPosition.x;
	let top = window.CropperInf.cropAreaPosition.y;
	let right = window.CropperInf.cropAreaPosition.x + window.CropperInf.cropAreaPosition.w;
	let bottom = window.CropperInf.cropAreaPosition.y + window.CropperInf.cropAreaPosition.h;
	let deltaX = e.pageX - startPoint.x;
	let deltaY = e.pageY - startPoint.y;
	if (cropRatio) {
		if (window.CropperInf.direction === 'nw') {
			if (deltaX * cropRatio > deltaY) deltaX = Math.round(deltaY * cropRatio);
			else deltaY = Math.round(deltaX / cropRatio);
			let l = left + deltaX;
			let t = top + deltaY;
			let maxX = right - Math.round(24 * cropRatio);
			let maxY = bottom - Math.round(24 / cropRatio);
			if (l < 0) { l = 0; t = top - Math.round(left / cropRatio); }
			if (t < 0) { t = 0; l = left - Math.round(top * cropRatio); }
			if (l > maxX || t > maxY) { l = maxX; t = maxY; }
			left = l;
			top = t;
		}
		if (window.CropperInf.direction === 'ne') {
			if (deltaX * cropRatio > deltaY) deltaX = -Math.round(deltaY * cropRatio);
			else deltaY = -Math.round(deltaX / cropRatio);
			let r = right + deltaX;
			let t = top + deltaY;
			let minX = left + Math.round(24 * cropRatio);
			let maxY = bottom - Math.round(24 / cropRatio);
			if (r > imgWidth) { r = imgWidth; t = top - Math.round((imgWidth - right) / cropRatio); }
			if (t < 0) { t = 0; r = right - Math.round(top * cropRatio); }
			if (r < minX || t > maxY) { r = minX; t = maxY; }
			right = r;
			top = t;
		}
		if (window.CropperInf.direction === 'sw') {
			if (deltaX * cropRatio > deltaY) deltaX = -Math.round(deltaY * cropRatio);
			else deltaY = -Math.round(deltaX / cropRatio);
			let l = left + deltaX;
			let b = bottom + deltaY;
			let maxX = right - Math.round(24 * cropRatio);
			let minY = top + Math.round(24 / cropRatio);
			if (l < 0) { l = 0; b = bottom + Math.round(left / cropRatio); }
			if (b > imgHeight) { b = imgHeight; l = left - Math.round((imgHeight - bottom) * cropRatio); }
			if (l > maxX || b < minY) { l = maxX; b = minY; }
			left = l;
			bottom = b;
		}
		if (window.CropperInf.direction === 'se') {
			if (deltaX * cropRatio > deltaY) deltaX = Math.round(deltaY * cropRatio);
			else deltaY = Math.round(deltaX / cropRatio);
			let r = right + deltaX;
			let b = bottom + deltaY;
			let minX = left + Math.round(24 * cropRatio);
			let minY = top + Math.round(24 / cropRatio);
			if (r > imgWidth) { r = imgWidth; b = bottom + Math.round((imgWidth - right) / cropRatio); }
			if (b > imgHeight) { b = imgHeight; r = right + Math.round((imgHeight - bottom) * cropRatio); }
			if (r < minX || b < minY) { r = minX; b = minY; }
			right = r;
			bottom = b;
		}
	} else {
		if (window.CropperInf.direction === 'nw') { left += deltaX; top += deltaY; }
		if (window.CropperInf.direction === 'ne') { right += deltaX; top += deltaY; }
		if (window.CropperInf.direction === 'sw') { left += deltaX; bottom += deltaY; }
		if (window.CropperInf.direction === 'se') { right += deltaX; bottom += deltaY; }
	}
	left = Math.max(Math.min(left, window.CropperInf.cropAreaPosition.x + window.CropperInf.cropAreaPosition.w - 24), 0);
	top = Math.max(Math.min(top, window.CropperInf.cropAreaPosition.y + window.CropperInf.cropAreaPosition.h - 24), 0);
	right = Math.max(Math.min(right, imgWidth), left + 24);
	bottom = Math.max(Math.min(bottom, imgHeight), top + 24);
	let width = right - left;
	let height = bottom - top;
	CropArea.style.left = left + 'px';
	CropArea.style.top = top + 'px';
	CropArea.style.width = width + 'px';
	CropArea.style.height = height + 'px';
	_setShadePositions(window.Cropper);
	_setDatasetCrop(window.Cropper.previousElementSibling, left, top, width, height)
}
function _cropperCropEnd(){
	try {
		window.removeEventListener('mousemove', _cropperCrop);
		window.removeEventListener('mouseup', _cropperCropEnd);
	} catch (e) { /*console.dir(e);*/ }
	window.Cropper = null;
	window.CropperInf = null;
}

function _getCropRatio(elem){
	let res = parseFloat(elem.dataset.cropratio);
	if (isNaN(res)) res = null;
	return res;
}

function _setDatasetCrop(img, l, t, w, h){
	l = Math.round(l * img.naturalWidth / img.clientWidth);
	t = Math.round(t * img.naturalHeight / img.clientHeight);
	w = Math.round(w * img.naturalWidth / img.clientWidth);
	h = Math.round(h * img.naturalHeight / img.clientHeight);
	img.dataset.crop = l + ' ' + t + ' ' + w + ' ' + h;
}
