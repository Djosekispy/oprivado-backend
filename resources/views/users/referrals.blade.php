@extends('layouts.app')

@section('title') {{__('general.referrals')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
		  
		    <div class="row">

        @include('includes.cards-settings')
				
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat">Afiliados (Ganhe dinheiro convidando pessoas)</h2>

          @if ($settings->referral_system == 'on')

            <p class="text-muted mt-4">
              {{__('general.referrals_welcome_desc', ['percentage' => $settings->percentage_referred])}}
            </p>

            <button class="d-none copy-url" id="copyLink" data-clipboard-text="{{ url(auth()->user()->username.'?ref='.auth()->user()->id) }}"></button>
            <div class="fee-wrap">
              <span class="text-muted">{{ __('general.your_referral_link') }}</span>

              <span class="text-break"><strong>{{ url(auth()->user()->username.'?ref='.auth()->user()->id) }}</strong></span>

              <button class="btn btn-link e-none p-1 text-decoration-none" data-toggle="tooltip" data-placement="top" title="{{__('general.copy_link')}}" onclick="$('#copyLink').trigger('click')">
  							Copiar <i class="far fa-clone"></i>
  						</button>
            </div>
          @else
          <div class="alert alert-danger mt-3">
          <span class="alert-inner--text">
            <i class="fa fa-exclamation-triangle mr-1"></i> {{ __('general.referral_system_disabled') }}
          </span>
        </div>
          @endif
			
		 <div class="content mt-4">
            <div class="row">
              <div class="col-lg-3 mb-2">
                <div style="border: 1px solid #dbdbdb!important;
    border-radius: 4px !important;" class="card borda">
                  <div class="card-body">
                    <h4>
                      {{Helper::amountFormatDecimal(auth()->user()->balance)}}
                    </h4>
                    <small>{{ __('general.balance') }}</small>
                    @if (auth()->user()->balance >= $settings->amount_min_withdrawal)
                    <a style="
    font-size: 80%!important;
" href="{{ url('settings/withdrawals')}}" class="link-border color-link"> {{ __('general.make_withdrawal') }}</a>
                    @else
                    <a style="
    font-size: 80%!important;
" href="javascript:;" class="link-border color-link text-muted" data-toggle="tooltip" title="{{__('general.amount_min_withdrawal')}} {{Helper::amountWithoutFormat($settings->amount_min_withdrawal)}} {{$settings->currency_code}}">sacar</a>
                  @endif
                  </div>
                </div><!-- card 1 -->
              </div><!-- col-lg-4 -->

              <div class="col-lg-3 mb-2">
                <div style="border: 1px solid #dbdbdb!important;
    border-radius: 4px !important;" class="card">
                  <div class="card-body">
                    <h4>{{ number_format(auth()->user()->referrals()->count()) }}</h4>
                    <small>{{ __('general.total_registered_users') }}</small>
                  </div>
                </div><!-- card 1 -->
              </div><!-- col-lg-4 -->

              <div class="col-lg-3 mb-2">
                <div style="border: 1px solid #dbdbdb!important;
    border-radius: 4px !important;" class="card">
                  <div class="card-body">
                    <h4> {{ number_format(auth()->user()->referralTransactions()->count()) }}</h4>
                    <small>{{ __('general.total_transactions') }}</small>
                  </div>
                </div><!-- card 1 -->
              </div><!-- col-lg-4 -->

              <div class="col-lg-3 mb-2">
                <div style="border: 1px solid #dbdbdb!important;
    border-radius: 4px !important;" class="card">
                  <div class="card-body">
                    <h4> {{ Helper::amountFormatDecimal(auth()->user()->referralTransactions()->sum('earnings')) }}</h4>
                    <small>{{ __('general.earnings_total') }}</small>
                  </div>
                </div><!-- card 1 -->
              </div><!-- col-lg-4 -->

              <div class="col-lg-12 mt-3 py-4">
				  <h4 style="font-size: 18px;" class="mb-4">Últimas transações</h4>
                 <div style="border: 1px solid #dbdbdb!important;
    border-radius: 0px !important;" class="card">
                     <div class="table-responsive">
                       <table class="table table-striped m-0">
                         <thead>
                           <tr>
                             <th scope="col">{{__('admin.type')}}</th>
                             <th scope="col">{{__('admin.date')}}</th>
                             <th scope="col">{{__('general.earnings')}}</th>
                           </tr>
                         </thead>

                         <tbody>

                        @if ($transactions->count() != 0)
                           @foreach ($transactions as $referred)
                             <tr>
                               <td>{{ __('general.'.$referred->type) }}</td>
                               <td>{{ Helper::formatDate($referred->created_at) }}</td>
                               <td>{{ Helper::amountFormatDecimal($referred->earnings) }}</td>
                             </tr>
                           @endforeach

                         @else
                           <tr>
                             <td colspan="12" class="text-center">{{ __('general.no_transactions_yet') }}</td>
                           </tr>
                          @endif

                         </tbody>
                       </table>
                     </div>
                   
                 </div><!-- card -->

                 @if ($transactions->hasPages())
         			    	{{ $transactions->links() }}
         			    	@endif

              </div><!-- col-lg-12 -->

            </div><!-- end row -->
          </div> <!-- end content -->
			

        </div>
      </div>
      
    </div>
  </section>
@endsection
