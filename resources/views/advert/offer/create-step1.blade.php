<div id="tab_step1" class="tab-pane fade in active">

    <p class="title">Name and Description</p>

    <div class="control-box">
        <p class="control-text">
            <label>
                <span class="input-label">Offer name</span>
                <input name="label" value="">
            </label>
        </p>
        <p class="hint">Please, enter the Offer name.</p>
    </div>

    <div class="control-box">
        <p class="control-text">
            <label>
                <span class="input-label">Offer description</span>
                <textarea name="description"></textarea>
            </label>
        </p>
        <p class="hint">Please, enter the Offer description.</p>
    </div>

    <div class="control-box">
        <p>
            <span class="input-label"><strong>Offer picture</strong></span>
            <label class="control-file">
                <span class="text-add">Add picture</span>
                <input name="____offer_picture" type="file" class="js-imgupload" id="offerImg">
                <img src="" alt="">
                <span class="text-hover">Drag it here</span>
            </label>
        </p>
    </div>

    <p class="title">Category &amp; Type</p>

    <div class="control-box">
        <p class="control-select valid-not-empty">
            <label>
                <span class="input-label">Offer category</span>
                <select id="offer_category" name="category_id"></select>
            </label>
        </p>
        <p class="hint">Please, select the category.</p>
    </div>

    <div class="control-box offer-type-box">
        <p><strong>Offer type</strong></p>
        <p class="control-radio-left">
            <input name="____r2" type="radio" id="radio_ot1" checked value="Discount">
            <label for="radio_ot1">
                <span class="input-label">Discount</span>
                <!--<small>Description for this item</small>-->
            </label>
        </p>
        <p class="control-range sub-radio">
            <span class="input-label">Set discount (%)</span>
            <em role="button" class="more">+</em>
            <label>
                <input name="reward" data-min="0" data-max="99" data-default="10" value="10" class="js-numeric">
            </label>
            <em role="button" class="less">-</em>
        </p>
        <p class="control-radio-left">
            <input name="____r2" type="radio" id="radio_ot2" value="Second Free">
            <label for="radio_ot2">
                <span class="input-label">Second Free</span>
                <!--<small>Description for this item</small>-->
            </label>
        </p>
        <p class="control-radio-left">
            <input name="____r2" type="radio" id="radio_ot3" value="Bonus or Gift">
            <label for="radio_ot3">
                <span class="input-label">Bonus or Gift</span>
                <!--<small>Description for this item</small>-->
            </label>
        </p>
        <p class="control-text sub-radio">
            <label>
                <span class="input-label">Bonus or Gift information</span>
                <input name="____bonus_description" id="bonus_description" value="">
            </label>
        </p>
        <p class="hint">Please, enter the Bonus or Gift information.</p>
    </div>

    <p class="step-footer">
        <a href="#tab_step2" data-toggle="tab" class="tab-nav btn-nau pull-right">next step &gt;</a>
    </p>

</div>