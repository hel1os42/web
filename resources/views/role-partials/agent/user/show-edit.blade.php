<div>
    @if($editableUserModel->isAdvertiser())
        <input class="btn-nau pull-left" name="button_to_chief" value="evolve to chief" type="button" onclick="buttonToChief({{$editableUserModel->countHasOffers()}})">
        <input hidden type="checkbox" id="role_chief_advertiser" name="role_ids[]" value="{{\App\Models\Role::findByName(\App\Models\Role::ROLE_CHIEF_ADVERTISER)->getId()}}">
    @elseif($editableUserModel->isChiefAdvertiser())
        <input class="btn-nau pull-left" name="button_to_advert" value="degrade to advert" type="button" onclick="buttonToAdvert({{count($children)}})">
        <input hidden type="checkbox" id="role_user" name="role_ids[]" value="{{\App\Models\Role::findByName(\App\Models\Role::ROLE_USER)->getId()}}">
        <input hidden type="checkbox" id="role_advertiser" name="role_ids[]" value="{{\App\Models\Role::findByName(\App\Models\Role::ROLE_ADVERTISER)->getId()}}">
    @endif
</div>

@push('scripts')
    <script type="application/javascript">
        function buttonToChief(countOfOffers) {
            if(0 === countOfOffers) {
                document.getElementById('role_chief_advertiser').checked = true;
                document.getElementById('form_user_update').submit();
                return false;
            }
            else{
                alert('You can not convert the Advertiser into Chief Advertiser. You must destroy Offers from this Advertiser firstly');
            }
        }

        function buttonToAdvert(countOfChildren) {
            if(0 === countOfChildren){
                document.getElementById('role_user').checked = true;
                document.getElementById('role_advertiser').checked = true;
                document.getElementById('form_user_update').submit();
                return false;
            } else {
                alert('You can not convert the Chief Advertiser into Advertiser. You must detach Advertisers from this Chief firstly');
            }
        }
    </script>
@endpush