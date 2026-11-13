@extends('layouts.app')

@section('title') {{trans('general.funciona')}} -@endsection

@section('css')
  <script type="text/javascript">
      var error_scrollelement = {{ count($errors) > 0 ? 'true' : 'false' }};
  </script>
@endsection

@section('content')
<div class="fundo-imagem2">
      <div class="container position-relative">
      </div>
    </div>
    
  <section class="section section-sm">
    <div class="container">
        
      <div class="row">
          <div class="col-md-12 pt-4 mt-5 col-lg-12"> <div class="row justify-content-center text-left mb-sm">
        <div class="col-lg-12 py-3">
          <h2 class="mb-0 font-montserrat">
            Como funciona o Oprivado
          </h2>
        </div>
      </div> <div class="content-p">
              <p>Oprivado é uma rede social como outra qualquer onde você pode publicar seus conteúdos no formato de fotos e vídeos para diversas pessoas acessarem. A principal diferença dela para as demais redes é que o Oprivado oferece diversas formas de o criador de conteúdo ganhar dinheiro com os seus conteúdos dentro da plataforma.</p>
	
<p>Ao criar uma conta simples, você poderá acessar os conteúdos dos criadores, desde que pague para consumi-los. Mas, ao entrar na plataforma como criador de conteúdo, você pode criar uma assinatura para seu público acessar seus conteúdos todo mês ou até mesmo definir valores para os seguidores verem alguns de seus posts.</p>

<p>Apesar de ser uma plataforma para maiores de 18 anos, o foco é dar maior liberdade e segurança para os criadores ao se comunicarem com suas comunidades. É possível criar conta em diversas áreas como: Fitness; Cosplay; Moda; Viagens; Tutoriais; Beleza e cuidados pessoais; Videogames; Conteúdo adulto; Receitas; Humor e Vlogs.</p>

<p>Toda vez que uma pessoa paga pelo conteúdo, o criador recebe maior percentagem desse valor e a plataforma retém a outra pequena percentagem para manutenção da plataforma, o que inclui pagamento de impostos ao Estado, taxas do Banco, serviços de Publicidade e Marketing, custos do servidor e armazenamento de dados e serviços terceiros integrados no Oprivado. Os ganhos dos criadores de conteúdos são pagos em kwanzas directamente na sua conta bancaria dentro de 2 à 5 dias dependendo de seu Banco de apoio.</p>

<p>Além das assinaturas e das publicações, existem diversas formas de ganhar dinheiro no Oprivado. A melhor parte é que no Oprivado, o próprio criador é que define os valores dos seus conteúdos.</p>
			  
<p>Oprivado não é uma plataforma de namoro ou de encontros, portanto tome muito cuidado ao marcar encontros dentro da plataforma, pois não assumimos nenhuma responsabilidade fora da nossa comunidade. Por fim, desencorajmos esta prática.</p>
			  
<p>Quanto aos limites da Lei, o Oprivado funciona como uma plataforma de assinatura, permitindo que seguidores paguem por conteúdos exclusivos de criadores. O que circula na plataforma é conteúdo digital de diversos temas e inclusive adulto que é diferente de prostituição física que é punido no Codigo Penal angolano. O conteúdo digital é produzido voluntariamente pelo próprio criador maior de 18 anos verificado com os documentos de identificação emitidos pelo Governo de Angola. Deste modo, os criadores podem explorar sua própria imagem em conteúdos de diversos temas de forma segura, recebendo pagamentos diretamente dos assinantes.</p>
    </div>  
      
      <div class="row justify-content-center text-left mb-sm">
        <div class="col-lg-12 py-3">
          <h2 class="mb-0 font-montserrat">
            Liberdade, Responsabilidade e Segurança
          </h2>
        </div>
      </div>
			  
			 <p> O Oprivado é uma plataforma de conteúdos exclusivos que valoriza a liberdade de expressão, a criatividade e a liberdade individual, permitindo que os criadores partilhem conteúdos de diferentes categorias, como Adulto, Esportes, Culinária, Lifestyle, Podcasts, Viagens e muito mais.</p>

<p>O Oprivado não é uma plataforma de namoro ou de encontros. Por isso, não incentivamos nem promovemos a marcação de encontros presenciais através da plataforma, especialmente quando estes possam colocar em risco a sua segurança ou integridade física.</p>

<p>A liberdade de utilização da plataforma deve ser acompanhada de responsabilidade. Cada utilizador é responsável pelas suas próprias decisões, interações e ações, tanto dentro como fora do Oprivado. Não incentivamos, apoiamos ou promovemos qualquer comportamento ou situação que possa comprometer a sua integridade física ou colocar a sua segurança em risco.</p>

<p>O Oprivado destina-se exclusivamente à partilha e ao consumo de conteúdos exclusivos. Não nos responsabilizamos por encontros, acordos, interações ou acontecimentos que ocorram fora da plataforma, nem pelas consequências de decisões pessoais tomadas pelos utilizadores, sem prejuízo das responsabilidades que nos sejam legalmente aplicáveis.</p>

