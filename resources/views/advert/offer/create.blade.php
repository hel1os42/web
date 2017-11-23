@extends('layouts.master')

@section('title', 'Create offer')

@section('content')

<div class="col-md-12">
	<div class="content">
		<h3 class="title">Create advert</h3>
		<div class="card">

			<link rel="stylesheet" href="{{ asset('css/create-offer.css') }}" type="text/css">
			
			<form action="{{route('advert.offers.store')}}" method="post" class="form" id="createOfferForm" target="_top">
				{{ csrf_field() }}

				<ul class="tabs-steps js-tabs">
					<li data-target="tab_step1"><em>1</em> Main<br>Information</li>
					<li data-target="tab_step2"><em>2</em> Working Dates<br>&amp; Times</li>
					<li data-target="tab_step3"><em>3</em> Working<br>Area</li>
					<li data-target="tab_step4"><em>4</em> Additional<br>Settings</li>
				</ul>

				<div id="tab_step1">

					<p class="title">Name and Description</p>
					<p class="control-text valid-not-empty"><label><span class="input-label">Offer name</span> <input name="label" value="{{old('label')}}"</label></p>
					<p class="hint">Please, enter the Offer name.</p>
					<p class="control-text valid-not-empty"><label><span class="input-label">Offer description</span> <textarea name="description">{{old('description')}}</textarea></label></p>
					<p class="hint">Please, enter the Offer description.</p>
					<p><span class="input-label">Offer picture</span><br><label class="control-file"><span class="text-add">Add picture</span> <input name="____offer_picture" type="file" class="js-imgupload" id="offerImg"><img src="" alt=""><span class="text-hover">Drag it here</span></label></p>

					<p class="title">Category &amp; Type</p>

					<p class="control-select valid-not-empty">
						<label>
							<span class="input-label">Offer category</span>
							<select id="offer_category" name="category_id"></select>
						</label>
					</p>
					<p class="hint">Please, select the category.</p>


					<p>Offer type</p>
					<p class="control-radio-left"><input name="____r2" type="radio" id="radio_ot1" checked value="Discount"><label for="radio_ot1"><span class="input-label">Discount</span> <small>Description for this item</small></label></p>
					<p class="control-radio-left"><input name="____r2" type="radio" id="radio_ot2" value="Second Free"><label for="radio_ot2"><span class="input-label">Second Free</span> <small>Description for this item</small></label></p>
					<p class="control-radio-left"><input name="____r2" type="radio" id="radio_ot3" value="Bonus or Gift"><label for="radio_ot3"><span class="input-label">Bonus or Gift</span> <small>Description for this item</small></label></p>
					<p class="control-range" id="discount_value"><span class="input-label">Set discount (%)</span> <em role="button" class="more">+</em><label><input name="reward" data-min="0" data-max="99" data-default="10" value="10" class="js-numeric" value="{{old('reward')}}"></label><em role="button" class="less">-</em></p>

					<p class="tab-footer">
						<span class="btn-nau pull-right" data-tab-target="tab_step2">next step &gt;</span>
					</p>
				</div>

				<div id="tab_step2">
					<p class="title">Working dates</p>
					<p class="row control-datetime valid-dates">
						<label class="col-xs-6"><span class="input-label">from</span> <input name="start_date" readonly class="js-datepicker" placeholder="Select date" value="{{old('start_date')}}"></label>
						<label class="col-xs-6"><span class="input-label">to</span> <input name="finish_date" readonly class="js-datepicker" placeholder="Select date" value="{{old('finish_date')}}"></label>
					</p>
					<p class="hint">Please, select the valid range of dates.</p>

					<ul class="tabs-controls js-tabs">
						<li data-target="tab_wdt1">Simple</li>
						<li data-target="tab_wdt2">Detailed</li>
					</ul>

					<div class="tab-content" id="tab_wdt1">
						<p class="title">Working days</p>
						<p class="control-check-left"><input name="____wd_working_days" type="checkbox" id="check_wd8" checked><label for="check_wd8"><span class="input-label">Working Days</span></label></p>
						<p class="control-check-left"><input name="____wd_weekend" type="checkbox" id="check_wd9"><label for="check_wd9"><span class="input-label">Weekend</span></label></p>
						<p class="title">Working time</p>
						<p class="row control-datetime">
							<label class="col-xs-6"><span class="input-label">from</span> <input name="start_time" readonly class="js-timepicker" value="{{old('start_time')}}" id="time_wd8f" placeholder="__:__"></label>
							<label class="col-xs-6"><span class="input-label">to</span> <input name="finish_time" readonly class="js-timepicker" value="{{old('finish_time')}}" id="time_wd8t" placeholder="__:__"></label>
						</p>
					</div>

					<div class="tab-content" id="tab_wdt2">
						<p class="title">Working days &amp; time <small>You can set working time more flexibility</small></p>
						<p class="row">
							<span class="col-xs-2">Work</span>
							<span class="col-xs-2">Day</span>
							<span class="col-xs-4">From</span>
							<span class="col-xs-4">To</span>
						</p>
						<p class="row day-info">
							<span class="col-xs-2">
								<span class="control-check-left"><input name="____wd_mon" type="checkbox" id="check_wd1" data-relation="check_wd8" checked><label for="check_wd1">&nbsp;</label></span>
							</span>
							<strong class="col-xs-2">Mon</strong>
							<span class="col-xs-4 control-datetime">
								<label><input name="____start_time_mon" readonly class="js-timepicker" value="" data-relation="time_wd8f_wd" placeholder="__:__"></label>
							</span>
							<span class="col-xs-4 control-datetime">
								<label><input name="____finish_time_mon" readonly class="js-timepicker" value="" data-relation="time_wd8t_wd" placeholder="__:__"></label>
							</span>
						</p>
						<p class="row day-info">
							<span class="col-xs-2">
								<span class="control-check-left"><input name="____wd_tue" type="checkbox" id="check_wd2" data-relation="check_wd8" checked><label for="check_wd2">&nbsp;</label></span>
							</span>
							<strong class="col-xs-2">Tue</strong>
							<span class="col-xs-4 control-datetime">
								<label><input name="____start_time_tue" readonly class="js-timepicker" value="" data-relation="time_wd8f_wd" placeholder="__:__"></label>
							</span>
							<span class="col-xs-4 control-datetime">
								<label><input name="____finish_time_tue" readonly class="js-timepicker" value="" data-relation="time_wd8t_wd" placeholder="__:__"></label>
							</span>
						</p>
						<p class="row day-info">
							<span class="col-xs-2">
								<span class="control-check-left"><input name="____wd_wed" type="checkbox" id="check_wd3" data-relation="check_wd8" checked><label for="check_wd3">&nbsp;</label></span>
							</span>
							<strong class="col-xs-2">Wed</strong>
							<span class="col-xs-4 control-datetime">
								<label><input name="____start_time_wed" readonly class="js-timepicker" value="" data-relation="time_wd8f_wd" placeholder="__:__"></label>
							</span>
							<span class="col-xs-4 control-datetime">
								<label><input name="____finish_time_wed" readonly class="js-timepicker" value="" data-relation="time_wd8t_wd" placeholder="__:__"></label>
							</span>
						</p>
						<p class="row day-info">
							<span class="col-xs-2">
								<span class="control-check-left"><input name="____wd_thu" type="checkbox" id="check_wd4" data-relation="check_wd8" checked><label for="check_wd4">&nbsp;</label></span>
							</span>
							<strong class="col-xs-2">Thu</strong>
							<span class="col-xs-4 control-datetime">
								<label><input name="____start_time_thu" readonly class="js-timepicker" value="" data-relation="time_wd8f_wd" placeholder="__:__"></label>
							</span>
							<span class="col-xs-4 control-datetime">
								<label><input name="____finish_time_thu" readonly class="js-timepicker" value="" data-relation="time_wd8t_wd" placeholder="__:__"></label>
							</span>
						</p>
						<p class="row day-info">
							<span class="col-xs-2">
								<span class="control-check-left"><input name="____wd_fri" type="checkbox" id="check_wd5" data-relation="check_wd8" checked><label for="check_wd5">&nbsp;</label></span>
							</span>
							<strong class="col-xs-2">Fri</strong>
							<span class="col-xs-4 control-datetime">
								<label><input name="____start_time_fri" readonly class="js-timepicker" value="" data-relation="time_wd8f_wd" placeholder="__:__"></label>
							</span>
							<span class="col-xs-4 control-datetime">
								<label><input name="____finish_time_fri" readonly class="js-timepicker" value="" data-relation="time_wd8t_wd" placeholder="__:__"></label>
							</span>
						</p>
						<p class="row day-info">
							<span class="col-xs-2">
								<span class="control-check-left"><input name="____wd_sat" type="checkbox" id="check_wd6" data-relation="check_wd9"><label for="check_wd6">&nbsp;</label></span>
							</span>
							<strong class="col-xs-2">Sat</strong>
							<span class="col-xs-4 control-datetime">
								<label><input name="____start_time_sat" readonly class="js-timepicker" value="" data-relation="time_wd8f_we" placeholder="__:__"></label>
							</span>
							<span class="col-xs-4 control-datetime">
								<label><input name="____finish_time_sat" readonly class="js-timepicker" value="" data-relation="time_wd8t_we" placeholder="__:__"></label>
							</span>
						</p>
						<p class="row day-info">
							<span class="col-xs-2">
								<span class="control-check-left"><input name="____wd_sun" type="checkbox" id="check_wd7" data-relation="check_wd9"><label for="check_wd7">&nbsp;</label></span>
							</span>
							<strong class="col-xs-2">Sun</strong>
							<span class="col-xs-4 control-datetime">
								<label><input name="____start_time_sun" readonly class="js-timepicker" value="" data-relation="time_wd8f_we" placeholder="__:__"></label>
							</span>
							<span class="col-xs-4 control-datetime">
								<label><input name="____finish_time_sun" readonly class="js-timepicker" value="" data-relation="time_wd8t_we" placeholder="__:__"></label>
							</span>
						</p>
						<p class="hint working-dt-hint">Please, select the days and set the time.</p>
					</div>

					<p class="tab-footer">
						<span class="btn-nau pull-left" data-tab-target="tab_step1">&lt; prev step</span>
						<span class="btn-nau pull-right" data-tab-target="tab_step3">next step &gt;</span>
					</p>
				</div>

				<div id="tab_step3">
					<p class="title">Working area</p>
					<p class="control-select valid-not-empty">
						<label>
							<span class="input-label">Your place</span>
							<select name="____place">
								<option value="" selected>Select a place</option>
								<option value="Place 1">Place 1</option>
								<option value="Place 2">Place 2</option>
								<option value="Place 3">Place 3</option>
							</select>
						</label>
					</p>
					<p class="hint">Please, select a place.</p>
					
					<p class="title">Setting map radius</p>
					[ insert map here ]
					<iframe src="" style="height: 400px; background: #ff5a00 linear-gradient(to right, #ff5a00, #ff8b10);"></iframe>

					<p class="tab-footer">
						<span class="btn-nau pull-left" data-tab-target="tab_step2">&lt; prev step</span>
						<span class="btn-nau pull-right" data-tab-target="tab_step4">next step &gt;</span>
					</p>
				</div>

				<div id="tab_step4">
					<p class="title">Max redemption total <small>Zero is infinity</small></p>
					<p class="control-range max-redemption"><span class="input-label">Overral</span> <em role="button" class="more">+</em><label><input name="max_count" data-min="0" data-max="1000" data-default="0" value="{{old('max_count')}}" class="js-numeric"></label><em role="button" class="less">-</em></p>
					<p class="control-range max-redemption"><span class="input-label">Daily</span> <em role="button" class="more">+</em><label><input name="max_per_day" data-min="0" data-max="1000" data-default="0" value="{{old('max_per_day')}}" class="js-numeric"></label><em role="button" class="less">-</em></p>
					<p class="control-range max-redemption"><span class="input-label">Weekly</span> <em role="button" class="more">+</em><label><input name="max_per_week" data-min="0" data-max="1000" data-default="0" value="0" class="js-numeric"></label><em role="button" class="less">-</em></p>
					<p class="control-range max-redemption"><span class="input-label">Monthly</span> <em role="button" class="more">+</em><label><input name="max_per_month" data-min="0" data-max="1000" data-default="0" value="0" class="js-numeric"></label><em role="button" class="less">-</em></p>

					<p class="title">Max redemption per user <small>Zero is infinity</small></p>
					<p class="control-range max-redemption"><span class="input-label">Overral</span> <em role="button" class="more">+</em><label><input name="max_for_user" data-min="0" data-max="1000" data-default="0" value="{{old('max_for_user')}}" class="js-numeric"></label><em role="button" class="less">-</em></p>
					<p class="control-range max-redemption"><span class="input-label">Daily</span> <em role="button" class="more">+</em><label><input name="max_for_user_per_day" data-min="0" data-max="1000" data-default="0" value="{{old('max_for_user_per_day')}}" class="js-numeric"></label><em role="button" class="less">-</em></p>
					<p class="control-range max-redemption"><span class="input-label">Weekly</span> <em role="button" class="more">+</em><label><input name="max_for_user_per_week" data-min="0" data-max="1000" data-default="0" value="0" class="js-numeric"></label><em role="button" class="less">-</em></p>
					<p class="control-range max-redemption"><span class="input-label">Monthly</span> <em role="button" class="more">+</em><label><input name="max_for_user_per_month" data-min="0" data-max="1000" data-default="0" value="0" class="js-numeric"></label><em role="button" class="less">-</em></p>

					<p class="title">Other limits</p>
					<p class="control-range"><span class="input-label">Minimal user level</span> <em role="button" class="more">+</em><label><input name="user_level_min" data-min="0" data-max="99" data-default="0" value="{{old('user_level_min')}}" class="js-numeric"></label><em role="button" class="less">-</em></p>

					<p class="title">Reward options</p>
					<p class="control-range"><span class="input-label">Reward for redemption</span> <em role="button" class="more">+</em><label><input name="____reward_redemption" data-min="0" data-max="999999" data-default="10" value="10" class="js-numeric"></label><em role="button" class="less">-</em></p>
					<p class="control-range"><span class="input-label">Token reservation</span> <em role="button" class="more">+</em><label><input name="____token_reservation" data-min="0" data-max="999999" data-default="55" value="55" class="js-numeric"></label><em role="button" class="less">-</em></p>

					<p class="tokens-total"><strong>235</strong> <span>You have tokens on your account</span></p>

					<p class="tab-footer">
						<span class="btn-nau pull-left" data-tab-target="tab_step3">&lt; prev step</span>
						<input type="submit" class="btn-nau pull-right" value="Create Offer">
					</p>
				</div>

			</form>					

			<script type="text/javascript">
				/* offer_category */
				var xmlhttp = new XMLHttpRequest();

				xmlhttp.onreadystatechange = function () {
					if (xmlhttp.readyState == XMLHttpRequest.DONE) {
						if (xmlhttp.status == 200) {
							document.getElementById("offer_category").innerHTML = xmlhttp.responseText;
						}
						else if (xmlhttp.status == 400) {
							alert('There was an error 400');
						}
						else {
							alert('something else other than 200 was returned');
						}
					}
				};

				xmlhttp.open("GET", "{{route('categories')}}", true);
				xmlhttp.send();
			</script>

			
			<div id="formOverlay">
				<div id="formInformationModal"><p class="msg">Sending...</p><img src="{{ asset('img/loading.gif') }}" alt="loading..." class="loading"></div>
			</div>

			<script src="{{ asset('js/datetimepicker.js') }}"></script>
			<script src="{{ asset('js/create-offer.js') }}"></script>
	
			<link href="{{ asset('css/datetimepicker.css') }}" rel="stylesheet" type="text/css">
					
		</div>
	</div>
</div>

@stop
