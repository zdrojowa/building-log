@extends('DashboardModule::dashboard.index')

@section('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.css"
          integrity="sha256-AgL8yEmNfLtCpH+gYp9xqJwiDITGqcwAbI8tCfnY2lw=" crossorigin="anonymous"/>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @include('DashboardModule::partials.alerts')

                        <h4 class="card-title">Edytowanie zakładki</h4>

                        <div id="upload" class="upload">
                            <p>Upuść pliki tutaj lub</p>
                            <button>kliknij</button>
                        </div>

                        <div id="upload-container">
                            @foreach($buildingLog->photos->sortBy('_sequence') as $photo)
                                <div id="buildingLogPhoto__{{$photo->id}}" class="buildingLog-photo" data-photo-id="{{$photo->id}}">
                                    <div class="buildingLogPhoto__delete">
                                        <i class="mdi mdi-close"></i>
                                    </div>
                                    <img src="{{Storage::disk('public')->url($photo->file)}}"/>
                                </div>
                            @endforeach
                        </div>

                        <div id="preview-template">
                            <div class="buildingLog-photo">
                                <div class="buildingLogPhoto__delete">
                                    <i class="mdi mdi-close"></i>
                                </div>
                                <img data-dz-thumbnail/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection

        @section('javascripts')
            @parent
            <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.js"
                    integrity="sha256-OG/103wXh6XINV06JTPspzNgKNa/jnP1LjPP5Y3XQDY=" crossorigin="anonymous"></script>
            <script>
                $(".select2").select2();
            </script>


            <script>
                const dropzone = new Dropzone("div#upload", {
                    url: "{{route('BuildingLogModule::edit.storeImage', ['buildingLog' => $buildingLog->id])}}",
                    method: "POST",
                    maxFilesize: 1,
                    acceptedFiles: "image/jpeg, image/jpg, image/png, image/gif",
                    dictFileTooBig: 'image.tooLarge',
                    parallelUploads: 1,
                    dictInvalidFileType: 'image.badExtension',
                    previewsContainer: "#upload-container",
                    previewTemplate: document.getElementById('preview-template').innerHTML,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    init: function() {
                        this.on('error', function(file, error) {
                            this.removeFile(file);
                            if(error === 'image.tooLarge') {
                                Swal.fire("Error", 'Maksymalny rozmiar pliku to 1Mb', "error")
                            }
                            else if(error === 'image.badExtension') {
                                Swal.fire("Error", 'Rozszerzenie pliku jest niedozwolone', "error")
                            }
                        });

                        this.on('success', function (file, response) {
                            $(file.previewElement).attr('id', `buildingLogPhoto_${response._id}`);
                            $(file.previewElement).data('photo-id', `${response._id}`);
                        })
                    }
                })

                $("#upload-container").sortable({
                    update: function(x) {
                        $("#upload-container").sortable('serialize')
                        $.ajax({
                            method: 'POST',
                            url: '{{route('BuildingLogModule::edit.saveSequence', ['buildingLog' => $buildingLog->_id])}}',
                            data: {
                                sequence: $("#upload-container").sortable('serialize'),
                                _token: '{{csrf_token()}}'
                            }
                        })
                    }
                });

                $("body").on('click', '.buildingLogPhoto__delete', function() {
                    const parentObject = $(this).parent();
                    let url = '{{route('BuildingLogModule::destroy.photo', ['buildingLog' => $buildingLog->_id, 'buildingLogPhoto' => '%%id%%'])}}';
                    url = url.split('%%id%%').join($(this).parent().data('photo-id'));

                    Swal.fire({
                        title: 'Na pewno chcesz to zrobić?',
                        text: 'Nie będzie można tego przywrócić!',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d53f3a',
                        confirmButtonText: 'Tak',
                        cancelButtonText: 'Powrót'
                    }).then(result => {
                        if (!result.value) return;

                        $.ajax({
                            url: url,
                            method: "POST",
                            data: {
                                "_method": "DELETE",
                                "_token": $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function () {
                                Swal.fire('Usunięto!', 'Akcja zakończyła się sukcesem', 'success');
                                parentObject.remove();
                            },
                            error: function () {
                                Swal.fire('Wystąpił błąd!', 'Wystąpił błąd po stronie serwera', 'error');
                            }
                        })
                    })
                });
            </script>

            <style>
                .upload {
                    border: 1px dashed #484a4d;
                    border-radius: 5px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    padding: 30px 50px;
                }

                .upload p {
                    font-size: 22px;
                    margin-bottom: 0;
                    line-height: 45px;
                }

                #preview-template {
                    display: none;
                }

                .upload button {
                    border: none;
                    color: #fff;
                    background-color: #484a4d;
                    outline: none;
                    text-transform: uppercase;
                    font-weight: 300;
                    letter-spacing: .8px;
                }

                #upload-container {
                    display: flex;
                    flex-wrap: wrap;
                }

                .buildingLog-photo {
                    margin-top: 10px;
                    border: 1px dashed #484a4d;
                    position: relative;
                    display: inline-block;
                    padding: 10px;
                    border-radius: 20px;
                    cursor: grab;
                    margin-right: 10px;
                    background-color: #191c20;
                }

                .buildingLog-photo img {
                    height: 160px;
                    width: 160px;
                }

                .buildingLog-photo .buildingLogPhoto__delete {
                    position: absolute;
                    right: 10px;
                    top: 10px;
                    z-index: 10;
                    background-color: #2d2e31;
                    padding: 10px;
                    cursor: pointer;
                }

            </style>

@endsection
