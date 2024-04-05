@extends('admin.layouts.master')
@section('title',__('Dashboard'))
@section('content')

<div class="row gy-4">
  <div class="col-lg-3">
    <div class="s7__widget-three">
      <div class="content">
        <p class="mb-2">{{__('Total Users')}}</p>
        <h3 class="mb-0">{{$total_user}}</h3>
      </div>
      <div class="icon s7__bg-primary rounded-circle">
        <i class="las la-users"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3">
    <div class="s7__widget-three">
      <div class="content">
        <p class="mb-2">{{__('Active Users')}}</p>
        <h3 class="mb-0">{{$total_ac_user}}</h3>
      </div>
      <div class="icon s7__bg-primary rounded-circle">
        <i class="las la-user-plus"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3">
    <div class="s7__widget-three">
      <div class="content">
        <p class="mb-2">{{__('Banned Users')}}</p>
        <h3 class="mb-0">{{$total_bn_user}}</h3>
      </div>
      <div class="icon s7__bg-primary rounded-circle">
        <i class="las la-user-times"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3">
    <div class="s7__widget-three">
      <div class="content">
        <p class="mb-2">{{__('Today Join User')}}</p>
        <h3 class="mb-0">{{$userRecord['todayJoin']}}</h3>
      </div>
      <div class="icon s7__bg-primary rounded-circle">
        <i class="las la-user"></i>
      </div>
    </div>
  </div>
</div>

<div class="row gy-4 mt-3">


  <div class="col-lg-3">
    <div class="s7__widget-three">
      <div class="content">
        <p class="mb-2">{{__('Total Invest')}}</p>
        <h3 class="mb-0">{{round($total_invest,2)}} {{$general->currency}}</h3>
      </div>
      <div class="icon s7__bg-primary rounded-circle">
        <i class="las la-coins"></i>
      </div>
    </div>
  </div>


  <div class="col-lg-3">
    <div class="s7__widget-three">
      <div class="content">
        <p class="mb-2">{{__('Total Deposit')}}</p>
        <h3 class="mb-0">{{round($total_deposit,2)}} {{$general->currency}}</h3>
      </div>
      <div class="icon s7__bg-primary rounded-circle">
        <i class="las la-coins"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-3">
    <div class="s7__widget-three">
      <div class="content">
        <p class="mb-2">{{__('Today Withdraw')}}</p>
        <h3 class="mb-0">{{round($today_withdraw,2)}} {{$general->currency}}</h3>
      </div>
      <div class="icon s7__bg-primary rounded-circle">
        <i class="las la-money-bill-wave"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-3">
    <div class="s7__widget-three">
      <div class="content">
        <p class="mb-2">{{__('Total Withdraw')}}</p>
        <h3 class="mb-0">{{round($total_withdraw,2)}} {{$general->currency}}</h3>
      </div>
      <div class="icon s7__bg-primary rounded-circle">
        <i class="las la-hand-holding-usd"></i>
      </div>
    </div>
  </div>

</div>



{{-- <div class="row gy-4 mt-3">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0 py-1 text-uppercase">{{__('Invest ROI Plans')}}</h6>
      </div>
      <div class="card-body">

        <div class="sales-region">
          @foreach($roi_plans as $key => $data)
          <div class="single-region mt-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
              <h6 class="text-small">{{$data->name}}</h6>
              <b class="text-dark text-small">{{$data->period}} @lang('Times')</b>
            </div>
            <div class="progress">
              <div class="progress-bar progress-bar-striped bc-{{$key+1}}" role="progressbar" style="width: {{$data->percent}}%;" aria-valuenow="{{$data->percent}}"
                aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0 py-1 text-uppercase">{{__('Invest Fixed Plans')}}</h6>
      </div>
      <div class="card-body">

        <div class="sales-region">
          @foreach($fixed_plans as $key => $data)
          <div class="single-region mt-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
              <h6 class="text-small">{{$data->name}}</h6>
              <b class="text-dark text-small">{{($data->fixed_amount*$data->percent)/100}}{{$general->currency}}</b>
            </div>
            <div class="progress">
              <div class="progress-bar progress-bar-striped bc-{{$key+1}}" role="progressbar" style="width: {{$data->percent}}%;" aria-valuenow="{{$data->percent}}"
                aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div> --}}

