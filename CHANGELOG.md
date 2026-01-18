<!-- markdownlint-disable MD024 -->
# Changelog

All notable changes to this project will be documented in this file.

---

## [0.1.1] - 2026-01-18

### Changed

- Service network is now internal and must be created manually before starting the container

### Added

- Add alternatives section

---

## [0.1.0] - 2026-01-16

### Added

- Custom Docker image based on Alpine + PHP 8.4
- Adminer 5.4.1 with designs and plugins
- Custom plugins for login form and theme support
- Environment variables for login form pre-fill (`ADMINER_DEFAULT_*`)
- Dark theme support via `ADMINER_THEME`
- Docker Compose configuration with Traefik integration
- Documentation [README](README.md)

---

## About

This project follows [Semantic Versioning](https://semver.org/spec/v2.0.0.html).  
The changelog format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).
