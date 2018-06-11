<div class="modal fade" id="print_qr_code" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{ __('print.titles.print_qr_code') }}</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="user_invite_code" id="qr_invite_code" value="">
                <input type="hidden" name="place_name" id="qr_place_name" value="">
                <input type="hidden" name="place_address" id="qr_place_address" value="">

                <div class="fields col-md-12">
                    <div class="row m-b-10">
                        <div class="col-md-3">
                            <label>{{ __('print.fields.image_count') }}</label>
                        </div>
                        <div class="col-md-9">
                            <input class="qr_input" name="qr_image_count" id="qr_image_count" value="20" placeholder="20">
                        </div>
                    </div>
                    <div class="row m-b-10">
                        <div class="col-md-3">
                            <label>{{ __('print.fields.image_size') }}</label>
                        </div>
                        <div class="col-md-9">
                            <input class="qr_input" name="qr_image_size" id="qr_image_size" value="30" placeholder="30">
                            <input class="qr_range" type="range" min="10" max="100" name="qr_image_size_range" id="qr_image_size_range" value="30" step="5">
                        </div>
                    </div>
                    <div class="row m-b-10">
                        <div class="col-md-3">
                            <label>{{ __('print.fields.indent_size') }}</label>
                        </div>
                        <div class="col-md-9">
                            <input class="qr_input" name="qr_indent_size" id="qr_indent_size" value="5" placeholder="5">
                            <input class="qr_range" type="range" min="0" max="30" name="qr_indent_size_range" id="qr_indent_size_range" value="5">
                        </div>
                    </div>
                    <div class="row m-b-10">
                        <div class="col-md-3">
                            <label>{{  __('print.fields.margin_size') }}</label>
                        </div>
                        <div class="col-md-9">
                            <input class="qr_input" name="qr_margin_size" id="qr_margin_size" value="2" placeholder="2">
                            <input class="qr_range" type="range" min="0" max="10" name="qr_margin_size_range" id="qr_margin_size_range" value="2">
                        </div>
                    </div>
                    <div class="row m-b-10">
                        <div class="col-md-3">
                            <label>{{ __('print.fields.border') }}</label>
                        </div>
                        <div class="col-md-9">
                            <input type="checkbox" name="qr_border" id="qr_border" checked="chacked">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" id="print_qr">{{ __('print.buttons.print') }}</button>
                        <button type="button" class="btn" data-dismiss="modal">{{ __('print.buttons.close') }}</button>
                    </div>
                </div>

                <div id="qr_wrapper" class="clearfix">
                    <p class="title text-center"></p>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        #print_qr_code .modal-content {
            width: 100%;
        }

        .title {
            font-size: 12pt;
            font-family: 'Open Sans', sans-serif;
            text-align: center;
        }

        #qr_wrapper {
            border: 1px solid #e3e3e3;
            clear: both;
            text-align: center;
        }

        .qr-item {
            background-color: #fff;
            border: 1px solid #000;
            padding: 5mm;
            margin: 5px;
            display: inline-block;
        }

        .qr_input {
            width: 10%;
        }
        .qr_range {
            display: inline !important;
            width: 89% !important;
            vertical-align: middle;
            cursor: pointer;
        }

        @media screen {
            #qr_wrapper {
                padding-top: 10px;
            }
        }

        @media print {
            @page {
                margin: 40px 0 0;
            }

            .title {
                display: none;
            }

            #print_qr_code {
                position: relative;
            }

            body,
            #print_qr_code .modal-body,
            #print_qr_code .modal-content {
                margin: 0;
                padding: 0;
            }

            body > *:not(#print_qr_code),
            #print_qr_code .modal-header,
            #print_qr_code .modal-footer,
            #print_qr_code .modal-body > *:not(#qr_wrapper) {
                display: none;
            }

            #print_qr_code .modal-dialog,
            #print_qr_code .modal-content{
                width: 100%!important;
                margin: 0;
                padding: 0;
            }

            #qr_wrapper {
                top: 0;
                margin: 0;
                border: 0!important;
                page-break-inside: auto;
            }
        }

    </style>
    <style type="text/css" media="print" class="auto-print-styles"></style>
