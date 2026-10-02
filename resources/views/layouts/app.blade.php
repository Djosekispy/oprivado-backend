<!DOCTYPE html>
<html lang="pt">
	<!-- Oprivado Angola, ORANGE AFRIKA LTD. -->
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,shrink-to-fit=no">
  <meta name="google-site-verification" content="yw1iP8wYMp5wNjEcG5t4xNYHi55WAnNCJtXt2t3Fb5E" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="@yield('description_custom')@if(!Request::route()->named('seo') && !Request::route()->named('profile')){{trans('seo.description')}}@endif">
  <meta name="google-site-verification" content="tA1uEikYiu14zJKHXpWvJYKoiWudDbDKIORDf_tOXpQ" />
  <meta name="keywords" content="oprivado, oprivado angola, angola, influencers, criador de conteudo, conteudo exclusivo, rede social" />
  <meta name="theme-color" content="{{ auth()->check() && auth()->user()->dark_mode == 'on' ? '#303030' : $settings->color_default }}">
  <title>{{ auth()->check() && User::notificationsCount() ? '('.User::notificationsCount().') ' : '' }}@section('title')@show Oprivado Angola</title>
  <link rel="dns-prefetch" href="https://cdn.oprivado.ao">
  <link rel="preconnect" href="https://cdn.oprivado.ao">
<link rel="dns-prefetch" href="//feathericons.com">
  <link href="{{ url('public/img', $settings->favicon) }}" rel="icon">

  @include('includes.css_general')

  @if ($settings->status_pwa)
    @laravelPWA
  @endif
	
	@yield('head')
  	@yield('css')
	@yield('metada')
	@yield('meta_robots')
	@yield('brother')
	
	<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "@id": "https://oprivado.ao/#organization",
  "name": "Oprivado Angola",
  "url": "https://oprivado.ao",
  "logo": "https://oprivado.ao/public/img/logotipo.svg",
  "sameAs": [
    "https://www.facebook.com/oprivado.ao/",
    "https://www.instagram.com/oprivado.ao/"
  ],
  "description": "Oprivado é a plataforma de conteúdo exclusivo em Angola. Crie seu perfil para monetizar fotos e vídeos ou assine para ter acesso a conteúdo único!",
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "Contacto",
    "url": "https://oprivado.ao/contact"
  }
}
</script>
	
	<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-3B4PR2PMBF"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-3B4PR2PMBF');
</script>
</head>

	<style type="text/css">
		.live-dot {
    width: 8px;
    height: 8px;
    background: #ff2d2d;
    border-radius: 50%;
    display: inline-block;
    top: 4px;
	right: 4px;
    position: absolute;
    animation: pulseLives 1.2s infinite;
}
	#zuck-modal-content .story-viewer.loading .head .loading, #zuck-modal-content .story-viewer.muted .tip.muted {
    bottom: 190px !important;
    background: linear-gradient(0deg, rgb(74 48 48 / 0%) 0%, #00000000 100%)!important;
}
		
	#zuck-modal-content .story-viewer .tip {
    background: linear-gradient(0deg, rgba(0, 0, 0, 0.8) 0%, transparent 100%);
	border-radius: 0px!important;
    bottom: -2px!important;
    padding: 0px 24px!important;
    word-break: break-all;
    width: 100%!important;
    height: 80px!important;
}

 @media (max-width: 576px) {
    .container-phone {
        margin-left: 13px!important;
		margin-right: 13px!important;
    }
}
	.become-creator__row {
    display: flex;
    align-items: center;
    gap: 2px;
    width: 100%;
}
				 .become-creator__cta svg {
    width: 16px;
    height: 16px;
}
			.become-creator__cta {
    display: flex
;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    width: 40px;
    height: 40px;
    background: #fd7350;
    border-radius: 100px;
    color: white;
} 
		
.btn-mobile.active {
    background-color: #f5f5f5;
    border-radius: 50%;
}

