# CommitManiac

CommitManiac keeps a repository active by publishing one small daily note into `contents/`.
The note is generated locally, so the project no longer depends on a quote API, paid service, or personal access token.

## What gets published?

Every run creates a deterministic daily "greenhouse log":

- `contents/dd.mm.yy.txt`
- a short Git/GitHub/dev fact
- a daily signal and ritual line
- a stable seed so rerunning the same day does not create noisy extra commits

## How to use

1. Create or fork a repository.
2. Enable GitHub Actions.
3. Keep `.github/workflows/schedule.yml` on the default branch.
4. Optional but recommended: set repository variables in `Settings -> Secrets and variables -> Actions -> Variables`:
   - `COMMIT_AUTHOR_NAME` - your GitHub name or username.
   - `COMMIT_AUTHOR_EMAIL` - an email attached to your GitHub account.

The workflow uses GitHub's built-in `GITHUB_TOKEN` and `contents: write` permission, so no `APIKEY`, `OWNER`, or `REPO` secret is required for the normal daily mode.

If the contribution graph does not turn green, check that `COMMIT_AUTHOR_EMAIL` is verified on your GitHub account and that the commits land on the default branch.

## Running locally

```bash
composer install
composer test
php ./public/index.php
```

The script uses `Europe/Amsterdam` by default. Override it with:

```bash
COMMITMANIAC_TIMEZONE=UTC php ./public/index.php
```

## Why this changed

The previous version depended on QuoteSlate. When that service rate-limits or changes behavior, CommitManiac falls back to generic text. The current version generates original content locally, which keeps the daily run free and reliable.

**Feel free to thank by giving a star to this repository!**

[![LinkedIn](https://img.shields.io/badge/LinkedIn-%230077B5.svg?logo=linkedin&logoColor=white)](https://www.linkedin.com/in/lkwebdev/)
