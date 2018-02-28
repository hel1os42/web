{{ csrf_field() }}

<p class="title">Name and Description</p>

<div class="control-box">
    <p class="control-text">
        <label>
            <span class="input-label">Offer name*</span>
            <input name="label" value="" class="formData" data-max-length="40">
        </label>
    </p>
    <p class="hint">Please, enter the Offer name (3..128 characters).</p>
</div>

<div class="control-box clearfix">
    <p class="control-text">
        <label>
            <span class="input-label">Offer description</span>
            <textarea name="description" class="formData" data-max-length="200"></textarea>
        </label>
    </p>
    <p class="pull-right text-right more-buttons">
        <span class="btn btn-xs able" id="btn_add_more">Add link</span>
        <span class="btn btn-xs" id="btn_remove_more">Remove link</span>
        <span class="btn btn-xs" id="btn_edit_more">Edit more</span>
    </p>
    <p class="hint">Please, enter the Offer description.</p>
</div>
