<p class="title">Offer Type</p>

<input type="hidden" name="category_id" class="formData" value="{{ auth()->user()->place->category->first()->id }}">

<div class="control-box offer-type-box">
    <p class="control-radio-left">
        <input name="offer_type" type="radio" id="discount_radio" value="discount"{{ $type === 'discount' ? ' checked' : '' }}>
        <label for="discount_radio">
            <span class="input-label">Discount</span>
        </label>
    </p>
    <div class="sub-radio">
        <p class="control-text clearfix">
            <span class="input-label">Set discount (%) *</span>
            <label class="pull-right">
                <input name="discount_percent" class="formData offer-type-control" value="{{ $discount_percent }}">
            </label>
        </p>
        <p class="control-range">
            <span class="input-label">Start price</span>
            <label>
                <input name="discount_start_price" data-min="0" data-max="999999999" data-default="0" value="{{ $discount_start_price ?: '0' }}" class="js-numeric">
            </label>
        </p>
        <p class="control-select clearfix">
            <span class="input-label">Currency</span>
            <label class="pull-right">
                <select name="currency" class="offer-type-control"></select>
            </label>
        </p>
        <script>
            (function(){
                let html = '', checked;
                let currency = "{{ $currency }}";
                <?php echo json_encode(\App\Helpers\Constants::CURRENCIES); ?>.forEach(function(cur){
                    checked = currency === cur ? 'checked' : '';
                    html += `<option value="${cur}" ${checked}>${cur}</option>`;
                });
                document.querySelector('[name="currency"]').innerHTML = html;
            })();
        </script>
    </div>
    <p class="control-radio-left">
        <input name="offer_type" type="radio" id="second_free_radio" value="second_free"{{ $type === 'second_free' ? ' checked' : '' }}>
        <label for="second_free_radio">
            <span class="input-label">Second Free</span>
        </label>
    </p>
    <p class="control-radio-left">
        <input name="offer_type" type="radio" id="bonus_radio" value="bonus"{{ $type === 'bonus' ? ' checked' : '' }}>
        <label for="bonus_radio">
            <span class="input-label">Bonus</span>
        </label>
    </p>
    <p class="control-text sub-radio">
        <label>
            <span class="input-label">Bonus information *</span>
            <input name="bonus_information" id="bonus_information" value="{{ $type === 'bonus' ? $gift_bonus_descr : '' }}">
        </label>
    </p>
    <p class="control-radio-left">
        <input name="offer_type" type="radio" id="gift_radio" value="gift"{{ $type === 'gift' ? ' checked' : '' }}>
        <label for="gift_radio">
            <span class="input-label">Gift</span>
        </label>
    </p>
    <p class="control-text sub-radio">
        <label>
            <span class="input-label">Gift information *</span>
            <input name="gift_information" id="gift_information" value="{{ $type === 'gift' ? $gift_bonus_descr : '' }}">
        </label>
    </p>
    <p class="control-check-left">
        <input name="delivery" type="checkbox" id="check_delivery" value="delivery"{{ $delivery ? ' checked' : '' }}>
        <label for="check_delivery">
            <span class="input-label">Delivery</span>
        </label>
    </p>
</div>
<p class="hint" id="hint_offertypebox">Please, enter the information.</p>
