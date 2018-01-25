<div class="control-box" style="margin-top: 32px;">
    <p class="title">Working dates</p>
    <p class="row control-datetime valid-dates">
        <label class="col-xs-6"><span class="input-label">from *</span> <input name="start_date" readonly class="js-datepicker" placeholder="Select date" value=""></label>
        <label class="col-xs-6"><span class="input-label">to</span> <input name="finish_date" readonly class="js-datepicker" placeholder="Select date" value=""></label>
    </p>
    <p class="hint">Please, select the valid range of dates.</p>
</div>

<p class="title">Working days &amp; time</p>

<div class="days-info">
    <div class="select-working-days-wrap">
        <span id="selectWorkingDays" class="btn btn-xs btn-nau">Select WorkingDays</span>
    </div>
    <div class="select-weekends-wrap">
        <span id="selectWeekends" class="btn btn-xs btn-nau">Weekends</span>
    </div>

    <div class="row">
        <div class="col-xs-offset-1 col-lg-6 col-md-8 col-sm-8 col-xs-11">

            <p class="row">
                <span class="col-xs-4">Work</span>
                <span class="col-xs-4">From</span>
                <span class="col-xs-4">To</span>
            </p>

            <div id="dayInfoBox"></div>

            <p class="hint working-dt-hint">Please, select the days and set the time.</p>

        </div>
    </div>

    <script>
        (function(){
            let days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
            let html = '', weekend, Day, day2;
            days.forEach(function(day){
                weekend = day === 'sat' || day === 'sun';
                Day = day[0].toUpperCase() + day.substr(1);
                day2 = day.substr(0, 2);
                html += `<p class="row day-info"><span class="col-xs-4"><span class="control-check-left">`;
                html += `<input id="check_${day2}" name="check_${day2}" value="${day2}" type="checkbox">`;
                html += `<label for="check_${day2}">${Day}</label></span></span>`;
                html += `<span class="col-xs-4 control-datetime"><label>`;
                html += `<input name="start_time_${day2}" readonly class="js-timepicker" value="" placeholder="__:__">`;
                html += `</label></span><span class="col-xs-4 control-datetime"><label>`;
                html += `<input name="finish_time_${day2}" readonly class="js-timepicker" value="" placeholder="__:__">`;
                html += `</label></span></p>`;
            });
            let dayInfoBox = document.getElementById('dayInfoBox');
            dayInfoBox.innerHTML = html;

            document.getElementById('selectWorkingDays').onclick = function(){
                dayInfoBox.querySelectorAll('[type="checkbox"]').forEach(function(cb, i){
                    if (i < 5) cb.checked = true;
                });
            };
            document.getElementById('selectWeekends').onclick = function(){
                dayInfoBox.querySelectorAll('[type="checkbox"]').forEach(function(cb, i){
                    if (i > 4) cb.checked = true;
                });
            };
        })();
    </script>

</div>
