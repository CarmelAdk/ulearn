<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminCreateInstructor extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login');
            $browser->type('email', 'admin@ulearn.com')
                    ->type('password', 'secret')
                    ->click('button[type="submit"]');

            $browser->visit('/admin/user-form')
                    ->type('first_name', 'John')
                    ->type('last_name', 'Doe')
                    ->type('email', 'johndoe@example.com')
                    ->check('roles[]', 'instructor')
                    ->click('#inputBasicActive')
                    ->type('password', 'secret')
                    ->click('button[type="submit"]');

            $browser->visit('/admin/users')
                    ->assertSee('John Doe Active');
            
        });
    }
}
