const preset = require('./vendor/filament/filament/tailwind.config.preset')

module.exports = {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './src/**/*.php',
        './resources/views/**/*.blade.php',
    ],
    // Tailwind v4 specific configuration
    future: {
        hoverOnlyWhenSupported: true,
    },
}
