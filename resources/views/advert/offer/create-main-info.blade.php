{{ csrf_field() }}

<p class="title">Name and Description</p>

<div class="control-box">
    <p class="control-text">
        <label>
            <span class="input-label">Offer name*</span>
            <input name="label" value="" class="formData">
        </label>
    </p>
    <p class="hint">Please, enter the Offer name (3..128 characters).</p>
</div>

<div class="control-box">
    <p class="control-text">
        <label>
            <span class="input-label">Offer description</span>
            <textarea name="description" class="nullableFormData"></textarea>
        </label>
    </p>
    <p class="hint">Please, enter the Offer description.</p>
</div>
