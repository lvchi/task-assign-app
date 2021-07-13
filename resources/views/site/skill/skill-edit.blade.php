@extends('layouts.create')

@section('form')
    <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
        <legend class="w-auto">Kỹ năng</legend>

    @include('components.flash-message')

    <form action="{{route('skill.update',['id'=>$skill->id])}}" method="POST">
        @csrf
        <div class="form-group-row mb-3">
            @include('components.input-text', [
                'name' => 'skill_code',
                'label' => 'Mã',
                'value' => $skill->code,
                'inputClass' => 'form-control d-inline w-75'
            ])
        </div>
        <div class="form-group-row mb-5">
            @include('components.input-text', [
                'name' => 'skill_name',
                'label' => 'Tên',
                'value' => $skill->name,
                'inputClass' => 'form-control d-inline w-75'
            ])
        </div>
        @include('components.button-group', [
            'buttons' => [
                ['iconClass' => 'fas fa-save', 'value' => 'Lưu' ],
            ]
        ])

        @include('components.span-modal', [
           'value' => 'Xóa'
       ])
    </form>

    @include('components.modal', [
        'href' => route('skill.destroy',['id'=>$skill->id])
    ])
    </fieldset>
@endsection

@section('table')
    @include('components.table', [
        'fields' => [
            'code' => 'code',
            'name_skill' => 'name',
            'edit' => 'pattern.modified'
           ],
        'items' => $skills,
        'edit_route' => 'skill.edit'
    ])

    {{$skills->links()}}

@endsection,

