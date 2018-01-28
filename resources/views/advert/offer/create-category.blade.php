<p class="title">Offer Type</p>

@if($isPlaceCreated)
    <input type="hidden" name="category_id" class="formData" value="<?php if (count(auth()->user()->place->category)) { echo auth()->user()->place->category->first()->id; } ?>">
    <script>
        (function () {
            if (document.querySelector('[name="category_id"]').value === '') {
                alert('You must choose Category for Place');
                window.location.replace("{{ route('profile.place.show') }}");
            }
        })();
    </script>
@else
    <script>
        alert('Fill place info');
        window.location.replace("{{ route('places.create') }}");
    </script>
@endif

<div class="control-box offer-type-box">
    <p class="control-radio-left">
        <input name="offer_type" type="radio" id="discount_radio" checked value="discount">
        <label for="discount_radio">
            <span class="input-label">Discount</span>
        </label>
    </p>
    <div class="sub-radio">
        <p class="control-text clearfix">
            <span class="input-label">Set discount (%) *</span>
            <label class="pull-right">
                <input name="discount_percent" class="formData offer-type-control" value="5">
            </label>
        </p>
        <p class="control-range">
            <span class="input-label">Start price</span>
            <label>
                <input name="discount_start_price" data-min="0" data-max="999999999" data-default="0" value="0" class="js-numeric">
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
                let html = '';
                <?php echo json_encode(\App\Helpers\Constants::CURRENCIES); ?>.forEach(function(cur){
                    html += `<option value="${cur}">${cur}</option>`;
                });
                document.querySelector('[name="currency"]').innerHTML = html;
            })();
        </script>
    </div>
    <p class="control-radio-left">
        <input name="offer_type" type="radio" id="second_free_radio" value="second_free">
        <label for="second_free_radio">
            <span class="input-label">Second Free</span>
        </label>
    </p>
    <p class="control-radio-left">
        <input name="offer_type" type="radio" id="bonus_radio" value="bonus">
        <label for="bonus_radio">
            <span class="input-label">Bonus</span>
        </label>
    </p>
    <p class="control-text sub-radio">
        <label>
            <span class="input-label">Bonus information *</span>
            <input name="bonus_information" id="bonus_information" value="">
        </label>
    </p>
    <p class="control-radio-left">
        <input name="offer_type" type="radio" id="gift_radio" value="gift">
        <label for="gift_radio">
            <span class="input-label">Gift</span>
        </label>
    </p>
    <p class="control-text sub-radio">
        <label>
            <span class="input-label">Gift information *</span>
            <input name="gift_information" id="gift_information" value="">
        </label>
    </p>
    <p class="control-check-left">
        <input name="delivery" type="checkbox" id="check_delivery" value="delivery">
        <label for="check_delivery">
            <span class="input-label">Delivery</span>
        </label>
    </p>
</div>
<p class="hint" id="hint_offertypebox">Please, enter the information.</p>

