<?php

use mrkrstphr\Hydrotron\Hydrotron;

describe(Hydrotron::class, function () {
    describe('when()', function () {
        it('should run the callbacks when the value exists', function () {
            $hydro = new Hydrotron(['foo' => 10]);
            $finalValue = null;

            $callback1 = function ($value) {
                return $value * 2;
            };

            $callback2 = function ($value) {
                return $value * 3;
            };

            $callback3 = function ($value) use (&$finalValue) {
                $finalValue = $value;
            };

            $hydro->when('foo', $callback1, $callback2, $callback3);

            expect($finalValue)->to->equal(60);
        });

        it('should not run the callbacks when the value does not exist', function () {
            $hydro = new Hydrotron([]);
            $value1 = null;

            $callback1 = function ($value) use (&$value1) {
                $value1 = $value;
            };
            $hydro->when('foo', $callback1);

            expect($value1)->to->be->null();
        });
    });

    describe('instantiateWhen()', function () {
        it('should return an instantiated object when the value exists', function () {
            $hydro = new Hydrotron(['boop' => ['foo' => 'bar', 'bizz' => 'buzz']]);
            $instance = null;

            $callback1 = function (MyClass $c) use (&$instance) {
                $instance = $c;
            };

            $hydro->instantiateWhen('boop', MyClass::class, $callback1);

            expect($instance)->to->be->instanceof(MyClass::class);
            expect($instance->foo)->to->equal('bar');
            expect($instance->bizz)->to->equal('buzz');
        });
    });
});

class MyClass {
    public function __construct($foo, $bizz) {
        $this->foo = $foo;
        $this->bizz = $bizz;
    }
}
