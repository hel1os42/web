<p class="title">Max redemption total <small>Zero is infinity</small></p>
<p class="control-range max-redemption"><span class="input-label">Overral</span> <label><input name="max_count" data-min="0" data-max="1000" data-default="0" value="0" class="js-numeric nullableLimit"></label></p>
<p class="control-range max-redemption"><span class="input-label">Daily</span> <label><input name="max_per_day" data-min="0" data-max="1000" data-default="0" value="0" class="js-numeric nullableLimit"></label></p>

<p class="title">Max redemption per user <small>Zero is infinity</small></p>
<p class="control-range max-redemption"><span class="input-label">Overral</span> <label><input name="max_for_user" data-min="0" data-max="1000" data-default="0" value="0" class="js-numeric nullableLimit"></label></p>
<p class="control-range max-redemption"><span class="input-label">Daily</span> <label><input name="max_for_user_per_day" data-min="0" data-max="1000" data-default="0" value="0" class="js-numeric nullableLimit"></label></p>
<p class="control-range max-redemption"><span class="input-label">Weekly</span> <label><input name="max_for_user_per_week" data-min="0" data-max="1000" data-default="0" value="0" class="js-numeric nullableLimit"></label></p>
<p class="control-range max-redemption"><span class="input-label">Monthly</span> <label><input name="max_for_user_per_month" data-min="0" data-max="1000" data-default="0" value="0" class="js-numeric nullableLimit"></label></p>

<p class="title">Other limits</p>
<p class="control-range"><span class="input-label">Minimal user level *</span> <label><input name="user_level_min" data-min="1" data-max="99" data-default="1" value="1" class="js-numeric formData"></label></p>

<p class="title">Reward options</p>
<p class="control-range"><span class="input-label">Reward for redemption *</span> <label><input name="reward" data-min="1" data-max="999999" data-default="1" value="1" class="js-numeric formData"></label></p>
<p class="control-range"><span class="input-label">Token reservation *</span> <label><input name="reserved" data-min="10" data-max="999999" data-default="10" value="10" class="js-numeric formData"></label></p>

<p class="tokens-total"><strong>{{ $authUser['accounts']['NAU']['balance'] }}</strong> <span>You have tokens on your account</span></p>

<p><input type="submit" class="btn-nau pull-right" value="Create Offer"></p>