@keyframes pulseLives {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.4);
        opacity: 0.5;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}
   .seguir {background: #fff!important;
	   border: 1px solid #fd7350!important;
    padding-left: 10px;
    border-radius: 5px;
    color: #fd7350!important;
    font-weight: 500;
    padding-right: 10px;
    padding-bottom: 3px;
    padding-top: 3px;
    font-size: 11px;}
		
		.btn-category {
    width: auto!important;
}
		.custom-control-label::before {box-shadow: none!important;} .custom-control-label::before {background-color: #ccc; border: #ccc solid 1px;} .custom-switch .custom-control-label::after {background-color: #fff; }
		
		.avatar-user-home {
    border: 3px solid #FFF!important;
}
.cora {
        background: #fff!important;
		 border-radius: 4px !important;
		 border: 1px solid #dee2e6;
    }
		
		.cora2 {
        background: #f9f6f2!important;
		 border-radius: 4px !important;
		 border: 1px solid #f4eee6;
    }
		
    .rounded-4 {
        border-radius: 1.25rem !important;
    }
	
	   @media (max-width: 575px) {
        .largo {
            padding-bottom: 1rem !important;
			padding-left: 1rem !important;
			padding-right: 1rem !important;
			padding-top: 2px!important;
        }
    }
	
    @media (max-width: 991.98px) {
        .redo {
            width: 54px !important;
            height: 54px !important;
        }
    }
		
.verified { color: #ff8035!important; }

/* Substituição por imagem default para conteúdo adulto */
.adult-content-blur .media-post,
.adult-content-blur img:not(.avatarUser),
.adult-content-blur video,
.adult-content-blur .embed-responsive,
.adult-content-blur .post-image {
    display: none; /* Esconde o conteúdo real */
}

/* Mostra imagem padrão no lugar */
.adult-content-blur .media-placeholder {
    display: block;
    width: 100%;
    height: 380px;           /* ajuste conforme sua necessidade */
    background: url('/public/images/default.png') center/cover no-repeat;
    border-radius: 12px;
    position: relative;
}

/* Overlay de aviso sobre a imagem padrão */
.adult-content-blur .media-placeholder::after {
    content: "🔞 Conteúdo sensivel";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 0, 0, 0.88);
    color: #ff69b4;
    padding: 14px 32px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 16px;
    z-index: 30;
    pointer-events: none;
    white-space: nowrap;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.7);
}

/* Mensagem informativa (sempre visível) */
.adult-content-blur .media-placeholder::before {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.9);
    color: #fff;
    padding: 10px 24px;
    border-radius: 30px;
    font-size: 13.5px;
    z-index: 31;
    pointer-events: none;
    white-space: nowrap;
}
		
