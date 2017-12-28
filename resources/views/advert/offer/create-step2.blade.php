<div id="tab_step2" class="tab-pane fade">

    <div class="control-box">
        <p class="title">Working dates</p>
        <p class="row control-datetime valid-dates">
            <label class="col-xs-6"><span class="input-label">from</span> <input name="start_date" readonly class="js-datepicker" placeholder="Select date" value=""></label>
            <label class="col-xs-6"><span class="input-label">to</span> <input name="finish_date" readonly class="js-datepicker" placeholder="Select date" value=""></label>
        </p>
        <p class="hint">Please, select the valid range of dates.</p>
    </div>

    <ul class="nav nav-tabs small">
        <li class="active"><a data-toggle="tab" href="#tab_wdt1">Simple</a></li>
        <li><a data-toggle="tab" href="#tab_wdt2">Detailed</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab_wdt1" class="tab-pane fade in active">
            <p class="title">Working days</p>
            <p class="control-check-left"><input name="____wd_working_days" type="checkbox" id="check_wd8" checked><label for="check_wd8"><span class="input-label">Working Days</span></label></p>
            <p class="control-check-left"><input name="____wd_weekend" type="checkbox" id="check_wd9"><label for="check_wd9"><span class="input-label">Weekend</span></label></p>
            <p class="title">Working time</p>
            <p class="row control-datetime">
                <label class="col-xs-6"><span class="input-label">from</span> <input name="start_time" readonly class="js-timepicker" value="" id="time_wd8f" placeholder="__:__"></label>
                <label class="col-xs-6"><span class="input-label">to</span> <input name="finish_time" readonly class="js-timepicker" value="" id="time_wd8t" placeholder="__:__"></label>
            </p>
        </div>

        <div id="tab_wdt2" class="tab-pane fade">
            <p class="title">Working days &amp; time <small>You can set working time more flexibility</small></p>
            <p class="row">
                <span class="col-xs-2">Work</span>
                <span class="col-xs-2">Day</span>
                <span class="col-xs-4">From</span>
                <span class="col-xs-4">To</span>
            </p>
            <p class="row day-info">
                <span class="col-xs-2">
                    <span class="control-check-left"><input name="____wd_mon" type="checkbox" id="check_wd1" data-relation="check_wd8" data-weekday="mo" checked><label for="check_wd1">&nbsp;</label></span>
                </span>
                <strong class="col-xs-2">Mon</strong>
                <span class="col-xs-4 control-datetime">
                    <label><input name="____start_time_mon" readonly class="js-timepicker" value="" data-relation="time_wd8f" data-weekday="mo" placeholder="__:__"></label>
                </span>
                <span class="col-xs-4 control-datetime">
                    <label><input name="____finish_time_mon" readonly class="js-timepicker" value="" data-relation="time_wd8t" data-weekday="mo" placeholder="__:__"></label>
                </span>
            </p>
            <p class="row day-info">
                <span class="col-xs-2">
                    <span class="control-check-left"><input name="____wd_tue" type="checkbox" id="check_wd2" data-relation="check_wd8" data-weekday="tu" checked><label for="check_wd2">&nbsp;</label></span>
                </span>
                <strong class="col-xs-2">Tue</strong>
                <span class="col-xs-4 control-datetime">
                    <label><input name="____start_time_tue" readonly class="js-timepicker" value="" data-relation="time_wd8f" data-weekday="tu" placeholder="__:__"></label>
                </span>
                <span class="col-xs-4 control-datetime">
                    <label><input name="____finish_time_tue" readonly class="js-timepicker" value="" data-relation="time_wd8t" data-weekday="tu" placeholder="__:__"></label>
                </span>
            </p>
            <p class="row day-info">
                <span class="col-xs-2">
                    <span class="control-check-left"><input name="____wd_wed" type="checkbox" id="check_wd3" data-relation="check_wd8" data-weekday="we" checked><label for="check_wd3">&nbsp;</label></span>
                </span>
                <strong class="col-xs-2">Wed</strong>
                <span class="col-xs-4 control-datetime">
                    <label><input name="____start_time_wed" readonly class="js-timepicker" value="" data-relation="time_wd8f" data-weekday="we" placeholder="__:__"></label>
                </span>
                <span class="col-xs-4 control-datetime">
                    <label><input name="____finish_time_wed" readonly class="js-timepicker" value="" data-relation="time_wd8t" data-weekday="we" placeholder="__:__"></label>
                </span>
            </p>
            <p class="row day-info">
                <span class="col-xs-2">
                    <span class="control-check-left"><input name="____wd_thu" type="checkbox" id="check_wd4" data-relation="check_wd8" data-weekday="th" checked><label for="check_wd4">&nbsp;</label></span>
                </span>
                <strong class="col-xs-2">Thu</strong>
                <span class="col-xs-4 control-datetime">
                    <label><input name="____start_time_thu" readonly class="js-timepicker" value="" data-relation="time_wd8f" data-weekday="th" placeholder="__:__"></label>
                </span>
                <span class="col-xs-4 control-datetime">
                    <label><input name="____finish_time_thu" readonly class="js-timepicker" value="" data-relation="time_wd8t" data-weekday="th" placeholder="__:__"></label>
                </span>
            </p>
            <p class="row day-info">
                <span class="col-xs-2">
                    <span class="control-check-left"><input name="____wd_fri" type="checkbox" id="check_wd5" data-relation="check_wd8" data-weekday="fr" checked><label for="check_wd5">&nbsp;</label></span>
                </span>
                <strong class="col-xs-2">Fri</strong>
                <span class="col-xs-4 control-datetime">
                    <label><input name="____start_time_fri" readonly class="js-timepicker" value="" data-relation="time_wd8f" data-weekday="fr" placeholder="__:__"></label>
                </span>
                <span class="col-xs-4 control-datetime">
                    <label><input name="____finish_time_fri" readonly class="js-timepicker" value="" data-relation="time_wd8t" data-weekday="fr" placeholder="__:__"></label>
                </span>
            </p>
            <p class="row day-info">
                <span class="col-xs-2">
                    <span class="control-check-left"><input name="timeframes['days'][]" value="sa" type="checkbox" id="check_wd6" data-weekday="sa" data-relation="check_wd9"><label for="check_wd6">&nbsp;</label></span>
                </span>
                <strong class="col-xs-2">Sat</strong>
                <span class="col-xs-4 control-datetime">
                    <label><input name="____start_time_sat" readonly class="js-timepicker" value="" data-relation="time_wd8f" data-weekday="sa" placeholder="__:__"></label>
                </span>
                <span class="col-xs-4 control-datetime">
                    <label><input name="____finish_time_sat" readonly class="js-timepicker" value="" data-relation="time_wd8t" data-weekday="sa" placeholder="__:__"></label>
                </span>
            </p>
            <p class="row day-info">
                <span class="col-xs-2">
                    <span class="control-check-left"><input name="timeframes['days'][]" value="su" type="checkbox" id="check_wd7" data-weekday="su" data-relation="check_wd9"><label for="check_wd7">&nbsp;</label></span>
                </span>
                <strong class="col-xs-2">Sun</strong>
                <span class="col-xs-4 control-datetime">
                    <label><input name="____start_time_sun" readonly class="js-timepicker" value="" data-relation="time_wd8f" data-weekday="su" placeholder="__:__"></label>
                </span>
                <span class="col-xs-4 control-datetime">
                    <label><input name="____finish_time_sun" readonly class="js-timepicker" value="" data-relation="time_wd8t" data-weekday="su" placeholder="__:__"></label>
                </span>
            </p>
            <p class="hint working-dt-hint">Please, select the days and set the time.</p>
        </div>
    </div>

    <p class="step-footer">
        <a href="#tab_step1" data-toggle="tab" class="tab-nav btn-nau pull-left">&lt; prev step</a>
        <input type="submit" class="btn-nau pull-right" value="Create Offer">
        <a href="#tab_step3" data-toggle="tab" class="tab-nav btn-nau pull-right">next step &gt;</a>
    </p>
</div>