@extends('layouts.master')

@section('title', 'NAU show Place')

@section('content')
<div class="col-md-10 col-md-offset-1">
    <div class="card">
        <div class="content">
                <div class="img-container text-center">
                    <img src="{{route('place.picture.show', [$id, 'cover'])}}"><br>
                </div>
            <h1>{{$name}}</h1>
            <h4>{{$description}}</h4>
            <h4>{{$address}}</h4>
            <p>{{$about}}</p>
            <a href="{{route('profile.place.offers')}}"></a>

            <form method="POST" action="{{route('place.picture.store')}}" enctype="multipart/form-data">
                <label>Set logo:</label>
                <div class="form-group">
                    {{ csrf_field() }}
                    <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                        <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                            <div class="fileinput-new thumbnail">
                                <img src="{{asset('img/image_placeholder.jpg')}}" alt="...">
                            </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style=""></div>
                        <div class="btn btn-default btn-fill btn-file">
                            <span class="fileinput-new">Pick logo</span>
                            <span class="fileinput-exists">Change logo</span>
                            <input type="hidden">
                            <input type="file" name="picture">
                        </div>
                    </div>
                <input class="btn btn-rose btn-wd btn-md" type="submit">
                </div>
            </form>
            <form method="POST" action="{{route('place.cover.store')}}" enctype="multipart/form-data">
                <label>Set cover:</label>
                <div class="form-group">
                    {{ csrf_field() }}
                    <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                        <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                            <div class="fileinput-new thumbnail">
                                <img src="{{asset('img/image_placeholder.jpg')}}" alt="...">
                            </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style=""></div>
                        <div class="btn btn-default btn-fill btn-file">
                            <span class="fileinput-new">Pick cover</span>
                            <span class="fileinput-exists">Change cover</span>
                            <input type="hidden">
                            <input type="file" name="picture">
                        </div>
                    </div>
                <input class="btn btn-rose btn-wd btn-md" type="submit">
                </div>
            </form>
        </div>
    </div>
</div>
@stop
