@extends('layouts.default')

@section('content')
    @if(isset($currencies) && sizeof($currencies))
        <div class="row">
            <div class="container">
                <table class="table task-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                @foreach($currencies as $currency)
                    <tr>
                        <td>{{ $currency->code }}</td>
                        <td>{{ $currency->name }}</td>
                        <td>{{ $currency->type->name }}</td>
                    </tr>
                @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $currencies->links() }}
    @else
        No currencies found
    @endif
@endsection