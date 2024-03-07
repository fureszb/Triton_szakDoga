<?php
// routes/breadcrumbs.php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Ügyfelek főoldal
Breadcrumbs::for('ugyfel.index', function ($trail) {
    $trail->push('Ügyfelek', route('ugyfel.index'));
});

// Új ügyfél létrehozása
Breadcrumbs::for('ugyfel.create', function ($trail) {
    $trail->parent('ugyfel.index');
    $trail->push('Új ügyfél', route('ugyfel.create'));
});

// Ügyfél szerkesztése
Breadcrumbs::for('ugyfel.edit', function ($trail, $id) {
    $trail->parent('ugyfel.index');
    $trail->push('Ügyfél szerkesztése', route('ugyfel.edit', $id));
});

// Ügyfél megtekintése
Breadcrumbs::for('ugyfel.show', function ($trail, $id) {
    $trail->parent('ugyfel.index');
    $trail->push('Ügyfél részletei', route('ugyfel.show', $id));
});

// Megrendelések főoldal
Breadcrumbs::for('megrendeles.index', function ($trail) {
    $trail->push('Megrendelések', route('megrendeles.index'));
});

// Új megrendelés létrehozása
Breadcrumbs::for('megrendeles.create', function ($trail) {
    $trail->parent('megrendeles.index');
    $trail->push('Új megrendelés', route('megrendeles.create'));
});

Breadcrumbs::for('megrendeles.edit', function ($trail, $id) {
    $trail->parent('megrendeles.index');
    $trail->push('Megrendelés szerkesztése', route('megrendeles.edit', $id));
});

// Megrendelés megtekintése
Breadcrumbs::for('megrendeles.show', function ($trail, $id) {
    $trail->parent('megrendeles.index');
    $trail->push('Megrendelés részletei', route('megrendeles.show', $id));
});

