@extends('layouts.create')

@section('form')
    <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
        <legend class="w-auto">Dự án</legend>
    @include('components.flash-message')

    <form action="{{route('project.update',['id'=>$project->id])}}" method="POST">
        @csrf
        <div class="form-group-row mb-3">
            @include('components.input-text', [
                'name' => 'project_code',
                'label' => 'Mã',
                'value' => $project->code,
                'inputClass' => 'form-control d-inline w-75'
            ])
        </div>
        <div class="form-group-row mb-5">
            @include('components.input-text', [
                'name' => 'project_name',
                'label' => 'Tên',
                'value' => $project->name,
                'inputClass' => 'form-control d-inline w-75'
            ])
        </div>
        @include('components.button-group', [
            'buttons' => [
                ['iconClass' => 'fas fa-save', 'value' => 'Lưu', 'name' => 'update' ],
            ]
        ])

        @include('components.span-modal', [
           'value' => 'Xóa'
       ])
    </form>

    @include('components.modal', [
        'href' => route('project.destroy',['id'=>$project->id])
    ])
    </fieldset>
@endsection

@section('table')
    @include('components.table', [
        'fields' => [
            'code' => 'code',
            'name_project' => 'name',
            'edit' => 'pattern.modified'
           ],
        'items' => $projects,
        'edit_route' => 'project.edit'
    ])

    {{$projects->links()}}

@endsection