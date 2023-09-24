<div class="container fetchprices-form-container">
	<div class="form-container-inner">
		<form method="POST" action="/fetchPrices" id="convertForm">
			@csrf
			<div class="row">
				<div class="col">
					<input type="text" class="form-control form-control-xlg" id="cryptoQty" name="cryptoQty" value="1">
				</div>
				<div class="col">
					<select id="inputCrypto" name="inputCrypto" class="form-control form-control-xlg selectpicker dropdown" data-size="5" data-live-search="true">
					@foreach($crypto->currency as $currency)
						<option value="{{ $currency->code }}" title="{{ $currency->code }}">{{ $currency->code }} - {{ $currency->name }}</option>
					@endforeach
					</select>
				</div>
				<div class="col">
					<span class="equals">=</span>
				</div>
				<div class="col">
					<input type="text" value="" class="form-control form-control-xlg" id="result">
				</div>
				<div class="col">
					<select id="inputFiat" name="inputFiat" class="form-control form-control-xlg selectpicker dropdown" data-size="5" data-live-search="true">
					@foreach($fiat->currency as $currency)
						<option value="{{ $currency->code }}" title="{{ $currency->code }}">{{ $currency->code }} - {{ $currency->name }}</option>
					@endforeach
					</select>
				</div>
			</div>
		</form>
		<div class="singleRate">
			1 <span id="fromCurrency">BTC</span> = <span id="updateRate">1</span> <span id="toCurrency">USD</span>
		</div>
	</div>
</div>
<div class="row coinstats-stats">
	<div class="container">
		<div class="col text-left">
			<a href="/currencies?type=crypto">{{ $crypto->currency->count() }} cryptocurrencies supported</a>
		</div>
		<div class="col text-left">
			<a href="/currencies?type=fiat">{{ $fiat->currency->count() }} fiat currencies supported</a>
		</div>
	</div>
</div>
<div class="row fetchprices-stats">
	<div class="container">
		<div class="col text-right">
			<div class="loader">updating</div>
		</div>
		<div class="col text-right">
			<strong class="text-left">CoinGecko Price Updated At:</strong> <span id="updateTime">0000-00-00 00:00:00 UTC</span>
		</div>
		<div class="col text-right">
			<strong class="text-left">Price Last Checked At:</strong> <span id="fetchTime">0000-00-00 00:00:00 UTC</span>
		</div>
	</div>
</div>

