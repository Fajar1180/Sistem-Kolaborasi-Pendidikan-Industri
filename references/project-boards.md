# Project V2 board management (GitHub Projects via `gh` GraphQL)

Used to sweep GitHub Project V2 boards (organization/user projects, not repo
kanban boards) — e.g. move all items to a target status when linked issues are
closed. `gh` has no porcelain for project item field updates, so this uses
`gh api graphql` with `updateProjectV2ItemFieldValue`.

## Prerequisites

- `gh auth status` returns logged in (token with `read:project` scope).
- Know the three IDs for the board:
  - Project node id: `PVT_...`
  - Status single-select field id: `PVTSSF_...`
  - Target option id (e.g. "Done"): `98236657`

## Discovery (run once per board)

1. List project fields:
   ```bash
   gh api graphql -f query='
   { node(id: "PVT_...") { ... on ProjectV2 {
     fields(first: 20) { nodes { ... on ProjectV2SingleSelectField {
       id name options { id name } } } } } } }' | jq
   ```
2. List items with current field values:
   ```bash
   gh api graphql -f query='
   { node(id: "PVT_...") { ... on ProjectV2 {
     items(first: 50) { nodes { id fieldValues(first: 10) { nodes { ... on ProjectV2ItemFieldSingleSelectValue {
       field { name } option { name } } } }
       content { ... on Issue { number state title } } } } } } }' | jq
   ```

## Update loop (status sweep)

For each item, call `updateProjectV2ItemFieldValue` with
`singleSelectOptionId` (NOT `optionId` — that is the older schema name):

```graphql
mutation {
  updateProjectV2ItemFieldValue(input: {
    projectId: "PVT_...",
    itemId: "PVTI_...",
    fieldId: "PVTSSF_...",
    value: { singleSelectOptionId: "98236657" }
  }) { projectV2Item { id } }
}
```

via:

```bash
gh api graphql -f query='<mutation above>'
```

Each item needs its own mutation call — there is no batch endpoint for item
field values.

## Pitfalls

- **Wrong mutation name.** `projectV2ItemFieldValueUpdate` is the legacy
  name; current schema uses `updateProjectV2ItemFieldValue`. Using the old
  name returns `Field 'projectV2ItemFieldValueUpdate' doesn't exist on type
  'Mutation'`.
- **Wrong value key.** The input value object takes `singleSelectOptionId`,
  not `optionId`. `optionId` produces a validation error on the value field.
- **No dry-run.** Mutations write immediately; verify the target option id
  exists on the field before sweeping (see Discovery step 1).
- **Pagination.** `items(first: 50)` caps at 50; use `endCursor`/`after` for
  larger boards.
- **Issue linkage.** Only items backed by issues/pulls have a `content`
  object; draft items have `content: null`. Guard `.content.number` access.

## Verification

After sweeping, re-run the items query and assert every
`fieldValues` node for the status field shows the target option name, e.g.
all `option.name == "Done"`.
