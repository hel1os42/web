<p>
    <input style="line-height: 14px; font-size: 14px;" type="radio"
           onclick="checkUserRole(true)"
           name="role_ids[]"
           value="{{$roles::findByName('advertiser')->getId()}}" checked> Advertiser + user
</p>
<input hidden style="line-height: 14px; font-size: 14px;" type="checkbox"
       id="role_ids_user"
       name="role_ids[]"
       value="{{$roles::findByName('user')->getId()}}" checked>

<p><input style="line-height: 14px; font-size: 14px;" type="radio"
          onclick="checkUserRole(false)"
          name="role_ids[]"
          value="{{$roles::findByName('chief_advertiser')->getId()}}"> Chief advertiser</p>


<input hidden style="line-height: 14px; font-size: 14px;" type="radio"
       onclick="checkUserRole(false)"
       name="parent_ids[]"
       value="{{auth()->user()->getId()}}" checked>

<script type="application/javascript">
    function checkUserRole( userFlag ) {
        document.getElementById( 'role_ids_user' ).checked = userFlag;
    }
</script>