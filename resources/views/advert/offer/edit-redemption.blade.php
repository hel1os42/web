<div id="redemptions">
    <p class="title">Max redemption total <small>Zero is infinity</small></p>
    <p class="control-range max-redemption"><span class="input-label">Overral</span> <label><input name="max_count" data-min="0" data-max="1000" data-default="{{ ($max_count === null) ? 0 : $max_count }}" value="" class="js-numeric nullableLimit"></label></p>
    <p class="control-range max-redemption"><span class="input-label">Daily</span> <label><input name="max_per_day" data-min="0" data-max="1000" data-default="{{ ($max_per_day === null) ? 0 : $max_per_day }}" value="" class="js-numeric nullableLimit"></label></p>

    <p class="title">Max redemption per user <small>Zero is infinity</small></p>
    <p class="control-range max-redemption"><span class="input-label">Overral</span> <label><input name="max_for_user" data-min="0" data-max="1000" data-default="{{ ($max_for_user === null) ? 0 : $max_for_user }}" value="" class="js-numeric nullableLimit"></label></p>
    <p class="control-range max-redemption"><span class="input-label">Daily</span> <label><input name="max_for_user_per_day" data-min="0" data-max="1000" data-default="{{ ($max_for_user_per_day === null) ? 0 : $max_for_user_per_day }}" value="" class="js-numeric nullableLimit"></label></p>
    <p class="control-range max-redemption"><span class="input-label">Weekly</span> <label><input name="max_for_user_per_week" data-min="0" data-max="1000" data-default="{{ ($max_for_user_per_week === null) ? 0 : $max_for_user_per_week }}" value="" class="js-numeric nullableLimit"></label></p>
    <p class="control-range max-redemption"><span class="input-label">Monthly</span> <label><input name="max_for_user_per_month" data-min="0" data-max="1000" data-default="{{ ($max_for_user_per_month === null) ? 0 : $max_for_user_per_month }}" value="" class="js-numeric nullableLimit"></label></p>

    <p class="title">Other limits</p>
    <p class="control-range"><span class="input-label">Minimal user level *</span> <label><input name="user_level_min" data-min="1" data-max="99" data-default="{{ $user_level_min }}" value="" class="js-numeric formData"></label></p>

    <p class="title">Reward options</p>
    <p class="control-range"><span class="input-label">Reward for redemption *</span> <label><input name="reward" data-min="1" data-max="999999" data-default="{{ $reward }}" value="" class="js-numeric formData"></label></p>
    <p class="control-range"><span class="input-label">Token reservation *</span> <label><input name="reserved" data-min="10" data-max="9999999" data-default="{{ $reserved }}" value="" class="js-numeric formData"></label></p>

    <p class="control-range">
    <span class="input-label">
        {{ __('offers.points_for_redemption') }}
    </span>
        <label>
            <input name="points" data-min="0" data-max="9999999" data-default="{{ old('points', $points) }}" value="{{ old('points', $points) }}" class="js-numeric formData" />
        </label>
    </p>
</div>

<script>
    (function(){
        document.querySelector('#redemptions').querySelectorAll('[data-default]').forEach(function(input){
            input.value = input.dataset.default;
        });
    })();
</script>