.borda {
    border: 1px solid #dbdbdb!important;
    border-radius: 4px !important;
}

	.content-locked {
    background: #f9f6f2;
}
		#zuck-modal-content .story-viewer.loading .head .loading, #zuck-modal-content .story-viewer.muted .tip.muted {
    font-size:10px!important;
    bottom: 90px!important;
}
		
		p.name2 {
    font-size: 22px!important;
    color: black!important;
}
		a.page-link {
    color: #000000;
}
 @media (max-width: 990px) {
    .content-locked {
    background: url(https://oprivado.ao/public/images/icon3.png) no-repeat center center #efefef;
    background-size: 49%;
    background-repeat: no-repeat;
    background-position: left 121%!important;
}
}
		@media (max-width: 767px) {
    .texto-branco-phone {
    color: white!important;
}
}
				@media (max-width: 767px) {
    .texto-esquerdo-phone {
    text-align: center;
}
}
		.btn-blocked {border-radius: 4px !important;}
		
		@media (max-width: 991px) { .perfil:before {
    content: "";
    background: linear-gradient(#000, rgba(138, 150, 163, 0));
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    background-size: 100% 144px;
    background-repeat: no-repeat;
    opacity: .4;
} }
		span#the-count {
    font-size: 11px!important;
}
		.item-add-story > .info > .name {
    font-weight: 400!important;
    font-size: 10px!important;
}
		
		.fileuploader-theme-thumbnails .fileuploader-item .type-holder {
    font-size: 9px!important;
}
		
		.fileuploader-items .fileuploader-item .fileuploader-action i {
    font-size: 14px!important;
}
		
		ul.pagination.d-flex.justify-content-center {
    margin-top: 18px!important;
    font-size: 9px!important;
}
.page-link {
    padding: 6px 9px!important;
    height: 21px !important;
    min-width: 17px !important;
    text-align: center !important;
}
		p.list-grid-block.p-text.mb-0.text-word-break.updateComment.isComment15 {
    font-size: 12px!important;
}
		
		p.list-grid-block.p-text.mb-0.text-word-break.updateComment.isReply5 {font-size: 12px!important;
}
		
		h5.mb-1 {
    font-weight: 500!important;
    font-size: 17px!important;
}

		.live-views {
    padding: 3px 8px!important;
    border-radius: 4px!important;
    font-weight: 400!important;
    font-size: 13px!important;
}
		
	 .live {
    padding: 3px 8px!important;
    border-radius: 4px!important;
    font-weight: 400!important;
    font-size: 13px!important;
}
		.live-span {
    font-weight: 500;
}
		.link-footer{
    font-size: 12px!important;
}
			.block {
        width: 100%;
    background: #ffffff;
    padding: 1rem;
    margin: 0 6px 12px;
    border: 1px solid #ced4da;
    border-radius: 8px;
}
		#zuck-modal-content .story-viewer .tip {
    font-size: 13px!important;
}
		
		#zuck-modal-content .story-viewer .head .time {
    font-weight: 400!important;
    font-size: 11px!important;
	line-height: 8px;
}

		#zuck-modal-content .story-viewer .head .left .info .name {
    font-weight: 400!important;
    font-size: 13px!important;
}
		
	@media screen and (max-width: 768px) {.verified-story:after {
    display: none;
}}

@media screen and (min-width: 768px) {
   .block {
        width: 48%;
    }
}
@media screen and (min-width: 992px) {
    block {
        width: 31%;
    }
}
@media screen and (min-width: 1200px) {
     .block {
        width: 32% !important;
    }
}
	.block2 {
    width: 100%;
    background: #ffffff;
    padding: 1rem;
    margin: 0 6px 12px;
    border: 1px solid #ced4da;
    border-radius: 8px;
}

@media screen and (min-width: 768px) {
   .block2 {
        width: 48%;
    }
}
@media screen and (min-width: 992px) {
    block2 {
        width: 31%;
    }
}
@media screen and (min-width: 1200px) {
     .block2 {
        width: 32% !important;
    }
}

.blocks {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin: 0 -6px;
}

		body, p {
    color: black;
    font-weight: 400;
    font-size: 14px;
}
		.sweet-alert .sa-icon {
    display: none!important;
}
		
		.fileuploader-theme-thumbnails .fileuploader-item .content-holder h5 {
    font-size: 10px!important;
    font-weight: 500 !important;
}

.lead {
    font-weight: 400!important;
}
		
		.ico-no-result {
    color: black!important;
    border: 3px solid black!important;
}
	sup {
    top: -3px!important;
    color: red!important;
    left: 2px!important;
}
		
		  .message {
    padding: 6px 14px 7px !important;
    border-radius: 25px !important;
    font-size: 14px!important;
    font-weight: 400!important;
}

.bg-light {
    background-color: #f8f9fa !important;
    color: black!important;
}

