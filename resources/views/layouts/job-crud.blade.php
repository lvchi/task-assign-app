@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/file-input.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">

    @include('components.file-modal')

    @include('components.assignee-detail-modal')

    @yield('modal')


    <div class="container">

        @yield('message')


        <div class="row">
        

        <div class="row">

            
            <div class="col-md-9">

                @yield('form')

                <form id="job-form" action="{{route($routeName, $params ?? [])}}" method="{{$method}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="job_id" id="job_id" value="{{ $job_id ?? old('job_id') }}">
                    
                    <input type="hidden" name="editable" id="editable" value="{{ $editable ? 1 : 0 }}">

                    <input type="hidden" name= "process_method" id="process_method">
                    
                    <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
                        <legend class="w-auto">Thông tin nghiệp vụ</legend>
                        
                        @yield('job-info')
                        
                        
                    </fieldset>
					
                    <fieldset class="p-3 mb-3" style="border: 1px solid; border-radius: 15px">
                        <legend class="w-auto">Đối tượng xử lý</legend>


                        <div class="form-group-row mb-3 offset-10">
                            <button type="button" id="view-mode-btn" class="btn btn-info">Rút gọn</button>
                        </div>
                    
                        <div id="short-list">
                            <div class="form-group-row mb-3">
                                
                                @include('components.input-text', [
                                    'name' => 'chu-tri-display',
                                    'label' => 'Chủ trì', 
                                    'readonly' => true,
                                    'inputClass' => 'form-control d-inline w-75',
                                    'textClass' => 'col-sm-4 d-inline p-0'

                                ])

                                @if ($editable)
                                    <button type="button" id="chu-tri-btn" data-toggle="modal" data-target="#assignee-modal" class="btn btn-light">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                @endif

                    
                            </div>
                            <div class="form-group-row mb-3">
                                @include('components.input-text', [
                                    'name' => 'phoi-hop-display',
                                    'label' => 'Phối hợp', 
                                    'readonly' => true,
                                    'inputClass' => 'form-control d-inline w-75',
                                    'textClass' => 'col-sm-4 d-inline p-0'


                                ])
                    
                                
                                @if ($editable)
                                    <button type="button" id="phoi-hop-btn" data-toggle="modal" data-target="#assignee-modal" class="btn btn-light">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                @endif

                            </div>
                            <div class="form-group-row mb-3">
                                @include('components.input-text', [
                                    'name' => 'nhan-xet-display',
                                    'label' => 'Theo dõi/Nhận xét', 
                                    'readonly' => true,
                                    'inputClass' => 'form-control d-inline w-75',
                                    'textClass' => 'col-sm-4 d-inline p-0'

                                ])

                                
                                @if ($editable)
                                    <button type="button" id="nhan-xet-btn" data-toggle="modal" data-target="#assignee-modal" class="btn btn-light">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                @endif

                            </div>
                        </div>
                    
                        <div id="full-list" style="display: none">
                            @include('components.dynamic-table', [
                                'cols' => [
                                    'Hình thức xử lý' => '',
                                    'Mã ĐT' => '',
                                    'Đối tượng xử lý' => '',
                                    'Báo cáo trực tiếp' => '',
                                    'Hạn xử lý' => '',
                                    'SMS' => '',
                                ],
                                'id' => 'full-assignee-table',
                                'rows' => [],
                            ])
                        </div>

                        @yield('assign-button-group')

                    </fieldset>
                    
                    
					<div class="form-group-row mb-3 p-3">
						@include('components.input-text', [
							'name' => 'status', 
							'label' => 'Trạng thái',
                            'readonly' => true,
                            'value' => __('jobStatus.pending')
						])
					</div>

                    <div class="form-group-row mb-3 p-3" id="note-wrapper" style="display:  none">
                        @include('components.text-area', [
                            'name' => 'note',
                            'label' => 'Ghi chú sửa đổi',
                        ])
					</div>

                    @yield('deny-reason-modal')
                    
                    @yield('button-group')
                </form>
            </div>
            <div class="col-md-3">
                @include('components.dynamic-table', [
                    'cols' => [
                        'Tên công việc' => 'name',
                    ],
                    'id' => 'jobs-table',
                    'rows' => $jobs ?? [],
                    'min_row' => 5,
                    'pagination' => true
                ])
                <div id="history-workplan" style="display: none">
                    <a href=""  class="btn btn-link p-0 mb-1 text-decoration-none" data-toggle="modal" data-target="#update-job-histories">Lịch sử công việc</a>
                    
                    <a href="{{ route('workplans.create', ['jobId' => $job_id ?? '0']) }}" id="workplan" class="btn btn-link p-0 text-decoration-none">Kế hoạch thực hiện</a>
                    

                    
                    <!-- Update job histories modal -->
                    <div class="modal fade" id="update-job-histories" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Lịch sử sửa đổi công việc</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @include('components.dynamic-table', [
                                    'id' => 'update-histories-table',
                                    'cols' => [
                                        'Ngày sửa đổi' => 'created_at',
                                        'Tên trường' => 'field', 
                                        'Giá trị cũ' => 'old_value', 
                                        'Giá trị thay đổi' => 'new_value',
                                        'Ghi chú sửa đổi' => 'note',
                                    ],
                                    'rows' => [],
                              
                                ])
                            </div>

                        </div>
                        </div>
                    </div>





                </div>
            </div>

        </div>
        
                
    </div>
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/job-crud/jobAPI.js') }}"></script>
    <script src="{{ asset('js/job-crud/jobFormInput.js') }}"></script>
    <script src="{{ asset('js/job-crud/jobTable.js') }}"></script>
    <script src="{{ asset('js/job-crud/assigneeModal.js') }}"></script>

    
    <script type="text/javascript">



        $(document).ready(function () {
            
            if ($('#job_id').val() !== '') {
                const jobId = $('#job_id').val();
                
                let url = $('#workplan').attr('href').split('/').slice(0, -1).join('/');
                $('#workplan').prop('href', `${url}/${jobId}`);
                
                
                const readOnly = $('#editable').val() === '0';

                initializeJobValues(jobId, readOnly);
            }
            else {
                $('button[value="assignee-detail"]').hide();
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

            $("#period_unit").prop("selectedIndex", -1);

            $('#project_code').change(function () {
                const projectName = $(this).find(':selected').attr('data-hidden');
                $('#project_name').val(projectName);
            });
            

            $('#update-job-histories').on('show.bs.modal', function () {
                generateUpdateHistoriesTable();
            });

            $('#update-job-histories').on('hidden.bs.modal', function () {
               resetUpdateHistoriesTable();
            });

            

            document.querySelectorAll('#jobs-table tr').forEach(function (element) {
                if (element.id !== '') {
                    const id = element.id;       
                    const readOnly = $('#editable').val() === '0';

                    element.addEventListener('click', function () {
                        handleRowClick(id, readOnly);
                    });
                }
            });

            $('#view-mode-btn').click(function() {
				const text = $(this).html();
				if (text === 'Rút gọn') {
					
					$(this).html('Đầy đủ');
				
					$('#full-list').show();
					$('#short-list').hide();
				
				}
				else {
				
					$(this).html('Rút gọn');
				
					$('#short-list').show();
					$('#full-list').hide();
				}

			});

        });


        

        $('button[value="assignee-detail"]').click(function() {

            const jobId = $('#job_id').val();

            getAssigneeList(jobId).then(assigneeList => {

                initializeAssigneeDetailTable('assignee-detail-table', assigneeList);
                $('#assignee-detail-modal').modal('show');

            });

        });


        setCloseTimeout("#successModal", 5000);
        setCloseTimeout("#errorModal", 5000);

        
    </script>

    @yield('custom-script')

    
@endsection


