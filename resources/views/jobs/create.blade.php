@extends('layouts.job-crud', [
	'routeName' => 'jobs.action',
	'method' => 'POST', 
	'staff' => $staff,
	'jobs' => $jobs,
	'projects' => $projects,
	'jobTypes' => $jobTypes,
	'priorities' => $priorities,
	'processMethods' => $processMethods	
])

@section('assign-button-group')
	{{-- TODO: Thêm 2 link xem chi tiết + bô sung --}}
	
@endsection



@section('button-group')

	@include('components.button-group', [
		'parentClass' => 'btn-group offset-2',
		'buttons' => [
			['iconClass' => 'fas fa-save', 'value' => 'Lưu', 'action' => 'save'], 
			['iconClass' => 'fas fa-copy', 'value' => 'Lưu-sao', 'action' => 'save_copy'], 
			['iconClass' => 'fas fa-edit', 'type' => 'button', 'value' => 'Sửa', 'action' => 'edit'], 
			['iconClass' => 'fas fa-trash', 'value' => 'Xóa', 'action' => 'delete'], 
			['iconClass' => 'fas fa-search', 'value' => 'Tìm kiếm', 'action' => 'search'],
			['iconClass' => 'fas fa-redo', 'type' => 'button', 'value' => 'Tạo mới', 'action' => 'reset'], 
		] 
	])

	<script>
		$(document).ready(function() {

			$('button[value="reset"]').click(function () {
				$('.selectpicker').each(function () {
					$(this).val('');
					$(this).selectpicker('refresh')
				})
				$('input').each(function () {
					if ($(this).attr('name') === 'status') {
						$(this).val('Chưa nhận');
					}
					else {
						$(this).val('');
					}
				})
				$('#period_unit').prop('selectedIndex', -1);
				$('textarea').val('');
				$('#history-workplan').css('display', 'none');
			});

			$('button[value="edit"]').click(function () {
				const jobId = $('#job_id').val();
				if (jobId !== '') {
					$('#note-wrapper').css('display', 'block')
				}
			})
        });

	</script>
	
@endsection
