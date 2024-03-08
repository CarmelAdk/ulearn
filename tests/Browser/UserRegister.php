<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserRegister extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('first_name', 'Jane')
                    ->type('last_name', 'Doe')
                    ->type('email', 'janedoe@example.com')
                    ->type('password', 'secret')
                    ->type('password_confirmation', 'secret')
                    ->click('button[type="submit"]');

            $browser->visit('/')
                    ->assertSee('Jane');
        });
    }
}
