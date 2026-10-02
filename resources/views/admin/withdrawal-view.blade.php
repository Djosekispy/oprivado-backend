@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      <a class="text-reset" href="{{ url('panel/admin/withdrawals') }}">{{ __('general.withdrawals') }}</a>
      <i class="bi-chevron-right me-1 fs-6"></i>
      #{{$data->id}}
  </h5>

<div class="content">
	<div class="row">

		<div class="col-lg-12">

			<div class="card shadow-custom border-0">
				<div class="card-body p-lg-5">

             <dl class="row">
              <dt class="col-sm-2 text-lg-end">{{ __('admin.user') }}</dt>
              <dd class="col-sm-10">
                @if (isset($data->user()->username))
                    <a href="{{ url($data->user()->username) }}" target="_blank">
                    {{ $data->user()->username }} <i class="bi-box-arrow-up-right"></i>
                  </a>
                        @else
                            {{ __('general.no_available') }}
                    @endif
                </dd>

              @if ($data->gateway == 'PayPal')
              <dt class="col-sm-2 text-lg-end">{{ __('admin.paypal_account') }}</dt>
              <dd class="col-sm-10">{{$data->account}}</dd>
              @elseif ($data->gateway == 'Payoneer')
              <dt class="col-sm-2 text-lg-end">{{ __('general.payoneer_account') }}</dt>
              <dd class="col-sm-10">{{$data->account}}</dd>
              @elseif ($data->gateway == 'Zelle')
              <dt class="col-sm-2 text-lg-end">{{ __('general.zelle_account') }}</dt>
              <dd class="col-sm-10">{{$data->account}}</dd>
              @elseif ($data->gateway == 'Western Union')
              <dt class="col-sm-2 text-lg-end">{{ __('auth.full_name') }}</dt>
              <dd class="col-sm-10">{{ $data->user()->name ?? __('general.no_available') }}</dd>
              <dt class="col-sm-2 text-lg-end">{{ __('general.country') }}</dt>
              <dd class="col-sm-10">{{ isset($data->user()->countries_id) != '' ? $data->user()->country()->country_name : __('general.no_available')}}</dd>
              <dt class="col-sm-2 text-lg-end">{{ __('general.document_id') }}</dt>
              <dd class="col-sm-10">{{$data->account}}</dd>
              @elseif ($data->gateway == 'Bitcoin')
              <dt class="col-sm-2 text-lg-end">{{ __('general.bitcoin_wallet') }}</dt>
              <dd class="col-sm-10">{{$data->account}}</dd>
              @elseif ($data->gateway == 'Mercado Pago')
              <dt class="col-sm-2 text-lg-end">Alias MP</dt>
              <dd class="col-sm-10">{{$data->account}}</dd>
              <dt class="col-sm-2 text-lg-end">No. CVU</dt>
              <dd class="col-sm-10">{{$data->user()->cvu ?? __('general.no_available') }}</dd>
              @else
              <dt class="col-sm-2 text-lg-end">{{ __('general.bank_details') }}</dt>
              <dd class="col-sm-10">{!!Helper::checkText($data->account)!!}</dd>
            @endif

            <dt class="col-sm-2 text-lg-end">{{ __('admin.amount') }}</dt>
            <dd class="col-sm-10">{{Helper::amountFormatDecimal($data->amount) }}</dd>

            <dt class="col-sm-2 text-lg-end">{{ __('admin.date') }}</dt>
            <dd class="col-sm-10">{{date('d M, Y', strtotime($data->date))}}</dd>

            <dt class="col-sm-2 text-lg-end">{{ __('admin.status') }}</dt>
				             <dd class="col-sm-10"><span class="badge bg-{{ $data->status == 'paid' ? 'success' : ($data->status == 'rejected' ? 'danger' : 'warning') }}">{{ $data->status == 'paid' ? __('general.paid') : ($data->status == 'rejected' ? 'Rejeitado' : __('general.pending_to_pay')) }}</span></dd>

            @if ($data->status == 'rejected')
            <dt class="col-sm-2 text-lg-end">Motivo da rejeição</dt>
            <dd class="col-sm-10">{{ $data->rejection_reason }}</dd>
            @endif

            @if ($data->status == 'paid')
            <dt class="col-sm-2 text-lg-end">{{ __('general.date_paid') }}</dt>
            <dd class="col-sm-10">{{date('d M, Y', strtotime($data->date_paid))}}</dd>
          @endif

            </dl>

            @if ($data->status == 'pending' && isset($data->user()->username))
    <div class="row mb-3">
        <div class="col-sm-10 offset-sm-2">
            <div class="d-flex gap-2 flex-wrap mt-3">

                {{-- Marcar como pago --}}
                <form method="POST" action="{{ url('panel/admin/withdrawals/paid', $data->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-success px-5">
                        {{ __('general.mark_paid') }}
                    </button>
                </form>

                {{-- Rejeitar pedido --}}
                <button type="button"
                        class="btn btn-outline-danger px-5"
                        data-bs-toggle="modal"
                        data-bs-target="#rejectWithdrawalModal">
                    Rejeitar pedido
                </button>

            </div>
        </div>
    </div>
            <div class="modal fade" id="rejectWithdrawalModal" tabindex="-1" aria-labelledby="rejectWithdrawalModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="rejectWithdrawalModalLabel">Rejeitar pedido de levantamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                  </div>
                  <form method="POST" action="{{ url('panel/admin/withdrawals/reject', $data->id) }}">
                    @csrf
                    <div class="modal-body">
                      <label for="rejectionReason" class="form-label">Motivo da rejeição</label>
                      <textarea class="form-control" id="rejectionReason" name="reason" rows="4" maxlength="1000" required></textarea>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                      <button type="submit" class="btn btn-danger">Confirmar rejeição</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          @endif

				 </div><!-- card-body -->
 			</div><!-- card  -->
 		</div><!-- col-lg-12 -->

	</div><!-- end row -->
</div><!-- end content -->
@endsection
