@extends('advert.layout')

@section('title', 'Company info')

@section('content')
<div class="col-md-10 col-md-offset-1">
    <div class="card">
        <div class="content">
            <table id="table_your_offers" class="display">
                <thead>
                    <tr>
                        <th width="200"></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Name</td>
                        <td class="details-control">
                            <div>
                                {{$name}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td class="details-control">
                            <div>
                                {{$description}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>About</td>
                        <td class="details-control">
                            <div>
                                {{$about}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td class="details-control">
                            <div>
                                {{$address}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Picture</td>
                        <td class="details-control">
                            <div class="img-container text-center">
                                <img src="{{route('places.picture.show', [$id, 'picture'])}}" onerror="imgError(this);"><br>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Cover</td>
                        <td class="details-control">
                            <div class="img-container text-center">
                                <img src="{{route('places.picture.show', [$id, 'cover'])}}" onerror="imgError(this);"><br>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <a href="{{route('profile.place.offers')}}"></a>

            @include('partials/place-picture-filepicker')
            @include('partials/place-cover-filepicker')
        </div>
    </div>
</div>
@stop

@push('scripts')
    <script>
        function imgError(image) {
            image.onerror = "";
            image.src = "/img/imagenotfound.svg";
            return true;
        }
    </script>
@endpush