.msg-inbox .active .verified {
    color: #ff8035!important;
}
			.navbar-brand {
    font-size: 0px!important;
} 
		.buttons-mobile-nav {
    right: 0px!important;
    top: 4px!important;
}
		
			@media (max-width: 991px) {
    .ocultar-phone {
        display:none!important;
    }
}
		
		@media (min-width: 991px) {
    .ocultar-pc {
        display:none!important;
    }
}
		
		@media (max-width: 991px) {
    .notify {
        top: 9px !important;
        right: 11px !important;
    }
}

.notify {
	top: 4px;
    right: 4px;
    padding: 2px 5px;
    background: #F00;
    color: #FFF;
    line-height: normal;
    font-weight: 400;
    border-radius: 100px;
    border-bottom: 0px solid #DADADA;
    font-size: 8px;
}
		
		p.list-grid-block.p-text.mb-0.text-word-break.updateComment.isComment2 {
font-size: 13px !important;
color: black!important;
 font-weight: 400!important;
}

		h5.modal-title {
    font-weight: 500 !important;
    font-size: 16px;
}
		.card-title {
    margin-bottom: .75rem;
    color: black;
    font-size: 14px;
}
		p.list-grid-block.p-text.mb-0.text-word-break.updateComment.isComment2 {
    font-size: 13px!important;
} 
		
			.text-black {
    color: black!important;
}
		p.card-text.position-relative {
    color: black;
}
		
		.card-body.pb-0 {
    color: black;
}
		
		.dropdown-item:focus, .dropdown-item:hover, .dropdown-item.active, .dropdown-item:active {
    background-color: #f9f9f9!important;
}
		
		span.text-muted.dot-item {
    font-size: 13px!important;
}
		
@media (min-width: 576px) .form-inline .form-control {width: 100%;}

.table {
    width: 100%;
}
		@media (max-width: 992px){ .navbar_background_color {
    border-bottom: 1px solid #ced4da!important;
    padding-bottom: 14px !important;
    padding-top: 14px !important;
}}
		
