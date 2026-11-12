@extends('layouts.app')

@section('title') {{ request()->is('explore') ? __('general.explore') . ' -' : null }}@endsection

@section('content')


<section class="section section-sm">
    <div class="container container-lg-3 pt-lg-5 pt-3">
      <div class="row">

        <div class="col-md-2">
          @include('includes.menu-sidebar-home')
        </div>

        <div class="col-md-6 p-0 second wrap-post">
			
	 
        @if ($updates->count() != 0)
        @auth
			
			@if ($settings->announcement != ''
            && $settings->announcement_show == 'creators'
            && auth()->user()->verified_id == 'yes'
            || $settings->announcement != ''
            && $settings->announcement_show == 'all'
            )
          <div style="background-color: #ffffff!important;" class="alert alert-{{$settings->type_announcement}} announcements display-none card-border-0" role="alert">
            <button type="button" class="close" id="closeAnnouncements">
              <span aria-hidden="true">
                <i class="text-black bi bi-x-lg"></i>
              </span>
            </button>

            <h4 class="alert-heading">
      @if(request()->get('type_announcement', $settings->type_announcement) == 'primary')
          Nota:
      @elseif(request()->get('type_announcement', $settings->type_announcement) == 'danger')
          Comunicado:
      @endif
    </h4>
            <p class="update-text">
              {!! $settings->announcement !!}
            </p>
          </div><!-- end announcements -->
        @endif
			
        <div class="d-lg-flex d-block mb-3 justify-content-between align-items-center px-lg-0 largo2 mb-0 text-word-break">
            <!-- form -->
            <form class="position-relative w-100 mb-lg-0 mb-2" role="search" autocomplete="off" action="{{ url('explore') }}" method="get" class="position-relative">
              <img alt="Pesquisar" src="/public/img/pesquisa.svg" width="16px" style="position: absolute;top: 11px;left: 15px;">
             <input type="text" minlength="3" required name="q" class="form-control pl-5" value="{{ request()->get('q') }}" placeholder="{{ __('general.search') }}" id="searchInput">

			<div style=" padding: 10px; border-radius: 5px; border: 1px solid #ced4da; " id="suggestionsBox" class="suggestions-box mt-2">
    <a href="/explore/?sort=unlockable" class="btn btn-light btn-block text-left mb-1">
        Conteúdos pagos
    </a>

    <a href="/explore/?sort=oldest" class="btn btn-light btn-block text-left mb-1">
        Conteúdos mais antigos
    </a>
				
	<a href="/explore/?sort=free" class="btn btn-light btn-block text-left mb-1">
        Conteúdos gratuítos
    </a>
</div>
          </form><!-- form -->

          </div><!--  end d-lg-flex -->
          @endauth
			
			@if ($settings->live_streaming_status == 'on') 
							
							@php
								$liveService = app(\App\Services\LiveService::class);
								$activeLives = $liveService->countActiveLives() ?? 0;
							@endphp

							@if($activeLives > 0)
								<a class="text-decoration-none" href="{{ url('explore/creators/live') }}"> <div style="border-radius: 4px;" class="p-3 mb-4 border container-phone"> <div class="become-creator__row">
                                                    <i style="font-size: 30px; color: red; animation: pulseLives 1.2s infinite;" class="feather icon-radio mr-3"></i>
                                                    <div style="margin-left: 4px;">
                                                        <div style="font-weight: 500;" class="fw-bold text-black">
                                                             <span>Criadores estão em directo</span></div>
                                                        <small class="text-muted"> junte-se na live agora mesmo</small>
                                                    </div>
           </div> </div></a> 
							@endif
							
					@endif

			@include('includes.featured-top-creator')

          <div class="grid-updates position-relative" id="updatesPaginator">
              @include('includes.updates')
          </div>

        @else
          <div class="grid-updates position-relative" id="updatesPaginator"></div>

        <div class="my-5 text-center no-updates">
          <span class="btn-block mb-3">
            <i class="fa fa-photo-video ico-no-result"></i>
          </span>
        <h4 class="font-weight-light">{{__('general.no_posts_posted')}}</h4>
        </div>
        @endif
        </div><!-- end col-md-6 -->

        <div class="col-md-4 mb-4 d-lg-block d-none">
          @if ($users->count() == 0)
          <div class="panel panel-default panel-transparent mb-4 d-lg-block d-none">
        	  <div class="panel-body">
        	    <div class="media none-overflow">
        			  <div class="d-flex my-2 align-items-center">
        			      <img class="rounded-circle mr-2" src="{{Helper::getFile(config('path.avatar').auth()->user()->avatar)}}" width="60" height="60">

        						<div class="d-block">
        						<strong>{{auth()->user()->name}}</strong>


        							<div class="d-block">
        								<small class="media-heading text-muted btn-block margin-zero">
                          <a href="{{url('settings/page')}}">
                						{{ auth()->user()->verified_id == 'yes' ? __('general.edit_my_page') : __('users.edit_profile')}}
                            <small class="pl-1"><i class="fa fa-long-arrow-alt-right"></i></small>
                          </a>
                        </small>
        							</div>
        						</div>
        			  </div>
        			</div>
        	  </div>
        	</div>
        @endif

          <div class="navbar-collapse collapse d-lg-block sticky-top" id="navbarUserHome">

            @if ($users->count() != 0)
                @include('includes.explore_creators')
            @endif

            <div class="d-lg-block d-none">
              @include('includes.footer-tiny')
            </div>

         </div><!-- navbarUserHome -->

        </div><!-- col-md -->

      </div>
    </div>
  </section>

 <style>
