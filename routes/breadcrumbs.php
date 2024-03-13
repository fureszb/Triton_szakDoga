<?php
// routes/breadcrumbs.php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home.index', function (BreadcrumbTrail $trail) {
    $trail->push('Kezdőlap', route('home.index'));
});

// Home > Users
Breadcrumbs::for('users.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home.index');
    $trail->push('Felhasználók', route('users.index'));
});

// Home > Users > Edit User
Breadcrumbs::for('users.edit', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('users.index');
    $trail->push('Szerkesztés', route('users.edit', $user));
});

Breadcrumbs::for('users.create', function (BreadcrumbTrail $trail) {
    $trail->parent('users.index');
    $trail->push('Létrehozás', route('users.create'));
});

Breadcrumbs::for('users.show', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('users.index');
    $trail->push('Megtekintés', route('users.show', $user));
});


Breadcrumbs::for('ugyfel.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home.index');
    $trail->push('Ügyfelek', route('ugyfel.index'));
});

Breadcrumbs::for('ugyfel.edit', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('ugyfel.index');
    $trail->push('Szerkesztés', route('ugyfel.edit', $user));
});

Breadcrumbs::for('ugyfel.create', function (BreadcrumbTrail $trail) {
    $trail->parent('ugyfel.index');
    $trail->push('Létrehozás', route('ugyfel.create'));
});

Breadcrumbs::for('ugyfel.show', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('ugyfel.index');
    $trail->push('Megtekintés', route('ugyfel.show', $user));
});



Breadcrumbs::for('megrendeles.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home.index');
    $trail->push('Megrendelesek', route('megrendeles.index'));
});

Breadcrumbs::for('megrendeles.edit', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('megrendeles.index');
    $trail->push('Szerkesztés', route('megrendeles.edit', $user));
});

Breadcrumbs::for('megrendeles.create', function (BreadcrumbTrail $trail) {
    $trail->parent('megrendeles.index');
    $trail->push('Létrehozás', route('megrendeles.create'));
});

Breadcrumbs::for('megrendeles.show', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('megrendeles.index');
    $trail->push('Megtekintés', route('megrendeles.show', $user));
});


Breadcrumbs::for('szerelok.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home.index');
    $trail->push('Szerelők', route('szerelok.index'));
});

Breadcrumbs::for('szerelok.edit', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('szerelok.index');
    $trail->push('Szerkesztés', route('szerelok.edit', $user));
});

Breadcrumbs::for('szerelok.create', function (BreadcrumbTrail $trail) {
    $trail->parent('szerelok.index');
    $trail->push('Létrehozás', route('szerelok.create'));
});

Breadcrumbs::for('szerelok.show', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('szerelok.index');
    $trail->push('Megtekintés', route('szerelok.show', $user));
});


Breadcrumbs::for('anyagok.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home.index');
    $trail->push('Anyagok', route('anyagok.index'));
});

Breadcrumbs::for('anyagok.edit', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('anyagok.index');
    $trail->push('Szerkesztés', route('anyagok.edit', $user));
});

Breadcrumbs::for('anyagok.create', function (BreadcrumbTrail $trail) {
    $trail->parent('anyagok.index');
    $trail->push('Létrehozás', route('anyagok.create'));
});

Breadcrumbs::for('anyagok.show', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('anyagok.index');
    $trail->push('Megtekintés', route('anyagok.show', $user));
});

