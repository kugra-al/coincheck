let rates = [];
let currencies = [];

function formatCurrency (n, currency, decPlaces, thouSeparator, decSeparator) {
	if (n < 0.01)
		decPlaces = 8;
    var 
        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
        decSeparator = decSeparator == undefined ? "." : decSeparator,
        thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
        sign = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    if (currencies[currency])
    	currency = currencies[currency]['symbol'];
    return currency + sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};

function formatCrypto(n, currency) {
	if (currencies[currency])
    	currency = currencies[currency]['symbol'];
	return currency+n;
}

function updateResult()
{
	let cryptoQty = $('#cryptoQty').val();
	let cryptoSelect = $('#inputCrypto option:selected').val(); 	
	let fiatSelect = $('#inputFiat option:selected').val(); 

	if (isNaN(cryptoQty) || isNaN(parseFloat(cryptoQty))) {
		return;
	}
	if (rates && rates[cryptoSelect] && rates[cryptoSelect][fiatSelect]) {
		let rate = rates[cryptoSelect][fiatSelect];
		$('#result').val(formatCurrency( 
			parseFloat(cryptoQty)*
			parseFloat(rate['rate']) 
			,fiatSelect)
		);   	
		//$('#cryptoQty').val(formatCrypto(cryptoQty,cryptoSelect));
		$('#updateTime').text(rate['update_time']);
		$('#fetchTime').text(rate['fetch_time']);
		$('#updateRate').text(formatCurrency(rate['rate'],fiatSelect));
		$('#fromCurrency').text(cryptoSelect);
		if (cryptoSelect == "DOGE") {
			$('#fromCurrency').text(cryptoSelect+" = 1 DOGE");
		}
		//$('#toCurrency').text(fiatSelect);
	} else {
		$('#result').val("ERR");   	
	}
	$('.loader').text('Updated').fadeIn('slow').fadeOut('slow');
	resizeInputs();
	//console.log('update');
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
    }
});
function fetchRates()
{
	$('.loader').text('Updating...');
	$('.loader').show();
	$.ajax({
        type: 'POST',
        url: '/fetchPrices',
        data: [],
        dataType: 'json',
        success: function (data) {
            rates = data['content']['rates'];
            currencies = data['content']['currencies'];
            updateResult();
        },
        error: function (data) {
            console.log(data);
        }
    });
    console.log("fetched rates");
}


function resizeInputs() {
	$('#convertForm input').each(function(k,v){
		v.style.width = (v.value.length+3)+"ch";
	});
}

jQuery('document').ready(function(){

	Echo.channel('priceUpdates')
	    .listen('PriceUpdateEvent', (e) => {
	    	rates = e.content.rates;
	    	console.log("Echo update");
	    	updateResult();	        
	    });

	fetchRates();

	$('#convertForm').submit(function(e)
	{
		e.preventDefault();
		updateResult();
		//fetchRates();
	});

	$('#convertForm input').on('keyup', function(e){
		$('#convertForm').submit();
	});

	$('#convertForm input').on("click", function () {
   		$(this).select();
	});

	$('#convertForm select').on('change', function(e){
		$('#convertForm').submit();
	});

})

