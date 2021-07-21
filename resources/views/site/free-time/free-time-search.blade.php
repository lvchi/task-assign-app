@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
            <legend class="w-auto">Tìm kiếm kế hoạch/thời gian rảnh</legend>
            @include('components.flash-message')
            <div class="row">
                <div class="col-md-8">
                    <div class="row mb-4 ml-0">
                        <form action="{{route('free-time.search')}}" class="w-100" method="POST">
                            @csrf
                            <div class="form-group-row mb-3">
                                @include('components.select', [
                                    'name' => 'department',
                                    'label' => 'Loại',
                                    'type' => 'date',
                                    'options' => [
                                        ['id' => 0, 'name' => 'Kế hoạch thực hiện'],
                                        ['id' => 1, 'name' => 'Thời gian rảnh'],
                                    ]
                                ])
                                @include('components.input-number', [
                                    'name' => 'to_date',
                                    'label' => 'Rảnh từ (Manday)',
                                    'type' => 'date',
                                ])
                            </div>
                            <div class="form-group-row mb-3">
                                @include('components.input-date', [
                                    'name' => 'from_date',
                                    'label' => 'Từ ngày',
                                    'type' => 'date',
                                    'value' => app('request')->input('from_date')
                                ])
                                @include('components.input-date', [
                                    'name' => 'to_date',
                                    'label' => 'Đến ngày',
                                    'type' => 'date',
                                    'value' => app('request')->input('to_date')
                                ])
                            </div>
                            <div class="form-group-row mb-3">
                                @include('components.select', [
                                    'name' => 'department',
                                    'label' => 'Đối tượng',
                                    'type' => 'date',
                                    'options' => $staffs
                                ])

                            </div>
                            <div class="form-group-row mb-3">
                                @include('components.button-group', [
                                     'buttons' => [
                                         ['iconClass' => 'fas fa-search', 'value' => 'Tìm kiếm', 'action' => 'search']
                                     ]
                                 ])
                                <a href="{{route('free-time.list')}}"> <i class="fas fa-sync-alt"></i> Reset</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">
                    @include('components.table', [
                        'fields' => [
                            'condition' => 'condition',
                            'skill' => 'skill',
                            'name_skill' => 'name_skill',
                           ],
                        'edit_route' => 'skill.edit'
                    ])
                </div>
            </div>


        </fieldset>
                @include('components.table', [
                    'fields' => [
                        'object' => 'object',
                        'department' => 'department',
                        'manday_lsx' => 'manday_lsx',
                        'total_hour' => 'total_hour',
                       ],
                    'items' => $jobs,
                    'edit_route' => 'skill.edit'
                ])
                <div class="text-center mt-5">
                     <a href="#" class="btn btn-success">Xem chi tiết kế hoạch thời gian</a>
                </div>
    </div>

@endsection