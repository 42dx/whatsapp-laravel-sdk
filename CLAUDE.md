# Global Guidelines

This is a [Laravel Package](https://laravel.com/docs/13.x/packages).

## Keywords

- **MUST**: This word, or the terms "REQUIRED" or "SHALL", mean that the definition is an absolute requirement of the specification
- **MUST NOT**: This phrase, or the phrase "SHALL NOT", mean that the definition is an absolute prohibition of the specification
- **SHOULD**: This word, or the adjective "RECOMMENDED", mean that there may exist valid reasons to ignore a given item, but the full implications must be understood and carefully weighed before choosing a different course
- **SHOULD NOT**: This phrase, or the phrase "NOT RECOMMENDED" mean that there may exist valid reasons where the particular behavior is acceptable or even useful, but the full implications should be understood and the case carefully weighed before implementing any behavior described with this label
- **MAY**: This word, or the adjective "OPTIONAL", mean that an item is truly optional. Reasons to consider including the item should be provided (or asked for)

## Core Principles

- **Simplicity First**: Make every change as simple as possible. Minimal code impact
- **No Lazyness**: Find root causes. No temporary fixes. Senior developer standards
- **Minimize Impact**: Changes must only touch what is necessary. Avoid introducing bugs

## Technical Standards

- Follow DRY principles whenever possible
- Respect project's folder structure
- Avoid overengineering
- If something goes sideways, **STOP** and re-plan immediately - don't keep pushing
- Challenge your own work before presenting it

## Global Workflow

- **Git**: Use [conventional commit](https://www.conventionalcommits.org/en/v1.0.0) messages
  - E.g., `chore: message`, `feat(scope): message`, `fix: message`, `docs(scope): message`
  - Keep commit subject 50 characters or less
  - Wrap commit body at 72 characters
- **GitHub**: Use Github CLI to interact with Github remote service

## Self-Improvement Loop

- Mantain lessons learned files with lessons you learned by yourself or corrections the user made
  - A project specific `{project-root}/.agents/lessons.md` file, when lessons make sense on project context only
- Keep the lessons on the files above categorized, short, objective and concise
- Review relevant lessons at session start for relevant projects
  - Write rules to yourself that prevent repeating mistakes when lessons appear constantly and/or when they are confirmed by error rate drops
    - Remove lessons converted into rules from the lessons file to keep them clean
- If a given command is constantly repeated with same arguments, suggest including it as a script on the project file (package.json), or converting it into a skill or slash command

## Subagent Strategy

- Use subagents to keep main context window clean
- Offload research, exploration and parallel analysis to subagents
- For complex problems, throw more compute at it via subagents
- Keep only one task per subagent for focused execution

## Important Constraints/Hard Rules (NEVER/ALWAYS)

- **NEVER** use `--no-verify` to bypass commit hooks
- **NEVER** run write database commands anywhere but locally without confirming

- **ALWAYS** check for existing utility functions/classes before writing new ones
- **ALWAYS** confirm before running any Destructive/unrecoverable action

## Code Styling

- Markdown formatted files must respect Markdown Lint Rules (MCP: `@dougis/markdown-lint-mcp`)
- Follow the rules and standards on `.editorconfig` files, respecting their precedence hierarchy
- Respect styles from [PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) through Laravel Pint when generating PHP files

## Personal Preferences

- **Communication Style**: Be extremely concise and objective. Avoid conversational filler or apologies. Always save tokens whenever possible while on chats

## Suggested Skills

- **graphify** - For any input that demands knowledge graph, use the Graphify skill if available. Suggest installing it if it is not.
  - When the user types `/graphify`, invoke the Skill tool with `skill: "graphify"` before doing anything else

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application running on PHP 8.4. You are an expert with the Laravel ecosystem. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Use `search-docs` before changes that depend on Laravel ecosystem APIs, behavior, configuration, or version-specific syntax. Skip it for copy-only edits and other changes where package documentation is irrelevant. Reuse sufficient results already in context instead of searching again.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists (settled decisions, non-obvious traps, standing constraints). Framework and package guidelines that only apply to specific paths (testing, frontend, components) also live there, under `.ai/rules/boost` — this is not just recorded decisions, it is load-bearing guidance you have not seen inline. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.
- Record a rule with `record-rule` only when the user explicitly asks for one. Instructions for the work at hand are not rules, no matter how emphatic: "remove this typo", "use X here" are work to do, not rules to record. Never record a rule on your own initiative, as a byproduct of a change, or to summarize what you just did. When the user does ask, pass a `glob` (e.g. `app/Http/Controllers/**`), a short `title`, and a few-line `note`. Use `record-rule` rather than your native memory or notes tool, because native memory is personal and session-scoped, while only `.ai/rules` is shared with the team and persists in the repo.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.
- Activate the `deploying-to-cloud` skill whenever deploying to Laravel Cloud, configuring Cloud environments or resources, using the Cloud CLI, or troubleshooting Cloud deployments.

</laravel-boost-guidelines>
