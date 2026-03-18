---
title: "Multi-Agent Shared State Protocol"
version: "1.0.0"
pattern: "supervisor + file-system-memory"
created_at: "2026-03-11T21:47:48+07:00"
---

# Multi-Agent Shared State Protocol

This document defines how all agents interacting with this project MUST manage shared state. Based on the **file system memory** pattern from the multi-agent-patterns skill — agents read and write to persistent storage as coordination mechanism.

---

## 🏛️ Architecture

```
Supervisor (User / Orchestrating Agent)
│
├── 🎨 UI Designer Agent      → owns views + CSS
├── ⚙️  Backend Architect Agent → owns controllers + services + routes
├── 🔍 QA Validator Agent     → owns testing + bug reports
└── 🧠 Context Manager Agent  → owns .agent/ directory
         │
         └── File System Memory (shared state)
               ├── .agent/state/shared-state.json  ← SINGLE SOURCE OF TRUTH
               ├── .agent/state/task-queue.json    ← Task coordination
               ├── .agent/registry/agents.json     ← Agent capabilities
               ├── .agent/handoffs/                ← Agent-to-agent handoffs
               ├── .agent/memory/                  ← Per-agent learned memory
               └── .agent/context/                 ← Human-readable context docs
```

---

## 📋 Protocol Rules

### Rule 1: SESSION START — Always Read First
Every agent MUST read these files at the start of a new session, before taking ANY action:

```
1. .agent/context/QUICK_REF.md          ← 30-second overview
2. .agent/state/shared-state.json       ← Current project state
3. .agent/state/task-queue.json         ← Available tasks to claim
4. .agent/handoffs/*.handoff.json       ← Any pending handoffs for this agent type
5. .agent/memory/*.json                 ← Lessons learned from previous agents
```

### Rule 2: TASK CLAIMING — Atomic Update
To claim a task, update `task-queue.json` atomically:
```json
{
  "id": "TASK-XXX",
  "status": "in_progress",
  "claimed_by": "{your-session-id}",
  "claimed_at": "{ISO timestamp}"
}
```
**Do NOT start working on a task marked `in_progress` or `completed` by another agent.**

### Rule 3: TASK COMPLETION — Write State Update
When completing a task:
1. Update `task-queue.json` → move task from `tasks[]` to `completed_tasks[]`
2. Update `shared-state.json` → update affected `pages[].status` and `bugs[]`
3. Write to `memory/{your-session-id}.json` → any new lessons/decisions learned
4. Create handoffs in `handoffs/` if downstream agents need to act

### Rule 4: HANDOFFS — Explicit Context Passing
When passing work between agent types:
```
Format: {from-session}-to-{agent-type}-{short-description}.handoff.json
Example: ab910e07-to-ui-designer-hotel-details.handoff.json
```
Handoffs MUST include:
- `context_summary` — what was done
- `current_state` — state of files now
- `target_state` — what needs to happen
- `verification_steps` — how to confirm success

### Rule 5: CONFLICT RESOLUTION
- **Last-write-wins** per JSON key (for status, page state)
- **Append-only** for `bugs[]`, `completed_tasks[]`, `decisions[]` arrays
- **Never delete** entries from bugs or memory files — mark as `resolved`

### Rule 6: CSS MODIFICATION RULE
**ALWAYS** append new CSS to end of `style.css`:
```bash
cat >> public/assets/front/css/style.css << 'CSSEOF'
/* New CSS section */
CSSEOF
```
Never insert CSS in the middle of the file — risk of specificity conflicts.

### Rule 7: VERIFICATION BEFORE MARKING DONE
Before marking any UI task as complete:
```bash
# Quick verification without browser
curl -sf http://127.0.0.1:8000/{path} | grep 'class="{expected-class}"'

# Check for PHP errors
tail -20 storage/logs/laravel.log
```

---

## 🔄 Workflow Diagram

```
New Session Starts
       │
       ▼
Read QUICK_REF.md + shared-state.json + task-queue.json
       │
       ▼
Are there pending handoffs for my agent type?
  ├── YES → Process handoff first (highest priority)
  └── NO  → Pick highest priority open task from task-queue.json
       │
       ▼
Claim task (update status='in_progress', claimed_by='{session-id}')
       │
       ▼
Execute task
  ├── Read relevant memory files for context/gotchas
  ├── Make changes to project files
  └── Verify changes work
       │
       ▼
Task Complete:
  ├── Update task-queue.json (move to completed_tasks)
  ├── Update shared-state.json (pages status, bugs)
  ├── Write memory/{session-id}.json (lessons learned)
  └── Create handoffs/ if needed for other agents
       │
       ▼
Pick next task or end session
```

---

## 📁 File Reference

| File | Purpose | Who Writes | Who Reads |
|---|---|---|---|
| `state/shared-state.json` | Project ground truth | All agents | All agents |
| `state/task-queue.json` | Task coordination | All agents | All agents |
| `registry/agents.json` | Agent capabilities | Context Manager | All agents |
| `handoffs/*.json` | Agent-to-agent comms | Source agent | Target agent |
| `memory/*.json` | Learned knowledge | Individual agents | All agents |
| `context/QUICK_REF.md` | Human-readable overview | Context Manager | All agents |
| `context/MASTER_CONTEXT.md` | Deep project context | Context Manager | All agents |
| `context/TASK_LOG.md` | Task history | Context Manager | All agents |
| `context/DESIGN_DECISIONS.md` | Why decisions were made | Context Manager | UI Designer |

---

## ⚡ Quick Patterns

### Pattern: Check If Task Is Already Done
```bash
cat .agent/state/task-queue.json | grep -A5 '"id": "TASK-XXX"'
```

### Pattern: Update Task Status
Modify the JSON directly in `.agent/state/task-queue.json`

### Pattern: Read Latest Memory
```bash
ls -t .agent/memory/*.json | head -3
cat .agent/memory/{latest}.json
```

### Pattern: Create Handoff
Create file: `.agent/handoffs/{from}-to-{type}-{topic}.handoff.json`

---

## 🧩 Integrated with Skills

This protocol works alongside:
- **context-management-context-save** → saves context snapshots to `.agent/context/`
- **baseline-ui** → enforces UI quality constraints for ui-designer agent
- **backend-architect** → guides architecture decisions for backend-architect agent
- **design-spells** → design quality patterns for ui-designer agent

---

*Protocol v1.0 — Established session ab910e07 — 2026-03-11*
