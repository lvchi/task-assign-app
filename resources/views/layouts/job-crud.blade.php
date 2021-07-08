@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.css') }}">
    <div class="container">
        @if (session('success'))
            @include('components.flash-message', [
                'modalId' => 'successModal',
                'alertClass' => 'alert alert-sucess',
                'message' => session('success')
            ])
        @elseif ($errors->has('job_id')) 
            @include('components.flash-message', [
                'modalId' => 'errorModal',
                'alertClass' => 'alert alert-danger',
                'message' => $errors->first('job_id')
            ])
        @endif
        <div class="row">

            
            <div class="col-md-9">
                <form action="{{route($routeName, $params ?? [])}}" method="{{$method}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="job_id" id="job_id" value="{{ old('job_id') }}">
                    <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
                        <legend class="w-auto">Thông tin nghiệp vụ</legend>
                        <div class="form-group-row mb-3">
                            @include('components.searchable-input-text', [
                                'name' => 'assigner_name',
                                'label' => 'Người giao việc', 
                                'options' => $staff
                            ])
                            <i class="fas fa-asterisk" style="width: .5em; color:red"></i>
                            <input type="hidden" name="assigner_id" id="assigner_id" value="{{ old('assigner_id') }}">
                            @error('assigner_id')
                                <span class="alert alert-danger ml-3 p-1 errors">{{$errors->first('assigner_id')}}</span>
                            @enderror
                        </div>

                        <div class="form-group-row mb-3">
                            @include('components.searchable-input-text', [
                                'name' => 'project_code',
                                'label' => 'Mã dự án', 
                                'options' => $projects, 
                                'displayField' => 'code',
                                'hiddenField' => 'name'
                            ])

                            @include('components.input-text', [
                                'name' => 'project_name',
                                'label' => '(Tên dự án)',
                                'readonly' => true,
                            ])
                            <input type="hidden" name="project_id" id="project_id" value="{{ old('project_id') }}">
                        </div>

                         

        
                        <div class="form-group-row mb-3">
                            @include('components.searchable-input-text', [
                                'name' => 'job_type',
                                'label' => 'Loại công việc', 
                                'options' => $jobTypes, 
                            ])
                            <input type="hidden" name="job_type_id" id="job_type_id" value="{{ old('job_type_id') }}">
                            
                        </div>
                        <div class="form-group-row mb-3">
                            @include('components.input-number', [
                                'name' => 'period',
                                'label' => 'Kỳ',
                            ])
                            @include('components.select', [
                                'name' => 'period_unit', 
                                'label' => 'Đơn vị',
                                'options' => [
                                    ['value' => 'day', 'display' => 'Ngày'],
                                    ['value' => 'week', 'display' => 'Tuần'],
                                    ['value' => 'term', 'display' => 'Kỳ'],    
                                ]
                            ])
                        </div>
                        <div class="form-group-row mb-3">
                            @include('components.searchable-input-text', [
                                'name' => 'parent_job',
                                'label' => 'Việc cha', 
                                'options' => $jobs, 
                            ])
                            <input type="hidden" name="parent_id" id="parent_id" value="{{ old('parent_id') }}">
                        </div>
                        <div class="form-group-row mb-3">
                            @include('components.input-text', [
                                'name' => 'code',
                                'label' => 'Mã CV'
                            ])
                            @include('components.searchable-input-text', [
                                'name' => 'priority_name',
                                'label' => 'Độ ưu tiên', 
                                'options' => $priorities, 
                            ])
                            <input type="hidden" name="priority_id" id="priority_id" value="{{ old('priority_id') }}">
    
                        </div>
                        <div class="form-group-row mb-3">
                            @include('components.input-text', [
                                'name' => 'name', 
                                'label' => 'Tên công việc',
                            ])
                            <i class="fas fa-asterisk" style="width: .5em; color:red"></i>
                            @error('name')
                               <span class="alert alert-danger ml-3 p-1 errors">{{$errors->first('name')}}</span>
                            @enderror
                        </div>

                        <div class="form-group-row mb-3">
                            @include('components.input-number', [
                                'name' => 'lsx_amount', 
                                'label' => 'Khối lượng LSX',
                            ])
                            <label>(Man day)</label>
                        </div>
                        <div class="form-group-row mb-3">
                            @include('components.input-number', [
                                'name' => 'assign_amount', 
                                'label' => 'Khối lượng giao'
                            ])
                            <label>(Man day)</label>
                        </div>
                        <div class="form-group-row mb-3">
                            @include('components.input-date', [
                                'type' => 'date',
                                'name' => 'deadline', 
                                'label' => 'Hạn xử lý',
                            ])
                            <i class="fas fa-asterisk" style="width: .5em; color:red"></i>
                            @error('deadline')
                                <span class="alert alert-danger ml-3 p-1 errors">{{$errors->first('deadline')}}</span>
                            @enderror
                        </div>

                        <div class="form-group-row mb-3">
                            @include('components.text-area', [
                                'name' => 'description',
                                'label' => 'Mô tả CV',
                            ])
                           
                        </div>
                        <div class="form-group-row mb-3">
                            @include('components.input-file', [
                                'name' => 'job_files[]',
                                'label' => 'Tệp nội dung',
                            ])
                        </div>
                    </fieldset>
					
                    <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
                        <legend class="w-auto">Đối tượng xử lý</legend>


                        <div class="form-group-row mb-3 offset-10">
                            <button class="btn btn-info">Rút gọn</button>
                        </div>

                        <div id="short-list">
                            <div class="form-group-row mb-3">
                                @include('components.searchable-input-text', [
                                    'name' => 'chu-tri',
                                    'label' => 'Chủ trì', 
                                    'options' => $staff, 
                                ])
                                <input type="hidden" name="chu-tri-id" id="chu-tri-id">
                            </div>
                            <div class="form-group-row mb-3">
                                @include('components.multiple-search-input', [
                                    'name' => 'phoi-hop[]', 
                                    'label' => 'Phối hợp', 
                                    'options' => $staff
                                ])
                            </div>
                            <div class="form-group-row mb-3">
                                @include('components.searchable-input-text', [
                                    'name' => 'nhan-xet',
                                    'label' => 'Theo dõi, nhận xét', 
                                    'options' => $staff, 
                                ])
                                <input type="hidden" name="nhan-xet-id" id="nhan-xet-id">
                            </div>
                        </div>

                        <div id="detail" class="d-none">
                            
                        </div>
                        
                        @yield('assign-button-group')
                    </fieldset>
                    
                    
					<div class="form-group-row mb-3 p-3">
						@include('components.input-text', [
							'name' => 'status', 
							'label' => 'Trạng thái',
                            'readonly' => true,
                            'value' => 'Chưa nhận'
						])
					</div>

                    <div class="form-group-row mb-3 p-3" id="note-wrapper" style="display:  none">
                        @include('components.text-area', [
                            'name' => 'note',
                            'label' => 'Ghi chú sửa đổi',
                        ])
					</div>
                    
                    @yield('button-group')
                </form>
            </div>
            <div class="col">
                @include('components.dynamic-table', [
                    'cols' => [
                        'Tên công việc' => 'name',
                    ],
                    'rows' => $jobs,
                    'min_row' => 5,
                    
                ])
                <div id="history-workplan" style="display: none">
                    <a href="" class="btn btn-link p-0 mb-1 text-decoration-none">Lịch sử công việc</a>
                    <a href="" class="btn btn-link p-0 text-decoration-none">Kế hoạch thực hiện</a>
                </div>
            </div>

        </div>
        
                
    </div>

    <script type="text/javascript">
        const handleOptionChange = (name, hiddenInputId) => {
            $(`#${name}`).change(function (e) {
                $(`#${hiddenInputId}`).val(e.target.value)
            });
        }

        const getJob = (id, options) => {
            return fetch(`/api/jobs/${id}`, {
                method: "GET",
                ...options
            }).then(response => response.json());
        } 

        const setValue = (selector, value) => {
            $(selector).val(value)
        }

        const setSelectedValue = (selector, value) => {
            $(selector).selectpicker('val', value);
            $(selector).selectpicker('refresh');

        }

        const initializeJobValues = (jobId) => {
            getJob(jobId).then(job => {
                Object.keys(job).forEach(key => {
                    let input = document.querySelector(`#${key}`);
                    if (input !== null) {
                        input.value = job[key];
                    }
                    
                })

                setSelectedValue('#assigner_name', job.assigner_id)
                setSelectedValue('#project_code', job.project_id);
                setValue('#project_name', job.project ? job.project.name : '');

                setSelectedValue('#job_type', job.job_type_id);
                
                setSelectedValue('#parent_job', job.parent_id);
                
                setSelectedValue('#priority_name', job.priority_id);
            });
        }

        selectInputs = [
            {name: 'assigner_name', hiddenInputId: 'assigner_id'},
            {name: 'project_code', hiddenInputId: 'project_id'},
            {name: 'job_type', hiddenInputId: 'job_type_id'},
            {name: 'parent_job', hiddenInputId: 'parent_id'},
            {name: 'priority_name', hiddenInputId: 'priority_id'},
            {name: 'chu-tri', hiddenInputId: 'chu-tri-id'},
            {name: 'nhan-xet', hiddenInputId: 'nhan-xet-id'},
        ];

        selectInputs.forEach(element => {
            handleOptionChange(element.name, element.hiddenInputId);
        });

        $('#project_code').change(function () {
            const projectName = $(this).find(':selected').attr('data-hidden');
            $('#project_name').val(projectName);
        });

        $(document).ready(function () {
            $("#period_unit").prop("selectedIndex", -1);

            // $('#job_id').change(function () {
            //     console.log('change');
            //     if ($(this).val() !== '') {
            //         const jobId = $('#job_id').val();
            //         initializeJobValues(jobId);
            //     }
            // });
            if ($('#job_id').val() !== '') {
                const jobId = $('#job_id').val();
                initializeJobValues(jobId);
            }
        });

        document.querySelectorAll('tr').forEach(function (element) {
            
            if (element.id !== '') {
                const id = element.id;                
                element.addEventListener('click', function (e) {
                    $('.alert').each(function () {
                        $(this).alert('close')
                    });
                    $('#job_id').val(id);
                    $('#history-workplan').css('display', 'block');
                    initializeJobValues(id);

                });
            }
            
        });

        const setCloseTimeout = (modalSelector, timeout) => {
            $(modalSelector).modal("show").on("shown.bs.modal", function () {
                window.setTimeout(function () {
                    $(modalSelector).modal("hide");
                }, timeout);
            });
        }

        setCloseTimeout("#successModal", 5000);
        setCloseTimeout("#errorModal", 5000);

        
    </script>

    
@endsection


