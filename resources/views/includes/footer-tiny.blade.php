<div class="card border-0 bg-transparent">
  <div class="card-body p-0">
    <ul class="list-inline mb-0 small">
<li class="list-inline-item">
            <a class="link-footer footer-tiny" href="{{ url('/sobre') }}">
              Sobre a plataforma
            </a>
          </li>
		
		<li class="list-inline-item">
            <a class="link-footer footer-tiny" href="{{ url('/funciona') }}">
             Como funciona
            </a>
          </li>
		<li class="list-inline-item">
            <a class="link-footer footer-tiny" href="{{ url('/top') }}">
             Top 10
            </a>
          </li>
      @foreach (Helper::pages() as $page)
        @if ($page->access == 'all')

          <li class="list-inline-item">
            <a class="link-footer footer-tiny" href="{{ url('/p', $page->slug) }}">
              {{ $page->title }}
            </a>
          </li>

        @elseif ($page->access == 'creators' && auth()->check() && auth()->user()->verified_id == 'yes')
          <li class="list-inline-item">
            <a class="link-footer footer-tiny" href="{{ url('/p', $page->slug) }}">
              {{ $page->title }}
            </a>
          </li>

        @elseif ($page->access == 'members' && auth()->check())
          <li class="list-inline-item">
            <a class="link-footer footer-tiny" href="{{ url('/p', $page->slug) }}">
              {{ $page->title }}
            </a>
          </li>
        @endif

      @endforeach

      @if (! $settings->disable_contact)
        <li class="list-inline-item"><a class="link-footer footer-tiny" href="{{ url('contact') }}">{{ trans('general.contact') }}</a></li>
    @endif
		
<li class="list-inline-item"><a class="link-footer footer-tiny" href="mailto:info@oprivado.ao">Investidor</a></li>
		
		<li class="list-inline-item"><a class="link-footer footer-tiny" href="https://oprivado.ao/my/referrals">Afiliados</a></li>
		
      <li class="list-inline-item"><a class="link-footer footer-tiny" href="{{ url('blog') }}">{{ trans('general.blog') }}</a></li>

    </ul>
	  <small class="text-muted">
      &copy; 2021 - {{date('Y')}} {{$settings->title}}, <a style=" color: black!important; " target="_blank" href="https://orangeafrika.com/">ORANGE AFRIKA LTD.</a> 

      @if ($settings->show_address_company_footer)
          {{ $settings->company }} - {{ __('general.address') }}: {{ $settings->address }} {{ $settings->city }} {{ $settings->country }}
        @endif
    </small>
  </div>
</div>
