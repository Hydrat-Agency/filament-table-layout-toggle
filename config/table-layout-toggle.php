<?php

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
        'local_storage' => true,

        /**
         * If enabled, changing the layout will affect all compatible tables of your app.
         */
        'share_between_pages' => false,
    ],
];
