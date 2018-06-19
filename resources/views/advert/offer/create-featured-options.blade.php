<p class="title">
    {{ __('offers.text.featured_options') }}
</p>
<p class="control-check-left mb-2">
    <input name="is_featured" type="checkbox" id="is_featured" value="1">
    <label for="is_featured">
        <span class="input-label">
            {{ __('offers.fields.is_featured') }}
        </span>
    </label>
</p>
<p class="control-range">
    <span class="input-label">
        {{ __('offers.fields.referral_points_price') }}
    </span>
    <label>
        <input name="referral_points_price"
               data-min="0"
               data-max="999999"
               data-default="0"
               value="0"
               class="js-numeric formData">
    </label>
</p>
<p class="control-range">
    <span class="input-label">
        {{ __('offers.fields.redemption_points_price') }}
    </span>
    <label>
        <input name="redemption_points_price"
               data-min="0"
               data-max="999999"
               data-default="0"
               value="0"
               class="js-numeric formData">
    </label>
</p>
