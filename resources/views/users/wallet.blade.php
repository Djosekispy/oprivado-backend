@extends('layouts.app')

@section('title') {{__('general.wallet')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">
			
			<div class="py-5">
          <h2 class="mb-0 font-montserrat"> {{__('general.wallet')}}</h2>
        </div>

          @include('errors.errors-forms')

          @if (session('error_message'))
          <div class="alert alert-danger mb-3">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
            </button>

            {{ session('error_message') }}
          </div>
          @endif

          @if (session('success_message'))
          <div class="alert alert-success mb-3">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
            </button>

            {{ session('success_message') }}
          </div>
          @endif

          <div style="color: #000000; border-color: #000000; background-color: #ffffff;" class="alert shadow overflow-hidden" role="alert">

            <div class="inner-wrap">
              <span>
                <h2><strong>{{ Helper::userWallet() }}</strong>
                  <small class="h5 none">{{ $settings->wallet_format == 'real_money' ? $settings->currency_code : null}}</small>
                </h2>

                <span class="w-100 d-block">
                {{__('general.funds_available')}}
                </span>

                @if ($equivalent_money)
                  <span>
                    <strong>{{ $equivalent_money }}</strong>
                  </span>
                @endif

                <span class="w-100 none d-block mt-2">
                  @if (auth()->user()->balance != 0.00)
                  <a href="#" data-toggle="modal" data-target="#modalTransfer" class="btn btn-1 btn-success mb-2">
                    <i class="bi bi-arrow-left-right mr-2"></i> {{ __('general.transfer_balance') }}
                  </a>
                  @endif
                </span>
              </span>
            </div>
            </div><!-- /alert -->

			  <form method="POST" action="{{ url('add/funds') }}" id="formAddFunds">
			@csrf
			<div class="form-group mb-4">
				<label>Montante</label><sup>*</sup>
				<div class="input-group mb-2">
					<div class="input-group-prepend">
						<span class="input-group-text">KZ</span>
					</div>
					<input class="form-control form-control-lg" required id="onlyNumber" name="amount" min="{{ $settings->min_deposits_amount }}" max="{{ $settings->max_deposits_amount }}" autocomplete="off" placeholder="Min: 2.000 - Max: 1.000.000" type="number">
					
				</div>
				<small style="font-size: 10px;" class="form-text mb-4">Não fazemos reembolsos</small>
			</div>

			  <label>Metódo de pagamento</label><sup>*</sup>

            @foreach (PaymentGateways::where('enabled', '1')->orderBy('type', 'DESC')->get() as $payment)

              @php
              if ($payment->type == 'card' ) {
                $paymentName = '<img src="'.url('public/img/visama.png').'" width="100"/>';
              } elseif ($payment->type == 'bank') {
                $paymentName = '<i class="fa fa-university mr-1 icon-sm-radio"></i> '.__('general.bank_transfer');
              } else if ($payment->name == 'PayPal') {
                $paymentName = '<img src="'.url('/public/img/PayPalWEB.svg').'" width="65"/>';
              } else if ($payment->name == 'Coinpayments') {
                $paymentName = '<img src="'.url('public/img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'coinpayments-white.png').'" width="150"/>';
              } else if ($payment->name == 'Coinbase') {
                $paymentName = '<img src="'.url('public/img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'coinbase-white.png').'" width="110"/>';
              } else if ($payment->name == 'NowPayments') {
                $paymentName = '<img src="'.url('public/img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'nowpayments-white.png').'" width="130"/>';
              } else if ($payment->name == 'Mercadopago') {
                $paymentName = '<img src="'.url('public/img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'mercadopago-white.png').'" width="100"/>';
              } else if ($payment->name == 'Flutterwave') {
                $paymentName = '<img src="'.url('public/img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'flutterwave-white.png').'" width="150"/>';
              } else if ($payment->name == 'Mollie') {
                $paymentName = '<img src="'.url('public/img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'mollie-white.png').'" width="80"/>';
              } else if ($payment->name == 'Razorpay') {
                $paymentName = '<img src="'.url('public/img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'razorpay-white.png').'" width="110"/>';
              } else {
                $paymentName = '<img src="'.url('public/img/payments', $payment->logo).'" width="100"/>';
              }

              @endphp
              <div style=" border: 1px solid #cfd7db!important; border-radius: 4px!important; padding: 12px!important;" class="custom-control custom-radio mb-3">
                 <input style=" margin-left: 5px!important; margin-right: 8px!important; " name="payment_gateway" required value="{{$payment->name}}" id="tip_radio{{$payment->name}}"  class="custom-control-input" type="radio">
               
                <label class="custom-control-label" for="tip_radio{{$payment->name}}">
                  <span><strong>{!!$paymentName!!}</strong></span> <br/>
				  <span>
					  <label style=" 
                        font-size: 12px; 
                        line-height: 1.6; 
                        color: #666; 
                        text-align: justify;">{{$payment->bank_info}}</label>
					  </span>
                </label>
				  
				 @if (isset($payment->name) && $payment->name == 'gpo')
				  	<div class="form-group mb-2" style="display:none;" id="telephone">
				<label style="margin: 13px 0 0 0;font-size: 12px;line-height: 1.6;color: #666;text-align: justify;font-weight: 400;margin-top: 0px;">O número deve estar associado ao Multicaixa Express</label>
				<div class="input-group mb-2">
					<div class="input-group-prepend">
						<span class="input-group-text">+244</span>
					</div>
					<input class="form-control form-control-lg"  name="phone" min="9" max="9" autocomplete="off" placeholder="Digite o número de telemóvel" id="phone" type="number">
				</div>
			</div> @endif

              </div>

            @endforeach
					
					 <p class="help-block margin-bottom-zero fee-wrap">

                <span class="d-block w-100">
                {{ __('general.transaction_fee') }}:

                <span class="float-right"><strong>{{ Helper::symbolPositionLeft() }}<span id="handlingFee">0</span>{{ Helper::symbolPositionRight() }}</strong></span>
              </span><!-- end transaction fee -->

              @if (auth()->user()->isTaxable()->count() && $settings->tax_on_wallet)
                @foreach (auth()->user()->isTaxable() as $tax)
                <span class="d-block w-100 isTaxableWallet percentageAppliedTaxWallet{{$loop->iteration}}" data="{{ $tax->percentage }}">
                  {{ $tax->name }} {{ $tax->percentage }}%:

                  <span class="float-right">
                  <strong>{{ Helper::symbolPositionLeft() }}<span class="percentageTax{{$loop->iteration}}">0</span>{{ Helper::symbolPositionRight() }}</strong>
                </span>
              </span>
                @endforeach

  						@endif

                <span class="d-block w-100">
                  {{ __('general.total') }} à pagar:

                  <span class="float-right">
                  <strong>{{ Helper::symbolPositionLeft() }}<span id="total">0</span>{{ Helper::symbolPositionRight() }}</strong>
                </span>
              </span><!-- end total -->
              </p>
				<div id="result" style="display: none;">
				</div>
			<div class="alert alert-primary display-none" id="multicaixaAlert">
				<span class="m-0">Abra o seu Multicaixa Express para finalizar o pagamento.</span>
			</div>
			   <div class="alert alert-danger display-none" id="errorAddFunds">
			<span class="m-0" id="showErrorsFunds"></span>
		</div>

		<button class="btn btn-1 btn-primary btn-block mt-4"  id="addFundsBtn" type="submit"><i></i> {{__('general.add_funds')}}</button>
		<button class="btn btn-1 btn-primary btn-block mt-4" style="display:none;" id="addWalletBtn" type="button"><i></i>Pagar</button>
	<button type="button" onclick="GenerateReference()" id="mcx_submit_link" style="display: none;" class="btn btn-1 btn-primary btn-block mt-4">
    Gerar referência
</button>
	</form>

          @if ($data->count() != 0)
         <label class="mt-5">Últimas transações</label>

          <div class="card">
            <div style="border: 1px solid #dbdbdb!important;" class="table-responsive">
              <table class="table table-striped m-0">
                <thead>
                  <th scope="col">ID</th>
                  <th scope="col">{{ __('admin.amount') }}</th>
                  <th class="none" scope="col">{{ __('general.payment_gateway') }}</th>
                  <th scope="col">{{ __('admin.date') }}</th>
                  <th scope="col">{{ __('admin.status') }}</th>
                </thead>

                <tbody>
                  @foreach ($data as $deposit)

                    <tr>
                      <td>{{ str_pad($deposit->id, 4, "0", STR_PAD_LEFT) }}</td>
                      <td>{{ App\Helper::amountFormat($deposit->amount) }}</td>
                      <td class="none">{{ $deposit->payment_gateway == 'Bank Transfer' || $deposit->payment_gateway == 'Bank' ? __('general.bank_transfer') : $deposit->payment_gateway }}</td>
                      <td>{{ date('d M, Y', strtotime($deposit->date)) }}</td>

                     @php
    switch ($deposit->status) {
        case 'pending':
            $mode = 'warning';
            $_status = __('general.naopago');
            break;

        case 'processing':
            $mode = 'info';                       // ou 'primary'
            $_status = __('general.processando'); // Crie esta chave na tradução
            break;

        case 'active':
        case 'success':                           // caso tenha este status também
            $mode = 'success';
            $_status = __('general.pago');
            break;

        default:
            $mode = 'danger';
            $_status = __('general.falhou');
            break;
    }
@endphp

                       <td><span>{{ $_status }} @if ($deposit->status == 'active')
                         <a href="{{url('deposits/invoice', $deposit->id)}}" target="_blank"><i class="far fa-file-alt ml-1"></i></a>@endif</span></td>

                      
                    </tr><!-- /.TR -->
                    @endforeach
                </tbody>
              </table>
            </div><!-- table-responsive -->
          </div><!-- card -->
         
          @if ($data->hasPages())
  			    	<div class="mt-3 none">
                {{ $data->links() }}
              </div>
  			    	@endif

        @endif

        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>

  @if (auth()->user()->balance != 0.00)
    @include('includes.modal-transfer')
  @endif

@endsection

@section('javascript')
<script type="text/javascript">
@if (in_array(config('settings.currency_code'), config('currencies.zero-decimal')))
  $decimal = 0;
  @else
  $decimal = 2;
  @endif

  function toFixed(number, decimals) {
        var x = Math.pow(10, Number(decimals) + 1);
        return (Number(number) + (1 / x)).toFixed(decimals);
      }

  $('input[name=payment_gateway]').on('click', function() {

    var valueOriginal = $('#onlyNumber').val();
    var value = parseFloat($('#onlyNumber').val());
    var element = $(this).val();
        // Exibe o valor em um alerta
	  if(element == 'gpo'){
		   $('#telephone').show();
		   $('#addWalletBtn').show();
		  $('#addFundsBtn').hide();
		  $('#mcx_submit_link').hide();
		  $('#result').hide();
	  }else if(element == 'Multicaixa'){
		   $('#addWalletBtn').hide();
		   $('#telephone').hide();
		   $('#addFundsBtn').hide();
		    $('#mcx_submit_link').show()
		   $('#result').show();
	  }else{
		   $('#addWalletBtn').hide();
		   $('#telephone').hide();
		   $('#addFundsBtn').show();
		    $('#mcx_submit_link').hide()
		   $('#result').hide();
	  }
    //==== Start Taxes
    var taxes = $('span.isTaxableWallet').length;
    var totalTax = 0;

    if (valueOriginal.length == 0
				|| valueOriginal == ''
				|| value < {{ $settings->min_deposits_amount }}
				|| value > {{$settings->max_deposits_amount}}
      ) {
        // Reset
  			for (var i = 1; i <= taxes; i++) {
  				$('.percentageTax'+i).html('0');
  			}
        $('#handlingFee, #total, #total2').html('0');
      } else {
        // Taxes
        for (var i = 1; i <= taxes; i++) {
          var percentage = $('.percentageAppliedTaxWallet'+i).attr('data');
          var valueFinal = (value * percentage / 100);
          $('.percentageTax'+i).html(toFixed(valueFinal, $decimal));
          totalTax += valueFinal;
        }
        var totalTaxes = (Math.round(totalTax * 100) / 100).toFixed(2);
      }
      //==== End Taxes

    if (element != ''
        && value <= {{ $settings->max_deposits_amount }}
        && value >= {{ $settings->min_deposits_amount }}
        && valueOriginal != ''
      ) {
      // Fees
      switch (element) {
        @foreach (PaymentGateways::where('enabled', '1')->get(); as $payment)
        case '{{$payment->name}}':
          $fee   = {{$payment->fee}};
          $cents =  {{$payment->fee_cents}};
          break;
        @endforeach
      }

      var amount = (value * $fee / 100) + $cents;
      var amountFinal = toFixed(amount, $decimal);

      var total = (parseFloat(value) + parseFloat(amountFinal) + parseFloat(totalTaxes));

      if (valueOriginal.length != 0
  				|| valueOriginal != ''
  				|| value >= {{ $settings->min_deposits_amount }}
  				|| value <= {{$settings->max_deposits_amount}}
        ) {
        $('#handlingFee').html(amountFinal);
        $('#total, #total2').html(total.toFixed($decimal));
      }
    }

});

//<-------- * TRIM * ----------->

$('#onlyNumber').on('keyup', function() {

    var valueOriginal = $(this).val();
    var value = parseFloat($(this).val());
    var paymentGateway = $('input[name=payment_gateway]:checked').val();

    if (value > {{ $settings->max_deposits_amount }} || valueOriginal.length == 0) {
      $('#handlingFee').html('0');
      $('#total, #total2').html('0');
    }

    //==== Start Taxes
    var taxes = $('span.isTaxableWallet').length;
    var totalTax = 0;

    if (valueOriginal.length == 0
				|| valueOriginal == ''
				|| value < {{ $settings->min_deposits_amount }}
				|| value > {{$settings->max_deposits_amount}}
      ) {
        // Reset
  			for (var i = 1; i <= taxes; i++) {
  				$('.percentageTax'+i).html('0');
  			}
        $('#handlingFee, #total, #total2').html('0');
      } else {
        // Taxes
        for (var i = 1; i <= taxes; i++) {
          var percentage = $('.percentageAppliedTaxWallet'+i).attr('data');
          var valueFinal = (value * percentage / 100);
          $('.percentageTax'+i).html(toFixed(valueFinal, $decimal));
          totalTax += valueFinal;
        }
        var totalTaxes = (Math.round(totalTax * 100) / 100).toFixed(2);
      }
      //==== End Taxes

    if (paymentGateway
        && value <= {{ $settings->max_deposits_amount }}
        && value >= {{ $settings->min_deposits_amount }}
        && valueOriginal != ''
      ) {

      switch(paymentGateway) {
        @foreach (PaymentGateways::where('enabled', '1')->get(); as $payment)
        case '{{$payment->name}}':
          $fee   = {{$payment->fee}};
          $cents =  {{$payment->fee_cents}};
          break;
        @endforeach
      }

      var amount = (value * $fee / 100) + $cents;
      var amountFinal = toFixed(amount, $decimal);

      var total = (parseFloat(value) + parseFloat(amountFinal) + parseFloat(totalTaxes));

      if (valueOriginal.length != 0
  				|| valueOriginal != ''
  				|| value >= {{ $settings->min_deposits_amount }}
  				|| value <= {{$settings->max_deposits_amount}}
        ) {
        $('#handlingFee').html(amountFinal);
        $('#total, #total2').html(total.toFixed($decimal));
      } else {
        $('#handlingFee, #total, #total2').html('0');
        }
    }
});

@if (session('payment_process'))
   swal({
     html:true,
     title: "{{ __('general.congratulations') }}",
     text: "{!! __('general.payment_process_wallet') !!}",
     type: "success",
     confirmButtonText: "{{ __('users.ok') }}"
     });
  @endif

 $(document).ready(function () {
    $('#addWalletBtn').on('click', function () {
        Start_GPO_Notification();
    });

    function Start_GPO_Notification() {
        const amount = parseFloat($('#onlyNumber').val());
        const phone = $('#phone').val().trim();

        if (phone.length !== 9) {
            showError("O número de telefone deve ter 9 dígitos.");
            return;
        }
        if (isNaN(amount) || amount < 1999) {
            showError("O montante mínimo é 2.000 KZ");
            return;
        }

        sendmcxNotification();
    }

    function sendmcxNotification() {
        const link = $('#addWalletBtn');
        link.html("Processando...").prop('disabled', true);

        const formData = {
            _token: "{{ csrf_token() }}",
            amount: parseFloat($('#onlyNumber').val()),
            phone: $('#phone').val().trim(),
        };

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/gpo/init",
            data: formData,
            success: function (response) {
                if (response.success) {
					
                    getPaymentData("/gpo/finish", response); 
                } else {
                    showError(response.error || "Erro ao processar a transação.");
                    link.html("Continuar").prop('disabled', false); 
                }
            },
            error: function (xhr, statusText, error) {
                showError("Erro ao conectar ao servidor. Tente novamente.");
                link.html("Continuar").prop('disabled', false);
            },
        });
    }

    function getPaymentData(url_path, payInfo) {
        const div = $('#multicaixaMessage');
        div.show(); 
      $('#multicaixaAlert').show();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: url_path,
            data: {
                _token: "{{ csrf_token() }}",
                 merchantTransactionId: payInfo.merchantTransactionId,
				phone: payInfo.phone,
				amount: payInfo.amount,
            },
            success: function (result) {
				
                if (result.success) {
                    setTimeout(() => {
                        window.location.href = "/my/wallet";
                    }, 2000);
                } else {
                    showError(result.error || "Erro ao finalizar o pagamento.");
                }
            },
            error: function (xhr, statusText, error) {
                showError("Tempo de aceitação de pagamento expirado!");
            },
            complete: function () {
                $('#addWalletBtn').html("Continuar").prop('disabled', false);
                div.hide(); 
				 $('#multicaixaAlert').hide();
            },
        });
    }
	    function showError(message) {
        const errorDiv = $('#errorAddFunds');
        const errorList = $('#showErrorsFunds');

        errorList.empty(); 
        errorList.append(`<li>${message}</li>`);
        errorDiv.show(); 

        setTimeout(() => {
            errorDiv.hide();
        }, 5000);
    }
}); 
	
	function GenerateReference() {
    const amount = parseFloat($('#onlyNumber').val());
    const resultDiv = $('#result'); 
    const link = $('#mcx_submit_link'); 

    if (!amount || amount < 1999) {
        showError("O montante mínimo é 2.000 KZ");
        return;
    }

    link.html("Processando...").prop('disabled', true);

    const formData = {
        _token: "{{ csrf_token() }}", 
        amount: amount, 
    };

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/mcx/init",
        data: formData,
        success: function (response) {
            if (response.success) {
							resultDiv.css({
				'display': 'block',
				'padding': '20px',
				'border-radius': '10px',
				'border': '1px dashed #ccc',
				'transition': 'all 0.3s ease'
			});
                const options = { weekday: 'short', day: '2-digit', month: 'short' };
                const formattedDate = new Intl.DateTimeFormat('pt-BR', options).format(new Date(response.dueDate));

                resultDiv.html(`
                    <p style="
                        margin: 8px 0; 
                        font-size: 14px; 
                        line-height: 1.4; 
                        color: #333;">
                        <strong style="color: #000;">Entidade:</strong> ${response.entity}
                    </p>
                    <p style="
                        margin: 8px 0; 
                        font-size: 14px; 
                        line-height: 1.4; 
                        color: #333;">
                        <strong style="color: #000;">Referência:</strong> ${response.referenceNumber}
                    </p>
                    <p style="
                        margin: 8px 0; 
                        font-size: 14px; 
                        line-height: 1.4; 
                        color: #333;">
                        <strong style="color: #000;">Montante:</strong> ${response.amount} KZ
                    </p>
                    <p style="
                        margin: 8px 0; 
                        font-size: 14px; 
                        line-height: 1.4; 
                        color: #333;">
                        <strong style="color: #000;">Validade:</strong> ${formattedDate}
                    </p>
                `);
            } else {
                showError(response.error || "Erro ao gerar a referência.");
            }
        },
        error: function (xhr) {
            showError("Erro ao conectar ao servidor. Tente novamente.");
        },
        complete: function () {
            link.html("Gerar referência").prop('disabled', false);
        }
    });
}
function showError(message) {
    const errorDiv = $('#errorAddFunds');
    const errorList = $('#showErrorsFunds');

    if (errorDiv.length && errorList.length) {
        errorList.html(`<li>${message}</li>`); 
        errorDiv.css('display', 'block'); 
        setTimeout(() => {
            errorDiv.css('display', 'none');
        }, 5000);
    }
}
	
	
	
</script>
@endsection
