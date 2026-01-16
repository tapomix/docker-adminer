<?php

/** Adminer Plugin - Pre-fill login form with URL params or env defaults */
class DefaultLoginForm extends Adminer\Plugin
{
    public function __construct(
        private readonly array $drivers = ['pgsql', 'sqlite', 'server'], // server = mysql
    ) {
    }

    /** Get optional driver from URL params (e.g., ?pgsql=host) */
    private function getUrlDriver(): ?string
    {
        foreach ($this->drivers as $driver) {
            if (isset($_GET[$driver])) {
                return $driver;
            }
        }

        return null;
    }

    /** Get driver from URL or fallback to env */
    private function getDriver(): string
    {
        return $this->getUrlDriver() ?? (getenv('ADMINER_DEFAULT_DRIVER') ?: '');
    }

    /** Get server from URL or fallback to env */
    private function getServer(): string
    {
        $driver = $this->getUrlDriver();

        return null !== $driver ? $_GET[$driver] : (getenv('ADMINER_DEFAULT_SERVER') ?: '');
    }

    public function loginFormField($name, $heading, $value)
    {
        switch ($name) {
            // filter drivers + pre-select default
            case 'driver':
                $defaultDriver = $this->getDriver();
                $drivers = array_filter(
                    Adminer\SqlDriver::$drivers,
                    fn (string $val): bool => in_array($val, $this->drivers, true),
                    ARRAY_FILTER_USE_KEY,
                );

                $options = [];
                foreach ($drivers as $driver => $label) {
                    $selected = ($driver === $defaultDriver) ? ' selected' : '';
                    $options[] = sprintf('<option value="%s"%s>%s</option>', $driver, $selected, $label);
                }

                $value = sprintf('<select name="auth[%s]">%s</select>' . "\n", $name, implode('', $options));
                break;

            // pre-fill from env variables + set placeholder / autofocus
            case 'server':
                $defaultServer = $this->getServer();
                $value = sprintf('<input type="text" name="auth[%s]" value="%s" title="hostname[:port]" placeholder="<service-db> or <sqlite-file>.db" autofocus>', $name, htmlspecialchars($defaultServer));
                break;

            // only pre-fill from env variables
            case 'username':
            case 'db': // no-break
                if ($env = getenv('ADMINER_DEFAULT_' . strtoupper($name))) {
                    $value = sprintf('<input type="text" name="auth[%s]" value="%s">', $name, htmlspecialchars($env));
                }
                break;
        }

        return $heading . $value;
    }
}
