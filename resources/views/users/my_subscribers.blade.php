@extends('layouts.app')

@section('title') {{__('users.my_subscribers')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">
			
			  <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"> {{__('users.my_subscribers')}}</h2>
        </div>

          @if ($subscriptions->count() != 0)
          <div style="border: 1px solid #dbdbdb!important;border-radius: 0px!important;" class="card">
          <div class="table-responsive">
            <table class="table table-striped m-0">
              <thead>
                <tr>
                  <th scope="col">{{__('general.subscriber')}}</th>
                  <th scope="col">{{__('admin.date')}}</th>
                  <th scope="col">{{__('general.interval')}}</th>
                  <th scope="col">{{ __('admin.ends_at') }}</th>
                  <th scope="col">{{__('admin.status')}}</th>
                </tr>
              </thead>

              <tbody>

                @foreach ($subscriptions as $subscription)
                  <tr>
                    <td>
                      @if (! isset($subscription->subscriber->username))
                        {{ __('general.no_available') }}
                      @else
                      <a style="color:black!important;" href="{{url($subscription->subscriber->username)}}" class="mr-1">
                        <img src="{{Helper::getFile(config('path.avatar').$subscription->subscriber->avatar)}}" width="40" height="40" class="rounded-circle mr-2"> {{$subscription->subscriber->hide_name == 'yes' ? $subscription->subscriber->username : $subscription->subscriber->name}} </a>

                      <a href="{{url('messages/'.$subscription->subscriber->id, $subscription->subscriber->username)}}" title="{{__('general.message')}}"> <i class="feather icon-message-circle mr-1 mr-lg-0"></i>
                      </a>
                      @endif
                    </td>
                    <td>{{Helper::formatDate($subscription->created_at)}}</td>
                    <td>{{ $subscription->free == 'yes'? __('general.not_applicable') : __('general.'.$subscription->interval)}}</td>
                <td>
                      @if ($subscription->ends_at)
                    {{Helper::formatDate($subscription->ends_at)}}
                  @elseif ($subscription->free == 'yes')
                    {{ __('general.free_subscription') }}
                  @else
                  {{ __('general.no_available') }}
                  @endif
                </td>

                    <td>
                      @if ($subscription->stripe_id == ''
                        && strtotime($subscription->ends_at) > strtotime(now()->format('Y-m-d H:i:s'))
                        && $subscription->cancelled == 'no'
                          || $subscription->stripe_id != '' && $subscription->stripe_status == 'active'
                          || $subscription->stripe_id == '' && $subscription->free == 'yes'
                        )
                        <span>{{__('general.active2')}}</span>
                      @elseif ($subscription->stripe_id != '' && $subscription->stripe_status == 'incomplete')
                        <span>{{__('general.incomplete')}}</span>
                      @else
                        <span>{{__('general.cancelled')}}</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          </div><!-- card -->

          @if ($subscriptions->hasPages())
  			    	{{ $subscriptions->links() }}
  			    	@endif

        @else
          <div class="my-5 text-center">
            <span class="btn-block mb-3">
              <i class="feather icon-users ico-no-result"></i>
            </span>
            <h4 class="font-weight-light">{{__('users.not_subscribers')}}</h4>
          </div>
        @endif
        </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>
@endsection
