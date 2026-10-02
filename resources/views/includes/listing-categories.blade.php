<div class="py-1 mb-4">
	<div class="text-center">
		@foreach (Categories::where('mode','on')->orderBy('name')->get() as $category)
		<a class="text-muted btn btn-sm bg-white border mb-2 e-none btn-category @if(Request::path() == "category/$category->slug" || Request::path() == "category/$category->slug/featured" || Request::path() == "category/$category->slug/more-active" || Request::path() == "category/$category->slug/new" || Request::path() == "category/$category->slug/free")active-category @endif" href="{{url('category', $category->slug)}}">
		{{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}
		</a>
	@endforeach
</div>
</div>

