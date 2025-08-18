<?php

test('user ping works', function () {
    $this->get('/user/ping')->assertOk()->assertJson(['service' => 'user', 'status' => 'ok']);
});

test('admin ping works', function () {
    $this->get('/admin/ping')->assertOk()->assertJson(['service' => 'admin', 'status' => 'ok']);
});

test('clinic ping works', function () {
    $this->get('/clinic/ping')->assertOk()->assertJson(['service' => 'clinic', 'status' => 'ok']);
});