.card-novo {
    border: 1px solid #dbdbdb!important;
}
		
		.dropdown-menu {
    box-shadow: 0px 5px 18px 0px rgb(0 0 0 / 6%);
}
		
		.custom-radio .custom-control-input:disabled:checked~.custom-control-label::before {
    background-color: #fd7350!important;
}
		
		.btn, .sa-button-container button {
    border-radius: 4px!important;
}
		
		@media (max-width: 991px) {
    .navbar .navbar-collapse .navbar-nav .nav-item .nav-link:not(.btn) {
        font-size: 14px!important;
    }
}
	@media (max-width: 576px) {
    div#stories {margin-top: 15px;margin:10px; }}
		
	.sweet-alert {
    background-color: #fff;
    font-family: Inter Tight, system-ui!important;
}
	.sweet-alert button {
    border: none!important;
	font-size: 13px;
    font-weight: 400;
	padding: 8px 35px;
    margin: 26px 5px 0 5px;
	box-shadow: none !important;
}
	
	button.confirm {
    background: #fd7350!important;
}
	
	.sweet-alert button.cancel {
    background-color: #fea48d!important;
}
	
	.sweet-alert h2 {
	font-size: 19px !important;
    color: #020202;
    font-weight: 500!important;
    margin: 8px 0;
}
	
	.sweet-alert p {
    color: #262626;
    font-size: 13px;
    font-weight: 400;
}
	.img-user {
    border: 3px solid #FFF!important;
}
		span.text-muted.totalComments.dot-item {
    font-size: 12px;
}
		
		span.btn-block.mt-3 {
    font-size: 13px;
    font-weight: normal;
}
		
			.custom-select {
    font-size: 12px!important;
}
		
		a.social-share i {
    font-size: 18px;
}
		.list-unstyled {
    font-size: 13px;
 font-weight: 400!important;
}
		i.fa.fa-check.mr-2.text-primary {
    font-size: 10px;
}
		
		.modal-open .modal, .sweet-overlay {
    backdrop-filter: none;
}
	
		.nav-profile li.active {
    border-bottom: 2px solid #fd7350 !important;
}
		
		.nav-profile {
    border-bottom: 1px solid #dbdbdb;
}
		
		.dropdown-menu.show {
    border: 1px solid #dbdbdb;
}
		
		.popout {
   font-size: 13px!important;
	padding: 10px 10px;
}
		.font-weight-bold {
    font-weight: 600 !important;
}
		.badge {
    font-size: 10px;
    font-weight: 400;
    transition: none;
}
			.select2-container .select2-search--inline .select2-search__field {
    box-sizing: border-box;
    border: none;
    margin-top: 5px;
    font-size: 13px;
}
		
		.icon-notify {
    font-size: 42px!important;
    line-height: 30px!important;
}
		
		h6.mt-2.font-montserrat.text-notify {
    font-size: 14px!important;
    color: black!important;
}
		
		.alert-warning {
    font-size: 13px;
}
		
		.select2-container--default .select2-selection--multiple .select2-selection__choice {
    font-size: 13px;
}
		
		.select2-results__option {
    padding: 6px;
    font-size: 13px;
}
		
		.select2-container .select2-selection--multiple {
    min-height: 44px !important;
}
		
		.select2-container--default .select2-selection--multiple {
    border-left-width: 1px !important;
    border-bottom-left-radius: 4px !important;
			 border-top-left-radius: 4px !important;
    background-color: #fff !important;
    border-color: #cad1d7 !important;
}
		
		.alert-primary {
    font-size: 13px;
}
		p.card-text.mb-1 {
    font-size: 14px;
    color: black;
}
		.alert-success {
    font-size: 13px;
}
		.input-group-text {
    border-left: 1px solid #cad1d7;
    border-radius: 4px;
    background-color: #ffffff;
    align-items: center;
    font-size: 14px;
    padding: .375rem .75rem;
}
		.table thead th {
    color: black;
    font-size: 14px;
    font-weight: 500 !important;
}
		
		.table td, .table th {
    background: #fff;
}
		
		label {
    display: inline-block;
    margin-bottom: .5rem;
    font-size: 15px;
    color: black;
}
		
		.list-group-item.active, .btn-outline-primary:not(:disabled):not(.disabled).active {
    color: black;
    background-color: #efefef!important;
    border-color: #efefef!important;
    border-radius: 7px;
}
		
		.list-group-item-action:focus, .list-group-item-action:hover {
    background-color: #fff;
}
		
		.card-settings > .list-group-flush>.list-group-item {
    font-size: 14px;
	color: black;
}
		.alert-danger {
    font-size: 13px;
}
		.shadow-sm {
    box-shadow: none!important;
}
		.fee-wrap {
    font-size: 12px;
}
		
		.alert-primary {
    color: #000000;
    border-color: #000000;
    background-color: #ffffff;
}
		.custom-control-input {
    position: relative;
}
		.table thead th {
    font-weight: 400!important;
	border-bottom: 1px solid #dbdbdb;
}
		
		h6 {
    font-weight: 500 !important;
}
		.shadow {
    box-shadow: none!important;
}
		
		h2.mb-0.font-montserrat {
    font-size: 19px;
    font-weight: 500 !important;
	color: black;
}
		
		 h4 {
    font-weight: 500 !important;
	color: black!important;
}
		.f-size-20 {
    font-size: 19px;
}
	.search-bar {
    height: 37px !important;
}
		.dropdown-item {
    padding: 0.4rem 0.5rem;
    font-size: 14px;
	color: black!important;
}
		.none {
    display: none!important;
}
	
		.shadow-large {
    box-shadow: none!important;
}
		a.loadMoreReplies {
    font-size: 13px;
   }
		
		.isReplyTo.display-none.w-100.bg-light.py-2.px-3.mb-3.rounded {
    font-size: 13px;
}
		span.small.sm-font.sm-date.text-muted.mr-2.c-pointer.font-weight-bold.replyButton { font-weight: 500 !important; }
		p.list-grid-block.p-text.mb-0.text-word-break.updateComment.isComment3 { font-size: 13px; }
		h6.media-heading.mb-0 { font-size: 14px; color: black !important; }
		.btn {
    font-size: 13px!important;
}
		p.list-grid-block.p-text.mb-0.text-word-break.updateComment.isReply1 {
    font-size: 13px;
}
		
		.link-footer, .ico-social {
    color: #737373 !important;
}
		
		.text-muted {
    color: black!important;
}
		
		.custom-checkbox .custom-control-input ~ .custom-control-label, .custom-control-label {
    font-size: 12px!important;
}
		
		b, strong {
    font-weight: 500;
}
		.btn:hover:not(.e-none) {
    transform: none;
    box-shadow: none;
}	
		
		.btn-post {
    width: 41px!important;
    height: 41px!important;
    transition: none!important;
}
		
		.btn-post:hover {
    background-color: #fff;
}
		
.btn {
    border-radius: 4px!important;
	line-height: 1.0!important;
}		
		
.navbar_background_color {
    border-bottom: 1px solid #ced4da;
}
		
		@media (min-width: 576px) {
    .form-inline .form-control {
        border: 1px solid #ced4da !important;
    }
}
		
		.form-control {
    font-size: 13px;
    color: #495057;
    border: 1px solid #ced4da;
    border-radius: 4px;
	height: calc(2.75rem + -5px);
    transition: none;
	font-family: Inter Tight, system-ui!important;
}
		
		span.ml-2 {
    font-size: 14px;
}
		
		.menu-left-home li > a:hover, .menu-left-home li > a.active {
    background-color: #efefef;
    color: black;
    font-weight: 400;
}
		
		.menu-left-home li > a {
    padding: 8px 17px;
    display: block;
    text-decoration: none;
    margin-bottom: 15px;
    border-radius: 7px;
	color:black!important;
}
		
		a.stretched-link.text-white {
    font-size: 14px;
}
		
		small.text-white.w-100.d-block.text-truncate {
    font-size: 12px;
}
		
		h6.mb-3.text-muted.font-weight-light.filter-explorer {
    font-size: 15px;
    font-weight: 400 !important;
}
		
		small.text-muted.font-14 {
    font-size: 12px!important;
}
		
		small.timeAgo.text-muted {
    font-size: 12px!important;
}
		
		h5.mb-0.font-montserrat {
    font-size: 15px;
    font-weight: 500 !important;
    color: #080808 !important;
}
		a.display-none.link-border {
    font-size: 13px;
}
		
		.truncated {
    font-size: 14px;
	color: black!important;
}
		.jumbotron {
    background-color: #ffffff;
}
		span.text-muted.totalComments.dot-item.toggleComments {
			 font-size: 12px;
} 
		.countLikes {
    font-size: 12px;
}
		
		.b-radio-custom {
    border-radius: 0px!important;
}
		.card {
    border: 1px solid #dbdbdb;
	border-radius: 4px !important;
}
		
				body, a, p, h1, h2, h3, h4, h5, h6 {
    font-family: Inter Tight, system-ui!important;
		</style>

<body>
	
<div id="page-loader">
    <img src="/public/images/opvload.svg">
</div>

<style>
#page-loader {
    position: fixed;
    inset: 0;
    background: rgba(255,255,255,0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
}

#page-loader img {
    width: 80px;
    height: 80px;
	pointer-events: none!important;
}
</style>

<script>
window.addEventListener('load', function () {
    document.getElementById('page-loader').style.display = 'none';
});
</script>
	
  @if ($settings->google_tag_manager_body != '')
  {!! $settings->google_tag_manager_body !!}
  @endif

  @if ($settings->disable_banner_cookies == 'off')
  <div class="btn-block text-center showBanner padding-top-10 pb-3 display-none">
    <i class="fa fa-cookie-bite"></i> {{trans('general.cookies_text')}}
    @if ($settings->link_cookies != '')
      <a href="{{$settings->link_cookies}}" class="mr-2 text-white link-border" target="_blank">{{ trans('general.cookies_policy') }}</a>
    @endif
    <button class="btn btn-sm btn-primary" id="close-banner">{{trans('general.go_it')}}
    </button>
  </div>
@endif

  <div id="mobileMenuOverlay" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"></div>

  @auth
    @if (! request()->is('messages/*') && ! request()->is('live/*'))
    @include('includes.menu-mobile')
  @endif
  @endauth

  <div class="popout popout-error font-default"></div>

@if (auth()->guest() && request()->path() == '/' && $settings->home_style == 0
    || auth()->guest() && request()->path() != '/' && $settings->home_style == 0
    || auth()->guest() && request()->path() != '/' && $settings->home_style == 1
    || auth()->guest() && request()->path() == '/' && $settings->home_style == 2
    || auth()->guest() && request()->path() != '/' && $settings->home_style == 2
    || auth()->check()
    )
  @include('includes.navbar')
  @endif

  <main @if (request()->is('messages/*') || request()->is('live/*')) class="h-100" @endif role="main">
    @yield('content')

    @if (auth()->guest() 
          && ! request()->route()->named('profile')
          && ! request()->is(['creators', 'category/*', 'creators/*'])
          || auth()->check()
          && request()->path() != '/'
          && ! request()->route()->named('profile')
          && ! request()->is([
            'my/bookmarks', 
            'my/likes', 
            'my/purchases', 
            'explore', 
            'messages', 
            'messages/*', 
            'creators', 
            'category/*', 
            'creators/*', 
            'live/*'
            ])          
          )

          @if (auth()->guest() && request()->path() == '/' && $settings->home_style == 0
                || auth()->guest() && request()->path() != '/' && $settings->home_style == 0
                || auth()->guest() && request()->path() != '/' && $settings->home_style == 1
                || auth()->guest() && request()->path() != '/' && $settings->home_style == 2
                || auth()->check()
                  )

                  @if (auth()->guest() && $settings->who_can_see_content == 'users')
                    <div class="text-center py-3 px-3">
                      @include('includes.footer-tiny')
                    </div>
                  @else
                    @include('includes.footer')
                  @endif

          @endif

  @endif

  @guest

  @if (Helper::showLoginFormModal())
      @include('includes.modal-login')
    @endif

  @endguest

  @auth

    @if ($settings->disable_tips == 'off')
     @include('includes.modal-tip')
   @endif

   @if ($settings->gifts)
     @include('includes.modal-gifts')
   @endif

    @include('includes.modal-payperview')

    @if ($settings->live_streaming_status == 'on')
      @include('includes.modal-live-stream')
    @endif

    @if ($settings->allow_scheduled_posts)
      @include('includes.modal-scheduled-posts')
    @endif
    
  @endauth

  @guest
    @include('includes.modal-2fa')
  @endguest
</main>

  @include('includes.javascript_general')

  @yield('javascript')

@auth
  <div id="bodyContainer"></div>
	 @include('includes.choose-sensitive')
@endauth
	
	
	
	
		<script>
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('select-proof')) {
            const id = e.target.getAttribute('data-target');
            const input = document.getElementById('proofOfComplaint-' + id);
            if (input) input.click();
        }
    });
    document.addEventListener('change', function(e) {
        if (e.target.type === 'file' && e.target.id.startsWith('proofOfComplaint-')) {
            const id = e.target.id.split('-')[1];
            const span = document.getElementById('proofFileName-' + id);
            
            if (span && e.target.files.length > 0) {
                span.textContent = e.target.files[0].name;
            }
        }
    });

});
</script>
<!--<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
</body>
</html>
