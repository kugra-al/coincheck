<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\CurrencyRate;
use App\Models\Currencies;

class PriceUpdateEvent implements ShouldBroadcast, ShouldQueue
{
	use Dispatchable, InteractsWithSockets, SerializesModels;
	public $message;
	public $count;
	/**
	* Create a new event instance.
	*
	* @return void
	*/
	public function __construct()
	{
		$this->content =  '';
		$this->count = 1;
	}

	/**
	* Get the channels the event should broadcast on.
	*
	* @return \Illuminate\Broadcasting\Channel|array
	*/
	public function broadcastOn()
	{
	    return new Channel('priceUpdates');
	}

	public function broadcastWith()
	{
		$rates = CurrencyRate::getRates();
	    return ['content' => ['rates' => $rates]];
	}
}
