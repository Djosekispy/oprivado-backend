@extends('layouts.app')

@section('title') {{trans('general.withdrawals')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
       
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">
			
			 <div class=" py-5">
          <h2 class="mb-0 font-montserrat">{{trans('general.withdrawals')}}</h2>

        </div>

          @include('errors.errors-forms')

        @if (auth()->user()->payment_gateway == '')
          <div class="alert alert-primary alert-dismissible" role="alert">
          <span class="alert-inner--text"><i class="fa fa-info-circle mr-1"></i> Por favor, adicione uma
            <a href="{{url('settings/payout/method')}}" class="link-border">conta bancária</a>
          </span>
        </div>
        @endif

            <div class="row">
              <div class="col-md-12">

                @php
                  $datePaid = Withdrawals::select('date')
                      ->where('user_id', auth()->user()->id)
                      ->where('status','pending')
                      ->orderBy('id','desc')
                      ->first();
                @endphp

                <div class="alert alert-primary overflow-hidden" role="alert">

                  <div class="inner-wrap">
                    <h2>
                      <strong>{{Helper::amountFormatDecimal(auth()->user()->balance)}}</strong> 
                    </h2>
					  <span class="w-100 d-block">Ganhos disponíveis</span>
                  </div>
			</div> <p class="help-block margin-bottom-zero fee-wrap">
				  
				  @if (! $datePaid)
                      <span>
                        {{trans('general.amount_min_withdrawal')}} <strong>10.000 KZ</strong> </span>   
                  @endif
				  
				  <span class="btn-block w-100">
                    @if ($datePaid)
                    @if (! $settings->specific_day_payment_withdrawals)
                      {{trans('users.date_paid')}} <strong>{{Helper::formatDate(Carbon\Carbon::parse($datePaid->date)->addDays($settings->days_process_withdrawals))}}</strong>

                    @else
                      {{ trans('users.date_paid') }} <strong>{{ Helper::formatDate(Helper::paymentDateOfEachMonth($settings->specific_day_payment_withdrawals)) }}</strong>
                    @endif
                  @endif
                  </span>
				  
				  <span class="btn-block none w-100">
                    @if (! $settings->specific_day_payment_withdrawals)
                      * {{ trans('general.payment_process_days', ['days' => $settings->days_process_withdrawals]) }}

                    @elseif (! $datePaid)
                      * {{ trans('users.date_paid') }} {{ Helper::formatDate(Helper::paymentDateOfEachMonth($settings->specific_day_payment_withdrawals)) }}
                    @endif
                  </span>
              </p>

                <div>

                  @if (auth()->user()->balance >= $settings->amount_min_withdrawal
                      && auth()->user()->payment_gateway != ''
                      && auth()->user()->withdrawals()
                      ->where('status','pending')
                      ->count() == 0)

                  {!! Form::open([
                   'method' => 'POST',
                   'url' => "settings/withdrawals",
                   'class' => 'd-inline'
                 ]) !!}

                 @if ($settings->type_withdrawals == 'custom')
                   <div class="form-group mt-4">
					   <label>Montante</label>
                     <div class="input-group mb-2">
                     <div class="input-group-prepend">
                       <span class="input-group-text">{{$settings->currency_symbol}}</span>
                     </div>
                         <input class="form-control form-control-lg isNumber" id="onlyNumber" autocomplete="off" name="amount" placeholder="" type="text">
                     </div>
                   </div><!-- End form-group -->
                 @endif

                  {!! Form::submit(trans('general.make_withdrawal'), ['class' => 'btn btn-primary btn-block mb-2 saveChanges']) !!}
                  {!! Form::close() !!}
                @else
                  <button class="btn btn-1 btn-block btn-primary mb-2 disabled e-none">{{trans('general.make_withdrawal')}}</button>
                @endif

                </div>

              </div><!-- col-md-12 -->
            </div>

          @if ($withdrawals->count() != 0)
			<label class="mt-5">Últimos levantamentos</label>
          <div style="border: 1px solid #dbdbdb!important;border-radius: 0px!important;" class="card">
          <div class="table-responsive">
            <table class="table table-striped m-0">
              <thead>
                <tr>
                  <th scope="col">{{trans('admin.amount')}}</th>
                  <th class="none" scope="col">{{trans('admin.method')}}</th>
                  <th scope="col">{{trans('admin.date')}}</th>
                  <th scope="col">{{trans('admin.status')}}</th>
                  <th scope="col">Descrição</th>
                </tr>
              </thead>

              <tbody>

                @foreach ($withdrawals as $withdrawal)
                  <tr>
                    <td>{{Helper::amountFormatDecimal($withdrawal->amount)}}</td>
                    <td class="none">Transferência báncaria</td>
                    <td>{{Helper::formatDate($withdrawal->date)}}
                    </td>
                    <td>@if ( $withdrawal->status == 'paid' )
                    <span>{{trans('general.paid')}}</span>
					 @elseif ($withdrawal->status == 'rejected')
                    <span >Rejeitado</span>
                    @else
                    <span>Em revisão</span>
                    @endif
                  </td>
                    <td>

                  @if ($withdrawal->status == 'pending' && Carbon\Carbon::parse($withdrawal->estimated_payment)->shortAbsoluteDiffForHumans() <> '5d')
						{!! Form::open([
                        'method' => 'POST',
                        'url' => "delete/withdrawal/$withdrawal->id",
                        'class' => 'd-inline'
                      ]) !!}

                      {!! Form::button(trans('general.cancel'), ['class' => 'btn btn-primary btn-sm deleteW p-1 px-2']) !!}
                      {!! Form::close() !!}

                  @elseif ($withdrawal->status == 'pending' && Carbon\Carbon::parse($withdrawal->estimated_payment)->shortAbsoluteDiffForHumans() == '5d')

                    {{ trans('general.in_process') }}
                  @else
					 @if ($withdrawal->status == 'rejected')
					  <span>{{$withdrawal->rejection_reason}}</span>
					  @else

                  {{trans('general.paid')}} em {{Helper::formatDate($withdrawal->date_paid)}}
					 @endif
						
                  @endif
                  </td>
                </tr>
                @endforeach

              </tbody>
            </table>
          </div>
          </div><!-- card -->

          @if ($withdrawals->hasPages())
            {{ $withdrawals->links() }}
          @endif

        @endif
        </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>
@endsection
