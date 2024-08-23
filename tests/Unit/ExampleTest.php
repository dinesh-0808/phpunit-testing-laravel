<?php

function sum($a, $b) // Add parameter types
{
    return $a + $b; // Add $ before variable names
}


describe('sum', function() {
    it('may sum integers', function(){
        $result = sum(1, 2);
        expect($result)->toBe(3);
    });

    it('may sum floats', function(){
        $result = sum(1.5, 1.5);
        expect($result)->toBe(3.0);
    });
});

$email = ['enunomaduro@gmail.com', 'other@example.com'];

it('has emails', function (string $email) {
    expect($email)->not->toBeEmpty();
})->with($email);
