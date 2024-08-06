<?php

use Hydrat\TableLayoutToggle\Persisters;

return [

    'default_layout' => 'list',

    'toggle_action' => [
        /**
         * Should the toggle action get automatically displayed ?
         */
        'enabled' => true,

        /**
         * The filament view hook to render the action in.
         */
        'position' => 'tables::toolbar.search.after',

        /**
         * Action icons when toggling between list and grid layout.
         */
        'list_icon' => 'heroicon-o-list-bullet',
        'grid_icon' => 'heroicon-o-squares-2x2',
    ],

    'persist' => [
        /**
         * Enable to persist selected layout in user's local storage.
         */
        'persiter' => Persisters\LocalStoragePersister::class,

        /**
         * Configure options for the cache persister.
         */
        'cache' => [
            'store' => null, // the cache driver to use, defaults to config('cache.default')

            'time' => 60 * 24 * 7, // the TTL in minutes, defaults to a week
        ],

        /**
         * If enabled, the toggle state will be shared for all togglable tables of the app.
         */
        'share_between_pages' => false,
    ],

];
