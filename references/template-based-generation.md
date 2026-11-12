# DOCX project plan generation per template (Template SKPL OO style)

Repeatable workflow for producing a project plan DOCX from a template
structure (cover, document control, TOC, chapters, attachments), with the
user's standing formatting rules and automatic cleanup of temporary generator
scripts after use.

## Standing formatting rules (always apply)

- Font: Times New Roman, 12 pt, black.
- Line spacing: 1.5.
- Margins: top/bottom 3 cm, left/right 4 cm.
- Sections: cover → revision/control table → table of contents → chapters
  (Pendahuluan, Desain Global, Desain Rinci, ...) → attachments
  (Lampiran: charter, WBS, sprint backlog, jadwal).
- Header: `ponytail:` comment marking deliberate simplifications + upgrade
  path is not required here; keep generated docs plain.

## Procedure

1. **Read the template.** Extract section order and any fixed content from the
   template DOCX (e.g. `Template SKPL OO.docx`) before generating.
2. **Write a generator script.** A throwaway Python script (`python-docx`)
   that builds the DOCX from structured data (markdown source, JSON, or
   hardcoded content). Keep it under `storage/` or the scratch dir — never in
   the repo tree permanently.
3. **Run and place the output.** `python3 storage/gen_plan.py`, move the
   resulting `.docx` (and any generated image, e.g. Gantt PNG) into
   `Dokumen/`.
4. **Clean up.** Delete the generator script and any temporary files
   (`~$*.docx` lock files, scratch CSVs/PNGs not committed) immediately after
   placing outputs:
   ```bash
   rm -f storage/gen_plan.py storage/gen_gantt.py Dokumen/~*.docx
   ```
5. **Commit.** `git add Dokumen/<files> && git commit`.
6. **Verify.** `ls -la Dokumen/*.docx`, `git status --short` should show only
   the committed documents and a clean tree.

## Pitfalls

- **Temp scripts must not be committed.** Generator scripts are disposable;
  leaving them in `storage/` clutters the repo and the user's rule is to
  delete them after use.
- **Word lock files.** `~$*.docx` files appear when Word has the document
  open; remove them before moving/committing (`rm -f Dokumen/~*.docx`).
- **Margins/font via python-docx.** Set section margins in `cm` and the
  `Normal` style font to `Times New Roman`, `Pt(12)`, `RGBColor(0,0,0)`;
  line spacing `1.5` via `paragraph_format.line_spacing = 1.5`.
- **TOC fields are not computed by python-docx.** A TOC field writes field
  code only; Word populates entries when opened. If a static TOC is needed,
  build it manually as a list/tables instead of a field.
- **DOCX is a zip package.** Never edit `document.xml` with `sed`/`patch`;
  always go through python-docx or the `docx` skill's scripts.

## Related

- `references/revisions-and-comments.md` — for reviewing/revising generated docs.
- `scripts/docx_create.py`, `docx_edit.py`, `docx_validate.py` — generic
  docx helpers in the `docx` skill.
