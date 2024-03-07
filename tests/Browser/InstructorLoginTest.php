<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class InstructorLoginTest extends DuskTestCase
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
          
            $browser->type('email', 'instructor@ulearn.com')
                    ->type('password', 'secret')
                    ->click('button[type="submit"]');

            $browser->assertPathIs('/instructor-dashboard');
        });
    }
}
