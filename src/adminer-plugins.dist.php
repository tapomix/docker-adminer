<?php
/**
 * Adminer Plugins Configuration
 *
 * @see https://www.adminer.org/plugins/
 */

// Load plugin definitions
require_once __DIR__ . '/my-plugins/default-login-form.php';
require_once __DIR__ . '/my-plugins/default-theme.php';

// require_once __DIR__ . '/plugins/database-hide.php';
// require_once __DIR__ . '/plugins/enum-option.php';
// require_once __DIR__ . '/plugins/json-column.php';
// require_once __DIR__ . '/plugins/version-noverify.php';
// ...

return [
    // custom plugins
    new DefaultLoginForm(drivers: ['pgsql', 'sqlite', 'server']),
    new DefaultTheme(),
    // official plugins
    // new AdminerDatabaseHide(['postgres', 'template1']),
    // new AdminerEnumOption(),
    // new AdminerJsonColumn(),
    // new AdminerVersionNoverify(),
    // ...
];
