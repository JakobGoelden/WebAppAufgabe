# WebAppAufgabe

## Quick Git Guide: Feature Branches, Rebase & Merge

### Create a Feature Branch
1.  Update main: `git checkout main && git pull`
2.  Create & switch: `git checkout -b <feature>`
3.  Make changes, then commit: `git add . && git commit -m "Add feature"`

### Rebase onto Main
1.  Fetch latest: `git fetch origin`
2.  Rebase: `git rebase origin/main`

### Merge and Clean Up
1.  Push: `git push origin <feature>`
2.  Create a Pull Request. (Check if the version runs of XAMPP to approve)
4.  After approval, merge the PR.
5.  Delete the branch: `git push origin --delete <feature>`
6.  Cleanup local references: `git fetch --prune`   
