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
php bin/console foundry:load-fixtures dev  # rebuild the DB and load every story
php bin/console foundry:load-fixtures items --append   # one story, keep existing rows
php bin/console make:factory / make:story # scaffold a Foundry factory or story
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
  is why the `bg-[#...]` values in `ItemStory` compile. Class
  names must therefore appear as complete literals; never assemble one at runtime
  from fragments, or it silently won't be generated. An item's `colorClass` is a
  DB column, so any new colour must also exist verbatim somewhere under `src/`.
- **Doctrine + Postgres**, attribute mapping under `src/Entity`, underscore
  naming strategy, `IDENTITY` generation on Postgres. Two migrations exist (the
  `user` and `item` tables).
- **Fixtures go through Zenstruck Foundry alone** — there is no
  DoctrineFixturesBundle and no `src/DataFixtures/`. `ItemStory` (name `items`)
  holds the six canonical items and `UserStory` (name `users`) the admin; both
  carry `groups: ['dev']`, so `foundry:load-fixtures dev` loads everything and a
  bare name loads just one. Stories register each object with `addState()`, so
  tests can reach them via `ItemStory::get('phaser')` or
  `ItemStory::getRandom('items')`.
- **A story is mandatory, a factory is not — and there are no factories.**
  `#[AsFixture]` throws on anything that isn't a `Story`, so a story is the only
  entry point `foundry:load-fixtures` can find; a factory can never be one. Both
  stories build their objects with Foundry's `persist(Class::class, [...])`
  helper, which spins up an anonymous factory internally, so `src/Factory/`
  doesn't exist. Add a real factory only once something needs varied objects —
  `persistent_factory(Item::class)` inline in a test, or `make:factory`.
- **`foundry:load-fixtures` rebuilds the database, it does not merely purge it.**
  `orm.reset.mode` is set to `migrate` in `config/packages/zenstruck_foundry.yaml`
  so the rebuild replays the migrations; the default `schema` mode would drop and
  recreate the schema and wipe `doctrine_migration_versions`, leaving
  `migrations:status` convinced every migration is pending. Pass `--append` to
  skip the reset. A full reset means item ids are stable at 1–6.
- `UserStory` creates the single `ROLE_ADMIN` account —
  `the.curator@lost-and-found.time` / `tardis` (the `ADMIN_EMAIL` and `PASSWORD`
  consts). `password` is a hashed column, so the story injects
  `PasswordHasherFactoryInterface` and hashes the plain value itself; stories are
  autoconfigured services, which is what makes that injection work.
- Autowiring/autoconfiguration is on for everything in `src/` (
  `config/services.yaml`); routes come from `#[Route]` attributes.
- `config/reference.php` is auto-generated and gitignored — never hand-edit it.

## Current state (intentionally incomplete)

- `Item` is now a real entity backed by Doctrine. `ItemsController::index()`
  injects `ItemRepository` and sorts by id; `show(Item $item)` relies on the
  `EntityValueResolver` for lookup and the 404, so there is no manual
  `createNotFoundException()` any more.
- The claim form in `show.html.twig` is markup only — no Symfony Form type, no
  route or handler, no CSRF token. Submitting it does nothing.
- EasyAdmin is installed and routed but has no Dashboard controller, and the
  security firewall has a user provider but no authenticator or login route.
