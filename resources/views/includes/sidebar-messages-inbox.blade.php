<div class="w-100 p-3 border-bottom">
	<div class="w-100">
		<a href="{{url()->previous()}}" class="mr-1 text-decoration-none">
			<i class="fa fa-arrow-left"></i>
		</a>

		<span style=" font-weight: 500!important; color: black; font-size:17px; " class="h5 align-top font-weight-bold">{{trans('general.messages')}}</span>

		<span class="float-right">
			<a href="#" class="text-decoration-none" data-toggle="modal" data-target="#newMessageForm" title="{{trans('general.new_message')}}">
				<i class="feather icon-edit"></i>
			</a>
		</span>
	</div>
</div>

@include('includes.messages-inbox')