<p>A sua liberdade é importante. A sua segurança e responsabilidade também. Utilize a plataforma de forma consciente, respeite as regras da comunidade e proteja sempre a sua integridade física e pessoal.</p>
      
      <div class="blocks mt-5"><div class="block2"><p class="name2">Utilizador normal</p><p class="location">Este tipo de utilizador é indicado para quem gostaria de apenas consumir conteúdo dos seus criadores favoritos. Entretanto, o seu perfil não estará visivel nas pesquisas ou na página de criadores de conteúdos.</p></div>
		  
		   <div class="block2"><p class="name2">Utilizador Pro</p><p class="location">Este é um utilizador privilegiado e é indicado para quem gostaria de consumir conteúdo e interagir com os seus criadores favoritos. Suporte diferenciado e maior credibilidade.</p></div>
		  
      <div class="block2"><p class="name2">Criador de conteúdos</p><p class="location">Este tipo de utilizador é indicado para quem vai produzir e vender conteúdos auténticos e exclusivos no Oprivado. Todos os perfis de criadores de conteúdos são verificados com os seus documentos pessoais.</p></div>
		  
      </div></div>
      
        <div class="col-lg-12 mt-5 mb-5">
<div class="section">
    <div class="row">
      <div class="col-md-6">
        <label for="rangeNumberFollowers" class="w-100">
          {{ __('general.number_followers') }}
          <i class="feather icon-facebook mr-1"></i>
          <i class="feather icon-twitter mr-1"></i>
          <i class="feather icon-instagram"></i>
          <span class="float-right">
            <span id="numberFollowers">1000</span> seguidores
          </span>
        </label>
        <input type="range" class="custom-range" value="0" min="1000" max="1000000" id="rangeNumberFollowers" onInput="$('#numberFollowers').html($(this).val())">
      </div>

      <div class="col-md-6">
        <label for="rangeMonthlySubscription" class="w-100">{{ __('general.monthly_subscription_price') }}
          <span class="float-right">
            {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}<span id="monthlySubscription">{{ $settings->min_subscription_amount }} KZ</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}
        </span>
        </label>
        <input type="range" class="custom-range" value="0" onInput="$('#monthlySubscription').html($(this).val())" min="{{ $settings->min_subscription_amount }}" max="{{ $settings->max_subscription_amount }}" id="rangeMonthlySubscription">
      </div>

      <div class="col-md-12 text-left mt-5">
           <h2 style="font-size: 19px;
    font-weight: 500 !important;" class="mb-3">Você pode ganhar aproximadamente
          <span id="estimatedEarn"></span>
          {{ __('general.per_month') }}</h2>
        <p class="mb-1">
          {{trans('general.earnings_simulator_subtitle_3')}}<br>Estes valores não incluem os ganhos com conteúdos e gorjetas, apenas por subscrição ou assinatura.
        </p>
        @if ($settings->fee_commission != 0)
         <small class="w-100 d-block">Inclui a taxa da plataforma em todo seu rendimento.</small>
        @endif
      </div>
    </div>
	</div>
			</div></div></div>
</section>
@endsection

@section('javascript')

  @if ($settings->earnings_simulator == 'on')
  <script type="text/javascript">

  function decimalFormat(nStr)
  {
    @if ($settings->decimal_format == 'dot')
     var $decimalDot = '.';
     var $decimalComma = ',';
     @else
     var $decimalDot = ',';
     var $decimalComma = '.';
     @endif

     @if ($settings->currency_position == 'left')
     var currency_symbol_left = '{{$settings->currency_symbol}}';
     var currency_symbol_right = '';
     @else
     var currency_symbol_right = '{{$settings->currency_symbol}}';
     var currency_symbol_left = '';
     @endif

      nStr += '';
      var x = nStr.split('.');
      var x1 = x[0];
      var x2 = x.length > 1 ? $decimalDot + x[1] : '';
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1)) {
          var x1 = x1.replace(rgx, '$1' + $decimalComma + '$2');
      }
      return currency_symbol_left + x1 + x2 + currency_symbol_right;
    }

    function earnAvg() {
      var fee = {{ $settings->fee_commission }};
      @if($settings->currency_code == 'JPY')
       $decimal = 0;
      @else
       $decimal = 2;
      @endif

      var monthlySubscription = parseFloat($('#rangeMonthlySubscription').val());
      var numberFollowers = parseFloat($('#rangeNumberFollowers').val());

      var estimatedFollowers = (numberFollowers * 5 / 100)
      var followersAndPrice = (estimatedFollowers * monthlySubscription);
      var percentageAvgFollowers = (followersAndPrice * fee / 100);
      var earnAvg = followersAndPrice - percentageAvgFollowers;

      return decimalFormat(earnAvg.toFixed($decimal));
    }
   $('#estimatedEarn').html(earnAvg());

   $("#rangeNumberFollowers, #rangeMonthlySubscription").on('change', function() {

     $('#estimatedEarn').html(earnAvg());

   });
  </script>
@endif

@if (session('success_verify'))
  <script type="text/javascript">

	swal({
		title: "{{ trans('general.welcome') }}",
		text: "{{ trans('users.account_validated') }}",
		type: "success",
		confirmButtonText: "{{ trans('users.ok') }}"
		});
    </script>
	 @endif

	 @if (session('error_verify'))
   <script type="text/javascript">
	swal({
		title: "{{ trans('general.error_oops') }}",
		text: "{{ trans('users.code_not_valid') }}",
		type: "error",
		confirmButtonText: "{{ trans('users.ok') }}"
		});
    </script>
	 @endif

@endsection