@endpush

@push('scripts')
    <script type="text/javascript">
        let modal_dialog = document.querySelector('#print_qr_code .modal-dialog');
        let QRDataDefault = {
            image_count : 20,
            image_size  : 30, // 30mm
            indent_size : 5, // 5mm
            margin_size : 2, // 2mm
            border      : true,
            coefficient : 3.78,
            w_size_a4   : 210,
            h_size_a4   : 297,
        };
        let QRData = {
            image_count : QRDataDefault.image_count,
            image_size  : QRDataDefault.image_size,
            indent_size : QRDataDefault.indent_size,
            margin_size : QRDataDefault.margin_size,
            border      : QRDataDefault.border,
        };
        let qr_wrapper = document.getElementById('qr_wrapper');

        // RUN
        applyMainStyles();
        applyListeners();

        function applyMainStyles() {
            let storageStyles = getStyles();
            if ( storageStyles ) {
                QRData = storageStyles;
                updateFields();
            }

            let mainWidth = document.querySelector('main > .container').offsetWidth - 50;
            let maxWidth = QRDataDefault.coefficient * QRDataDefault.w_size_a4;
            if ( mainWidth > maxWidth ) mainWidth = maxWidth;

            modal_dialog.style.width = mainWidth + 'px';
            modal_dialog.style.margin = '0 auto';

            let modal_body = modal_dialog.getElementsByClassName('modal-body')[0];
            let modal_body_style = window.getComputedStyle(modal_body);
            let modal_body_width = mainWidth - parseFloat(modal_body_style.paddingLeft) - parseFloat(modal_body_style.paddingRight);

            QRData.coefficient = modal_body_width / QRDataDefault.w_size_a4;

            let styleSheets = document.getElementsByTagName('style');
            for ( let i in Object.keys(styleSheets) ) {
                if (styleSheets[i].classList.contains('auto-print-styles')) {
                    QRData.print_styles = styleSheets[i];
                }
            }
        }

        function updateFields() {
            for ( let key in QRData ) {
                let field = modal_dialog.querySelector('[name=qr_' + key + ']');
                if (field) {
                    if (typeof(QRData[key]) === 'boolean') {
                        modal_dialog.querySelector('[name=qr_' + key + ']').checked = QRData[key];
                        continue;
                    }
                    field.value = QRData[key];

                    let range = modal_dialog.querySelector('[name=qr_' + key + '_range]');
                    if (range) range.value = QRData[key];
                }
            }
        }

        // LISTENERS
        function applyListeners() {
            let open_dialog_btn = document.querySelectorAll('.qr-code-modal-open');

            // OPEN MODAL button
            for( let i = 0; i < open_dialog_btn.length; i++ ) {
                open_dialog_btn[i].addEventListener('click', function(e) {
                    e.preventDefault();

                    QRData.invite_code   = this.attributes['data-invite-code'].value;
                    QRData.place_name    = this.attributes['data-place-name'].value;
                    QRData.place_address = this.attributes['data-place-address'].value;

                    render();
                });
            }

            // PRINT button
            document.getElementById('print_qr').addEventListener('click', function() {
                let titlePage = document.getElementsByTagName('title')[0];
                titlePage.innerText = QRData.place_name + ( QRData.place_address ? ' - ' + QRData.place_address : '' );
                window.print();
            });

            // SETTINGS inputs listeners
            document.getElementById('qr_image_count').addEventListener('change', function() {
                QRData.image_count = ( this.value !== '' ) ? this.value : QRDataDefault.image_count;
                render();
            });
            document.getElementById('qr_image_size').addEventListener('change', function() {
                QRData.image_size = ( this.value !== '' ) ? this.value : QRDataDefault.image_size;
                applyStyles();
            });
            document.getElementById('qr_indent_size').addEventListener('change', function() {
                QRData.indent_size = ( this.value !== '' ) ? this.value : QRDataDefault.indent_size;
                applyStyles();
            });
            document.getElementById('qr_margin_size').addEventListener('change', function() {
                QRData.margin_size = ( this.value !== '' ) ? this.value : QRDataDefault.margin_size;
                applyStyles();
            });
            document.getElementById('qr_border').addEventListener('change', function() {
                QRData.border = this.checked;
                applyStyles();
            });
            document.getElementById('qr_image_size_range').addEventListener('input', function() {
                document.getElementById("qr_image_size").value = this.value;
                QRData.image_size = this.value;
                applyStyles();
            });
            document.getElementById('qr_indent_size_range').addEventListener('input', function() {
                document.getElementById("qr_indent_size").value = this.value;
                QRData.indent_size = this.value;
                applyStyles();
            });
            document.getElementById('qr_margin_size_range').addEventListener('input', function() {
                document.getElementById("qr_margin_size").value = this.value;
                QRData.margin_size = this.value;
                applyStyles();
            });
        }

        function makeParams() {
            let paramsArray = [];
            let params = {
                color    : '000000',
                bgcolor  : 'FFFFFF',
                format   : 'png',
                download : '1',
                size     : '300x300',
                data     : 'https://nau.app.link/?invite_code=' + QRData.invite_code
            };

            for ( let name in params ) {
                paramsArray.push(name + '=' + params[name]);
            }
            return paramsArray.join('&');
        }

        function render() {
            let service = 'https://api.qrserver.com/v1/create-qr-code/';
            let params  = makeParams();
            let qr_title   = qr_wrapper.getElementsByClassName('title')[0];
            qr_title.innerHTML = QRData.place_name + ( QRData.place_address ? ' - ' + QRData.place_address : '' );

            removeImages();
            createImages(service + '?' + params);
            applyStyles();
        }

        function createImages(src) {
            let imgBlock;

            for( let i = 0; i < QRData.image_count; i++ ) {
                imgBlock = document.createElement('div');
                imgBlock.className = 'qr-item';
                imgBlock.innerHTML = '<img src="' + src + '" alt="Place QR code">';
                qr_wrapper.appendChild(imgBlock);
            }
        }

        function removeImages() {
            let imgBlocks = qr_wrapper.querySelectorAll('.qr-item');
            if (!imgBlocks.length) return;
            for ( let i = 0; i < imgBlocks.length; i++ ) {
                imgBlocks[i].remove();
            }
        }

        function applyStyles() {
            let imgBlocks = qr_wrapper.querySelectorAll('.qr-item');
            if (!imgBlocks.length) return;
            let imgSize = parseInt( QRData.image_size * QRData.coefficient );
            let indentSize = parseInt( QRData.indent_size * QRData.coefficient );
            let marginSize = parseInt( QRData.margin_size * QRData.coefficient );

            [].forEach.call(imgBlocks, function(imgBlock){
                let img     = imgBlock.getElementsByTagName('img')[0];
                if (img) {
                    img.style = 'width:' + imgSize + 'px;' + 'height:' + imgSize + 'px;';
                }
                imgBlock.style.padding = indentSize + 'px';
                imgBlock.style.margin  = marginSize + 'px';
                imgBlock.style.border  = ( QRData.border ) ? '1px solid' : '0';
            });

            QRData.print_styles.innerText = '.qr-item { padding: ' + QRData.indent_size + 'mm; } '
               + '.qr-item img {width:' + QRData.image_size + 'mm !important;' + 'height:' + QRData.image_size + 'mm !important;}';

            saveStyles();
        }

        function saveStyles() {
            localStorage.setItem('QR-print-settings', JSON.stringify(QRData));
        }

        function getStyles() {
            return JSON.parse(localStorage.getItem('QR-print-settings'));
        }


    </script>
@endpush
