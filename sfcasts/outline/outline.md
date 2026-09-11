**1. What is agentic development?** 

It is when you delegate the implementation of a feature to an agent. For this purpose, the agent needs access to tools
so it can read files, execute code, etc. And, the agent decides by itself what tools to use in order to accomplish the goal.
The outcome is a bunch of code written by the agent, which I recommend you to always review and push back on anything
that doesn't fit your expectations.

This new way of writing code shifts the developer's role from coder to reviewer/director, and has an
inherent non-deterministic nature. The same prompt can yield different results. 
It replaces "type exactly this" with verification and tests.

A quick mention of the existing tools that are out there (Claude Code, Codex, Cursor, etc.).
We'll use Claude Code. These tools are specific for agentic development, they come with a bunch of tools
that make AI models much more capable of writing code and solve problems.

**2. Install & set up Claude Code** — Get installed and authenticated,
understand the permission model, and make the agent's first act.

Note: CC requires Node 22 and NPM

Claude Code is a terminal application (there's a desktop app too, but we won't use it). To install it, you'll require Node 22 and NPM.
(we won't show how to install those). Then, you can install it globally with:

```terminal
    npm install -g @anthropic-ai/claude-code
```

Note: If you run into troubles you can visit the official documentation https://code.claude.com/docs/en/overview#get-started

And that's it, you can now use it by moving onto your project `cd /path/to/project`, and run `claude`.
It will ask a few questions the first time you open it on a project.

- Run `/init` to generate the CLAUDE.md file: This CLAUDE.md file is loaded into the agent's
context automatically at the start of every session, so don't let it grow too large.

Here's where you can add rules, instructions, and other information that you want the agent to know about your project.
AI models already know a lot about PHP, Symfony, Doctrine, etc., so only explain the things that are specific to your project.
For example, if you have a particular way to handle entity relationships, add that to the CLAUDE.md file.

- A quick mention of different `claude.md` files (local/global and per-project basis)
- Explain a few basic (most important) CC commands: `resume`, `clear`, `compact`, `rewind`, `model`
- Cover models and effort briefly. For code writing tasks always use Opus, for the most complex tasks use Fable,
and for quick tasks like scanning files, replacing text, etc. use Sonnet or Haiku.

**3. First Claude Code interaction**

Ask Claude to do a small task that will be likely one-shot and easy to verify. Rename a few fields
from the public claims table.

**4. Full Agentic Development Loop** 

Task: TODO
Start by creating a plan document. Switch to plan mode (quick explanation about modes) and write the prompt,
then review the plan, ask for a few changes, and then ask to implement it.

**5. Tests as the contract** 

Reviewing all the changes is exhausting and easy to miss things. We need some tests!
Prompt the agent to write tests for the feature you just implemented, and quickly review them.
This is something we always want to the agent to do for us, so we can add a rule to `claude.md`.
You can tell it to write the tests after implementation, but I love TDD, and it usually yields better tests,
so we'll specify that Claude should implement the tests first, and then implement the feature.

- Add another rule to `claude.md` to run tests after finishing a task

**6. Implement Second feature** 

Task: TODO
Improve the workflow. Start by creating a plan document.
Switch to plan mode (a quick explanation about modes), and write the prompt, then review the plan,
ask for a few changes, and then ask to implement it.

Check that the tests pass, and a quick UI check.

**7. Skills** 

Reuse your prompts by leveraging CC skills. Other agentic tools may use a 
different name but the concept is the same.

If you find yourself repeating the same prompt over and over, you can create a skill.
A skill is no more than a `md` file with a prompt that lives in a specific directory (`.claude/skills/<skill-name>/SKILL.md`).
You can define input variables if necessary and specify how the output should be. 

To use a skill you just call it in your prompt like if it were a command `/my-skill`.

```prompt
    /crunch this @file/path
```

**8. Context Management** 

- Explain briefly how context window works. Why it is important keeping it clean and focused.
- Talk about `/clear`, `/compact`, `/status` commands
- Explain differences between memory and claude.md
- Also mention that beyond 40% of context usage answers start to degrade quickly

**9 Implement a big feature??** — Just to see the whole workflow again

**Extras**

- MCP integrations
- Custom agents
- Worktrees
- Hooks
- Claude Loops





