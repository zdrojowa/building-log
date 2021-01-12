@extends('DashboardModule::dashboard.index')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Lista wszystkich zakładek</h4>
                        <table class="table table-striped"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascripts')
    @parent

    <script>

        $('.table').zdrojowaTable({

            ajax: {
                url: "{{route('BuildingLogModule::ajax')}}",
                method: "POST",
                data: {
                    "_token": "{{csrf_token()}}"
                },
            },
            headers: [
                {
                    name: 'L.p',
                    type: 'index',
                },
                {
                    name: 'Miesiąc',
                    type: 'text',
                    ajax: 'month',
                    orderable: true,
                },
                {
                    name: 'Rok',
                    type: 'text',
                    ajax: 'year',
                    orderable: true
                },
                {
                    name: 'Data utworzenia',
                    orderable: true,
                    ajax: 'created_at'
                },
                {
                    name: 'Akcje',
                    ajax: 'key',
                    type: 'actions',
                    buttons: [
                    @permission('BuildingLog.edit')
                        {
                            color: 'primary',
                            icon: 'mdi mdi-pencil',
                            class: 'remove',
                            url: "{{route('BuildingLogModule::edit', ['buildingLog' => '%%id%%'])}}"
                        },
                    @endpermission
                    @permission('BuildingLog.delete')
                        {
                            color: 'danger',
                            icon: 'mdi mdi-delete',
                            class: 'ZdrojowaTable--remove-action',
                            url: "{{route('BuildingLogModule::destroy', ['buildingLog' => '%%id%%'])}}"
                        },
                    @endpermission
                    ]
                }
            ]
        });
    </script>
@endsection
