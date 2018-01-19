@php
    $data = isset($data) ? $data : [];
    $currentUserModel = auth()->user();
@endphp
@if($currentUserModel->isAdmin())
    @includeIf('role-partials.admin.' . $partialRoute, $data)
@elseif($currentUserModel->isAgent())
    @includeIf('role-partials.agent.' . $partialRoute, $data)
@elseif($currentUserModel->isChiefAdvertiser())
    @includeIf('role-partials.chief.' . $partialRoute, $data)
@elseif($currentUserModel->isAdvertiser())
    @includeIf('role-partials.advertiser.' . $partialRoute, $data)
@endif