@media (max-width: 575px) {
    .largo2 {
        padding-bottom: 0px !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
        padding-top: 2px !important;
    }
}

.suggestions-box {
    display: none;
}

.suggestions-box.show {
    display: block;
}
 </style>

<script>
const input = document.getElementById('searchInput');
const box = document.getElementById('suggestionsBox');

input.addEventListener('focus', () => {
    box.classList.add('show');
});

document.addEventListener('click', (e) => {
    if (!input.contains(e.target) && !box.contains(e.target)) {
        box.classList.remove('show');
    }
});
</script>

<script>
(() => {
    const feed = document.getElementById('updatesPaginator');
    if (!feed || !window.fetch) {
        return;
    }

    const pendingPosts = [];
    const processedPosts = new WeakSet();
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.content : '';
    let postsSincePromotion = 0;
    let nextPromotionAfter = randomInterval();
    let processingPosts = false;
    let noMorePromotions = false;

    function randomInterval() {
        const intervals = [4, 6, 8];
        return intervals[Math.floor(Math.random() * intervals.length)];
    }

    function recordImpression(card) {
        const sendImpression = () => fetch(card.dataset.impressionUrl, {
            method: 'POST',
            credentials: 'same-origin',
            keepalive: true,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        }).then(response => {
            if (!response.ok && response.status !== 204) {
                console.error('Não foi possível registar a impressão da publicação patrocinada.', response.status);
            }
        }).catch(error => {
            console.error('Erro ao registar a impressão da publicação patrocinada.', error);
        });

        if (!('IntersectionObserver' in window)) {
            sendImpression();
            return;
        }

        const observer = new IntersectionObserver((entries, intersectionObserver) => {
            if (!entries.some(entry => entry.isIntersecting && entry.intersectionRatio >= 0.5)) {
                return;
            }

            intersectionObserver.disconnect();
            sendImpression();
        }, { threshold: 0.5 });

        observer.observe(card);
    }

    async function insertPromotionAfter(post) {
        const endpoint = new URL(@json(route('promoted-post.random')), window.location.origin);
        Array.from(feed.querySelectorAll('.card-updates[data], .js-promoted-post[data-update-id]')).slice(-100).forEach(post => {
            const postId = post.classList.contains('card-updates')
                ? post.getAttribute('data')
                : post.dataset.updateId;
            endpoint.searchParams.append('exclude_posts[]', postId);
        });

        const response = await fetch(endpoint.toString(), {
            cache: 'no-store',
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (response.status === 204) {
            noMorePromotions = true;
            return;
        }

        if (!response.ok) {
            throw new Error(`Pedido de publicação patrocinada falhou: ${response.status}`);
        }

        const template = document.createElement('template');
        template.innerHTML = (await response.text()).trim();
        const card = template.content.firstElementChild;

        if (!card || !card.classList.contains('js-promoted-post')) {
            throw new Error('A resposta AJAX da promoção não contém o componente esperado.');
        }

        post.insertAdjacentElement('afterend', card);
        recordImpression(card);
    }

    async function processPosts() {
        if (processingPosts) {
            return;
        }

        processingPosts = true;
        while (pendingPosts.length > 0) {
            const post = pendingPosts.shift();
            if (!post.isConnected) {
                continue;
            }

            postsSincePromotion++;
            if (!noMorePromotions && postsSincePromotion >= nextPromotionAfter) {
                postsSincePromotion = 0;
                nextPromotionAfter = randomInterval();
                try {
                    await insertPromotionAfter(post);
                } catch (error) {
                    console.error('Erro ao carregar uma publicação patrocinada.', error);
                }
            }
        }
        processingPosts = false;
    }

    function enqueuePosts(node) {
        if (!(node instanceof Element)) {
            return;
        }

        const posts = [];
        if (node.matches('.card-updates')) {
            posts.push(node);
        }
        posts.push(...node.querySelectorAll('.card-updates'));

        posts.forEach(post => {
            if (!processedPosts.has(post)) {
                processedPosts.add(post);
                pendingPosts.push(post);
            }
        });

        processPosts();
    }

    feed.querySelectorAll('.js-promoted-post').forEach(recordImpression);
    feed.querySelectorAll('.card-updates').forEach(enqueuePosts);

    new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            mutation.addedNodes.forEach(enqueuePosts);
        });
    }).observe(feed, { childList: true, subtree: true });
})();
</script>

@endsection