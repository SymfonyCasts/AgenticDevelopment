# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with
code in this repository.

## What this is

The course repo for SymfonyCasts' [Agentic Development](https://symfonycasts.com/screencast/agentic-development)
tutorial: "Time Traveler's Lost & Found", a Symfony 8.1 / PHP 8.4 app.
`sfcasts/` holds the course script (currently just an empty `metadata.yml`).
Because it's a tutorial repo, the app is deliberately half-built — see "Current
state" below before assuming something is missing by mistake.

## Commands

Start Postgres first — the app is configured for it, not SQLite:

```bash
docker compose up -d               # Postgres 16 on a random host port
symfony serve -d                   # web server; also boots the tailwind:build --watch worker
```

`.symfony.local.yaml` registers `symfony console tailwind:build --watch` as a
worker, so `symfony serve` is the intended way to run the app — CSS rebuilds
automatically. Without the Symfony CLI you must run that watcher yourself.

```bash
php bin/console <cmd>              # or: symfony console <cmd>
php bin/console tailwind:build     # one-off CSS build -> var/tailwind/app.built.css
php bin/console make:migration && php bin/console doctrine:migrations:migrate
php bin/console importmap:require <pkg>   # add JS deps (never npm/yarn — see below)
php bin/console debug:router
```

Tests (PHPUnit 13, config in `phpunit.dist.xml`):

```bash
php bin/phpunit                            # whole suite
php bin/phpunit --filter testSomething     # single test
php bin/phpunit tests/Path/To/SomeTest.php # single file
```

The suite is configured strictly: `failOnDeprecation`, `failOnNotice`, and
`failOnWarning` are all true, so a deprecation notice fails the build. Test DB
names get a `_test` suffix automatically (`config/packages/doctrine.yaml`).

## Architecture

- **Frontend has no Node build step.** AssetMapper serves `assets/` directly via
  importmaps (`importmap.php`); JS deps are vendored into `assets/vendor/` by
  `importmap:require`. The `yarn.lock` at the root is vestigial and
  `node_modules/` is gitignored — do not add npm dependencies or a bundler.
  Stimulus + Turbo are wired through `assets/app.js` → `stimulus_bootstrap.js`.
- **Tailwind v4 comes from `symfonycasts/tailwind-bundle`** (standalone binary
  v4.3.3, no PostCSS). Source is the single `@import "tailwindcss";` line in
  `assets/styles/app.css`; the built file lands in `var/tailwind/app.built.css`
  and reaches the page because `app.js` imports `./styles/app.css`.
- **The theme lives in `assets/styles/app.css`.** A v4 `@theme` block defines the
  `tardis` / `accent` colors and the `display` (Orbitron) / `sans` (Inter) font
  tokens, followed by plain rules for `.tardis-lamp` and `.panel-lines`. Setting
  `--font-sans` is what makes Preflight apply Inter to `html`.
- **`base.html.twig` follows the AssetMapper recipe**: `{% block javascripts %}`
  contains only `{{ importmap('app') }}`, `{% block stylesheets %}` is empty, and
  there are no inline `<style>`/`<script>` blocks or CDN tags. Do not reintroduce
  any — third-party JS goes through `importmap:require`. (The guarded FrankenPHP
  hot-reload block is stock recipe code and is inert here; leave it be.)
- **Tailwind v4 scans source files, not the DOM** — including `src/*.php`, which
  is why the `bg-[#...]` values in `ItemsController::ITEMS` compile. Class names
  must therefore appear as complete literals; never assemble one at runtime from
  fragments, or it silently won't be generated.
- **Doctrine + Postgres**, attribute mapping under `src/Entity`, underscore
  naming strategy, `IDENTITY` generation on Postgres. One migration exists (the
  `user` table).
- Autowiring/autoconfiguration is on for everything in `src/` (
  `config/services.yaml`); routes come from `#[Route]` attributes.
- `config/reference.php` is auto-generated and gitignored — never hand-edit it.

## Current state (intentionally incomplete)

- **There is still no `Item` entity, repository, or fixture.** `ItemsController`
  holds the six items in a `private const ITEMS` array keyed by id, as a
  deliberate stand-in: `index()` passes them all, `show(int $id)` looks one up
  and throws `createNotFoundException()` on a miss. Swapping that const for
  Doctrine is the intended next step; the templates already consume the data
  through `items` / `item` variables, so they should not need to change much.
- The claim form in `show.html.twig` is markup only — no Symfony Form type, no
  route or handler, no CSRF token. Submitting it does nothing.
- The only entity is `User`; EasyAdmin is installed and routed but has no
  Dashboard controller, and the security firewall has a user provider but no
  authenticator or login route.
