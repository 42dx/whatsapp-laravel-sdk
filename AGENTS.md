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
