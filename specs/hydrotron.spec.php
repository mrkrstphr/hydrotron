<?php

use mrkrstphr\Hydrotron\Hydrotron;

describe(Hydrotron::class, function () {
    describe('when()', function () {
        it('should run the callbacks when the value exists', function () {
            $hydro = new Hydrotron(['foo' => 'bar']);
            $value1 = null;
            $value2 = null;

            $callback1 = function ($value) use (&$value1) {
                $value1 = $value;
            };

            $callback2 = function ($value) use (&$value2) {
                $value2 = $value;
            };

            $hydro->when('foo', $callback1, $callback2);

            expect($value1)->to->equal('bar');
            expect($value2)->to->equal('bar');
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
            $hydro = new Hydrotron(['foo' => 'bar', 'bizz' => 'buzz']);
            $instance = null;

            $callback1 = function (MyClass $c) use (&$instance){
                $instance = $c;
            };

            $hydro->instantiateWhen('foo', MyClass::class, $callback1);

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
