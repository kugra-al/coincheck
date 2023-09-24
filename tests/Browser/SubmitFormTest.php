<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Str;

class SubmitFormTest extends DuskTestCase
{
    public function testSubmitConvertForm()
    {   
        $this->browse(function ($browser) {
            $selector = "#result";
            $browser->visit('/');
            $oldValue = $browser->value($selector);
            $browser->type('cryptoQty', '10')
                ->press('Convert')
                ->pause(1000)
                ->assertInputValueIsNot($selector, $oldValue);
        });     
    }

    public function testDateUpdateOnSubmitForm()
    {
        $this->browse(function ($browser) {
            $selector = "#fetchTime";
            $browser->visit('/');
            $oldValue = $browser->value($selector);
            $browser->type('cryptoQty', '10')
                ->press('Convert')
                ->pause(2000)
                ->assertInputValueIsNot($selector, $oldValue);
        });     
    }

    public function testInputIsNumber()
    {
        $this->browse(function ($browser) {
            $selector = "#result";
            $browser->visit('/');
            $browser->type('cryptoQty', Str::random(10))
                ->press('Convert')
                ->pause(1000)
                ->assertInputValueIsNot($selector, '$NaN');
        });
    }

    public function testInputIsNotEmpty()
    {
        $this->browse(function ($browser) {
            $selector = "#result";
            $browser->visit('/');
            $browser->type('cryptoQty', '')
                ->press('Convert')
                ->pause(1000)
                ->assertInputValueIsNot($selector, '$NaN');
        });        
    }
}
