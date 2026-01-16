# Tapomix / Docker - Adminer

Custom Docker image for [Adminer](https://www.adminer.org/) database management tool, configured to run behind [Traefik](https://traefik.io/traefik) reverse proxy.

Built on Alpine Linux with PHP 8.4, supports MySQL, PostgreSQL and SQLite.

## Why this custom image?

The [official Docker image](https://github.com/TimWolla/docker-adminer) has a [bug with dark themes](https://github.com/TimWolla/docker-adminer/pull/216) that prevents them from loading correctly. This custom image fixes that issue.

Additional features:

- Full support for login form pre-fill via environment variables (`ADMINER_DEFAULT_*`)
- Driver filtering (show only the drivers you need)
- Easy theme customization with light/dark mode support

## Installation

Clone this repository:

```bash
git clone https://github.com/tapomix/docker-adminer.git adminer
```

## Configuration

### Environment Variables

Copy the `.env.dist` file to `.env` and customize it:

```bash
cp .env.dist .env
```

#### Docker / Traefik

| Variable | Description |
| -------- | ----------- |
| `CONTAINER_NAME` | Name of the Docker container |
| `SERVICE_VERSION` | Docker image version (default: `latest`) |
| `SERVICE_NET` | Network for inter-service communication |
| `TRAEFIK_HOST` | Hostname for Traefik routing |
| `TRAEFIK_NET` | Traefik network name (default: `traefik-net`) |
| `TRAEFIK_PORT` | Port exposed by the service (default: `8080`) |
| `TZ` | Timezone (default: `Etc/UTC`) |
| `UID` / `GID` | User/Group ID for file permissions (default: `1000`) |

#### Adminer

| Variable | Description |
| -------- | ----------- |
| `ADMINER_DEFAULT_DRIVER` | Default driver: `pgsql`, `sqlite`, or `server` (MySQL) |
| `ADMINER_DEFAULT_SERVER` | Pre-filled server hostname |
| `ADMINER_DEFAULT_USERNAME` | Pre-filled username |
| `ADMINER_DEFAULT_DB` | Pre-filled database name |
| `ADMINER_THEME` | Theme from `designs/` folder (e.g., `galkaev`) |

## Customization

### Plugins

Copy the plugin template and edit it to enable/disable plugins:

```bash
cp src/adminer-plugins.dist.php src/adminer-plugins.php
```

Custom plugins are located in `src/my-plugins/`:

- `default-login-form.php` - Filter drivers and pre-fill login form from env
- `default-theme.php` - Load theme CSS based on `ADMINER_THEME`

Official plugins from Adminer are available in `/app/plugins/` inside the container. You can find the list at <https://www.adminer.org/en/plugins/>.

### Themes

Available themes can be found at <https://www.adminer.org/en/#extras>.

Set `ADMINER_THEME` to the folder name (e.g., `galkaev`, `hydra`, `nette`).

## Usage

### Commands

```bash
# Build and start
docker compose up -d --build

# Start (without rebuild)
docker compose up -d

# Stop
docker compose down

# View logs
docker compose logs -f

# Rebuild without cache
docker compose build --no-cache
```

### Custom theme

To use a custom theme not included in Adminer:

1. Create your custom theme in `.docker/theme.css`
2. Set `ADMINER_THEME=custom` in your `.env` file
3. Mount your theme file in `compose.override.yaml`:

```yaml
// compose.override.yaml
services:
  adminer:
    volumes:
      - .docker/theme.css:/app/designs/custom/adminer.css:ro # or adminer-dark.css
```

Then rebuild + restart the container.

### Connecting to databases

#### Docker containers (via service network)

For databases running in other Docker containers, use the shared `service-net` network.

Add the network to your database service:

```yaml
// compose.yaml (in your container with db service)
services:
  db:
    networks:
      - db-net

networks:
  db-net:
    external: true
    name: adminer  # or your SERVICE_NET value
```

Then use the container/service name as server (e.g., `<container>-db`) in the login form.

#### Host machine databases

For databases running on the host machine, add host network access:

```yaml
// compose.override.yaml
services:
  adminer:
    extra_hosts:
      - "host.docker.internal:host-gateway"
```

Then use `host.docker.internal` as server in the login form.

#### SQLite files

Place your SQLite file in `.docker/sql/` and use `/sql/filename.db` as server in the login form.

## Resources

- Adminer official site: <https://www.adminer.org/en/>
- TimWolla Docker Adminer : <https://github.com/TimWolla/docker-adminer>
