@extends('DashboardModule::dashboard.index')

@section('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.css" integrity="sha256-AgL8yEmNfLtCpH+gYp9xqJwiDITGqcwAbI8tCfnY2lw=" crossorigin="anonymous" />
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @include('DashboardModule::partials.alerts')

                        <h4 class="card-title">Dodawanie nowej zakładki w dzienniku</h4>
                        <form method="POST" action="{{route('BuildingLogModule::store')}}"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="">Miesiąc</label>
                                <select class="select2 form-control @error('month') has-danger @enderror" name="month" id="">
                                    <option value="Styczeń" @if($currentMonth === '01') selected @endif>Styczeń</option>
                                    <option value="Luty" @if($currentMonth === '02') selected @endif>Luty</option>
                                    <option value="Marzec" @if($currentMonth === '03') selected @endif>Marzec</option>
                                    <option value="Kwiecień" @if($currentMonth === '04') selected @endif>Kwiecień</option>
                                    <option value="Maj" @if($currentMonth === '05') selected @endif>Maj</option>
                                    <option value="Czerwiec" @if($currentMonth === '06') selected @endif>Czerwiec</option>
                                    <option value="Lipiec" @if($currentMonth === '07') selected @endif>Lipiec</option>
                                    <option value="Sierpień" @if($currentMonth === '08') selected @endif>Sierpień</option>
                                    <option value="Wrzesień" @if($currentMonth === '09') selected @endif>Wrzesień</option>
                                    <option value="Październik" @if($currentMonth === '10') selected @endif>Październik</option>
                                    <option value="Listopad" @if($currentMonth === '11') selected @endif>Listopad</option>
                                    <option value="Grudzień" @if($currentMonth === '12') selected @endif>Grudzień</option>
                                </select>
                                @error('month')
                                <small class="error mt-1 text-danger d-block">{{$message}}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="">Rok</label>
                                <select class="select2 form-control @error('month') has-danger @enderror" name="year" id="">
                                    <option value="{{ $currentYear - 1 }}">{{ $currentYear - 1 }}</option>
                                    <option value="{{ $currentYear }}" selected>{{ $currentYear }}</option>
                                    <option value="{{ $currentYear + 1 }}">{{ $currentYear + 1 }}</option>
                                </select>
                                @error('year')
                                <small class="error mt-1 text-danger d-block">{{$message}}</small>
                                @enderror
                            </div>

                            <button type="submit" class="float-right mt-2 btn btn-primary mr-2">Zapisz</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.js" integrity="sha256-OG/103wXh6XINV06JTPspzNgKNa/jnP1LjPP5Y3XQDY=" crossorigin="anonymous"></script>
    <script>
        $(".select2").select2();
    </script>
@endsection