<div class="row gy-4 mt-2">
  <div class="col-xxl-3 col-sm-6">
    <div class="s7__widget-two">
      <div class="icon">
        <i class="las la-hand-holding-usd"></i>
      </div>
      <div class="content">
        <p class="caption">{{__('This Month Invest')}}</p>
        <h3 class="amount">{{round($total_invest_month,2)}} {{$general->currency}}</h3>
      </div>
    </div>
  </div>
  <div class="col-xxl-3 col-sm-6">
    <div class="s7__widget-two">
      <div class="icon">
        <i class="las la-hand-holding-usd"></i>
      </div>
      <div class="content">
        <p class="caption">{{__('This Month Deposit')}}</p>
        <h3 class="amount">{{round($total_deposit_month,2)}} {{$general->currency}}</h3>
      </div>
    </div>
  </div>
  <div class="col-xxl-3 col-sm-6">
    <div class="s7__widget-two">
      <div class="icon">
        <i class="las la-hand-holding-usd"></i>
      </div>
      <div class="content">
        <p class="caption">{{__('This Month Withdraw')}}</p>
        <h3 class="amount">{{round($total_withdraw_month,2)}} {{$general->currency}}</h3>
      </div>
    </div>
  </div>
  <div class="col-xxl-3 col-sm-6">
    <div class="s7__widget-two">
      <div class="icon">
        <i class="las la-hand-holding-usd"></i>
      </div>
      <div class="content">
        <p class="caption">{{__('This Month Profit')}}</p>
        <h3 class="amount">{{round($month_earn,2)}} {{$general->currency}}</h3>
      </div>
    </div>
  </div>
</div>

<div class="card mt-5">
  <div class="card-header border-0">
      <div class="d-flex flex-wrap justify-content-between align-items-center">
          <h6 class="text-uppercase mb-0">{{__('Latest User')}}</h6>
      </div>
  </div>
  <div class="card-body p-0">
    <table class="table s7__table">
        <thead>
            <tr>
              <th>{{__('Name')}}</th>
              <th>{{__('Email')}}</th>
              <th>{{__('Balance')}}</th>
              <th>{{__('Status')}}</th>
              <th>{{__('Action')}}</th>
            </tr>
        </thead>
        <tbody>
          @foreach($latestUser as $data)
          <tr>
            <td data-caption="customer">
              <div class="content">
                <h6 class="text-small mb-0">@lang($data->name)</h6>
              </div>
            </td>
            <td data-caption="Product">@lang($data->email)</td>
            <td data-caption="Date">{{getAmount($data->balance)}} {{$general->currency}}</td>
            <td data-caption="Status">
              @if($data->status == 1)
                  <span class="s7__badge s7__badge-success">@lang('Active')</span>
              @else
                  <span class="s7__badge s7__badge-danger">@lang('Inactive')</span>
              @endif
            </td>
            <td data-caption="Action">
              <a href="{{route('user.view', $data->id)}}" class="table-icon s7__text-secondary"><i class="las la-edit"></i></a>
            </td>
          </tr>
          @endforeach
        </tbody>
    </table>
  </div>
</div>

@endsection

@section('script')

<script>

var options = {
    series: [{
      name: "Total Investment",
      data: [
        @foreach ($months as $totalInvestment)
            {{ @$investMonth->where('months', $totalInvestment)->first()->amount ?? 0 }},
        @endforeach
      ]
  }, {
    name: 'Total Interest',
    data: [
      @foreach ($months as $totalInterest)
          {{ @$interestMonth->where('months', $totalInterest)->first()->amount ?? 0 }},
      @endforeach
    ]
  }],
    chart: {
    height: 350,
    type: 'line',
    zoom: {
      enabled: false
    }
  },
  dataLabels: {
    enabled: false
  },
  stroke: {
    curve: 'straight'
  },
  title: {
    text: 'Plan Statistics (Last 12 Month)',
    align: 'left'
  },
  grid: {
    row: {
      colors: ['#f3f3f3', 'transparent'],
      opacity: 0.5
    },
  },
  xaxis: {
    categories:  @json($months),
  }
};

  var chart = new ApexCharts(document.querySelector("#chart"), options);
  chart.render();


  var options = {
      series: [{
      name: 'Total Deposit',
      data: [
        @foreach ($months as $depositMonth)
            {{ getAmount(@$depositsMonth->where('months', $depositMonth)->first()->depositAmount) }},
        @endforeach
        ]
    }, {
      name: 'Total Withdraw',
      data: [
        @foreach ($months as $withdrawMonth)
            {{ getAmount(@$withdrawalMonth->where('months', $withdrawMonth)->first()->withdrawAmount) }},
        @endforeach
      ]
    }],
      chart: {
      type: 'bar',
      height: 350,
      toolbar: {
          show: false
      }
    },
    title: {
      text: 'Monthly Deposit & Withdraw Report (Last 12 Month)',
      align: 'left'
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '55%',
        endingShape: 'rounded'
      },
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      show: true,
      width: 2,
      colors: ['transparent']
    },
    xaxis: {
      categories: @json($months),
    },
    fill: {
      opacity: 1
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return "{{ __($general->currency) }} " + val + " "
        }
      }
    }
  };

  var chart3 = new ApexCharts(document.querySelector("#chart3"), options);
  chart3.render();

</script>
@stop
